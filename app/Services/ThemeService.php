<?php

namespace App\Services;

use App\Models\Theme;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use ZipArchive;

class ThemeService
{
    protected $themesPath;
    protected $assetsPath;

    // PHP ফাইল স্ক্যানিংয়ের জন্য ব্লকড এক্সটেনশন
    protected $blockedExtensions = [
        'phtml',
        'php3',
        'php4',
        'php5',
        'php7',
        'phps',
        'pht',
        'phar',
        'vbs',
        'asp',
        'aspx',
        'jsp',
        'py',
        'rb',
        'pl',
        'cgi',
        'exe',
        'bat',
        'sh'
    ];

    // অনুমোদিত ফাইল এক্সটেনশন (সাদা তালিকা)
    protected $allowedExtensions = [
        'blade.php',
        'php',
        'css',
        'js',
        'jpg',
        'jpeg',
        'png',
        'gif',
        'svg',
        'webp',
        'ico',
        'woff',
        'woff2',
        'ttf',
        'eot',
        'json',
        'xml',
        'txt'
    ];

    // সন্দেহজনক প্যাটার্ন
    protected $suspiciousPatterns = [
        'eval\s*\(',
        'system\s*\(',
        'exec\s*\(',
        'shell_exec\s*\(',
        'passthru\s*\(',
        'popen\s*\(',
        'proc_open\s*\(',
        'base64_decode\s*\(',
        'gzuncompress\s*\(',
        'str_rot13\s*\(',
        'assert\s*\(',
        'file_put_contents\s*\(',
        'fwrite\s*\(',
        'curl_exec\s*\(',
        // '`[^`]*`',
        '\$\_GET',
        '\$\_POST',
        '\$\_REQUEST',
        '\$\_COOKIE',
        '\$\_FILES',
        '\$\_SERVER',
    ];

    public function __construct()
    {
        $this->themesPath = resource_path('views/themes');
        $this->assetsPath = public_path('themes');
    }

    public function scanThemes(): array
    {
        $themes = [];

        if (!File::exists($this->themesPath)) {
            return $themes;
        }

        $directories = File::directories($this->themesPath);

        foreach ($directories as $directory) {
            $themeFolder = basename($directory);
            if ($themeFolder === 'default') {
                continue;
            }
            $themeInfo   = $this->getThemeInfoFromFolder($themeFolder);
            if ($themeInfo) {
                $themes[] = $themeInfo;
            }
        }

        return $themes;
    }

    public function getThemeInfoFromFolder(string $folder): ?array
    {
        $themeJsonPath = $this->themesPath . '/' . $folder . '/theme.json';

        $defaultInfo = [
            'name'         => ucfirst(str_replace('-', ' ', $folder)),
            'folder'       => $folder,
            'version'      => '1.0.0',
            'author'       => 'Unknown',
            'description'  => 'No description provided',
            'screenshot'   => null,
            'is_installed' => false,
            'is_active'    => false,
            'is_core'      => false,
        ];

        if (File::exists($themeJsonPath)) {
            $jsonData = json_decode(File::get($themeJsonPath), true);
            if ($jsonData) {
                $defaultInfo = array_merge($defaultInfo, [
                    'name'        => $jsonData['name'] ?? $defaultInfo['name'],
                    'version'     => $jsonData['version'] ?? $defaultInfo['version'],
                    'author'      => $jsonData['author'] ?? $defaultInfo['author'],
                    'description' => $jsonData['description'] ?? $defaultInfo['description'],
                    'screenshot'  => $jsonData['screenshot'] ?? null,
                    'requires'    => $jsonData['requires'] ?? [],
                    'settings'    => $jsonData['settings'] ?? [],
                ]);
            }
        }

        $dbTheme = Theme::where('folder', $folder)->first();

        if ($dbTheme) {
            $defaultInfo['is_installed'] = true;
            $defaultInfo['is_active']    = $dbTheme->is_active;
            $defaultInfo['is_core']      = $dbTheme->is_core;
            $defaultInfo['id']           = $dbTheme->id;
            $defaultInfo['db_version']   = $dbTheme->version;
        }

        if ($defaultInfo['screenshot']) {
            $screenshotPath = $this->assetsPath . '/' . $folder . '/' . $defaultInfo['screenshot'];
            if (File::exists($screenshotPath)) {
                $defaultInfo['screenshot_url'] = asset('themes/' . $folder . '/' . $defaultInfo['screenshot']);
            }
        } else {
            $defaultScreenshot = $this->assetsPath . '/' . $folder . '/screenshot.png';
            if (File::exists($defaultScreenshot)) {
                $defaultInfo['screenshot_url'] = asset('themes/' . $folder . '/screenshot.png');
            }
        }

        return $defaultInfo;
    }

    public function getAllAvailableThemes(): array
    {
        return Cache::remember('available_themes', 3600, function () {
            $scannedThemes   = $this->scanThemes();
            $dbThemes        = Theme::all()->keyBy('folder');
            $availableThemes = [];

            foreach ($scannedThemes as $scannedTheme) {
                $dbTheme           = $dbThemes->get($scannedTheme['folder']);
                $availableThemes[] = array_merge($scannedTheme, [
                    'is_core'   => $dbTheme ? $dbTheme->is_core : false,
                    'id'        => $dbTheme ? $dbTheme->id : null,
                    'is_active' => $dbTheme ? $dbTheme->is_active : false,
                ]);
            }

            return $availableThemes;
        });
    }

    public function isValidThemeFolder(string $folder): bool
    {
        $themePath = $this->themesPath . '/' . $folder;

        if (!File::exists($themePath)) {
            return false;
        }

        if (!File::exists($themePath . '/layouts/app.blade.php')) {
            return false;
        }

        return true;
    }

    public function clearCache(): void
    {
        Cache::forget('available_themes');
        Cache::forget('active_theme');
        Cache::forget('all_themes');
    }

    /**
     * ফাইলের ধরন চেক করুন (ব্লেড ফাইল কিনা)
     */
    private function isBladeFile(string $filename): bool
    {
        return Str::endsWith($filename, '.blade.php');
    }

    /**
     * ফাইল অনুমোদিত কিনা চেক করুন
     */
    private function isFileAllowed(string $filename): bool
    {
        // ব্লেড ফাইল চেক
        if ($this->isBladeFile($filename)) {
            return true;
        }

        // এক্সটেনশন চেক
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        return in_array(strtolower($extension), $this->allowedExtensions);
    }

    /**
     * থিম ফোল্ডার স্ক্যান করে বিপজ্জনক ফাইল চেক করুন
     *
     * @param string $folderPath
     * @return array ['safe' => bool, 'message' => string, 'files' => array]
     */
    public function scanThemeForSecurity(string $folderPath): array
    {
        $dangerousFiles    = [];
        $suspiciousContent = [];

        if (!File::exists($folderPath)) {
            return [
                'safe'    => false,
                'message' => 'থিম ফোল্ডার পাওয়া যায়নি।',
                'files'   => []
            ];
        }

        // সব ফাইল রিকার্সিভভাবে স্ক্যান করুন
        $allFiles = File::allFiles($folderPath);

        foreach ($allFiles as $file) {
            $relativePath = $file->getRelativePathname();
            $fileSize     = $file->getSize();

            // ফাইলের নাম থেকে এক্সটেনশন বের করুন
            $filename = $file->getFilename();

            // ১. ব্লেড ফাইল চেক করুন (এগুলো অনুমোদিত)
            if ($this->isBladeFile($filename)) {
                // ব্লেড ফাইলের ভিতরে সন্দেহজনক কোড চেক করুন
                $content = File::get($file);
                foreach ($this->suspiciousPatterns as $pattern) {
                    if (preg_match('/' . $pattern . '/i', $content)) {
                        $suspiciousContent[] = [
                            'path'    => $relativePath,
                            'type'    => 'suspicious_code',
                            'pattern' => $pattern,
                            'line'    => $this->getLineNumber($content, $pattern)
                        ];
                        break;
                    }
                }
                continue; // ব্লেড ফাইল চেক করা শেষ, পরবর্তী ফাইলে যান
            }

            // ২. অন্যান্য ফাইলের এক্সটেনশন চেক করুন
            $extension = $file->getExtension();

            // ব্লকড এক্সটেনশন চেক করুন
            if (in_array(strtolower($extension), $this->blockedExtensions)) {
                $dangerousFiles[] = [
                    'path'      => $relativePath,
                    'type'      => 'blocked_extension',
                    'extension' => $extension,
                    'size'      => $this->formatBytes($fileSize)
                ];
                continue;
            }

            // ৩. অনুমোদিত এক্সটেনশন চেক করুন (শুধুমাত্র CSS, JS, Images ইত্যাদি)
            if (!in_array(strtolower($extension), $this->allowedExtensions)) {
                $dangerousFiles[] = [
                    'path'      => $relativePath,
                    'type'      => 'unexpected_extension',
                    'extension' => $extension,
                    'size'      => $this->formatBytes($fileSize)
                ];
                continue;
            }

            // ৪. JS ফাইলের ভিতরে সন্দেহজনক কোড চেক করুন (ঐচ্ছিক)
            if ($extension === 'js') {
                $content = File::get($file);
                foreach ($this->suspiciousPatterns as $pattern) {
                    if (preg_match('/' . $pattern . '/i', $content)) {
                        $suspiciousContent[] = [
                            'path'    => $relativePath,
                            'type'    => 'suspicious_code_js',
                            'pattern' => $pattern,
                            'line'    => $this->getLineNumber($content, $pattern)
                        ];
                        break;
                    }
                }
            }

            // ৫. ফাইলের সাইজ চেক করুন (অস্বাভাবিক বড় ফাইল)
            if ($fileSize > 5 * 1024 * 1024) { // 5MB
                $dangerousFiles[] = [
                    'path'  => $relativePath,
                    'type'  => 'oversized_file',
                    'size'  => $this->formatBytes($fileSize),
                    'limit' => '5MB'
                ];
            }
        }

        // রিপোর্ট তৈরি করুন
        if (count($dangerousFiles) > 0 || count($suspiciousContent) > 0) {
            $message = $this->generateSecurityReport($dangerousFiles, $suspiciousContent);
            return [
                'safe'               => false,
                'message'            => $message,
                'dangerous_files'    => $dangerousFiles,
                'suspicious_content' => $suspiciousContent
            ];
        }

        return [
            'safe'    => true,
            'message' => 'থিমটি নিরাপদ। কোনো বিপজ্জনক ফাইল পাওয়া যায়নি।',
            'files'   => []
        ];
    }

    /**
     * জিপ ফাইল স্ক্যান করে নিরাপত্তা চেক করুন
     *
     * @param string $zipPath
     * @return array
     */
    /**
     * জিপ ফাইল স্ক্যান করে নিরাপত্তা চেক করুন
     *
     * @param string $zipPath
     * @return array
     */
    public function scanZipForSecurity(string $zipPath): array
    {
        $zip            = new ZipArchive();
        $dangerousFiles = [];

        if ($zip->open($zipPath) !== true) {
            return [
                'safe'    => false,
                'message' => 'জিপ ফাইল খোলা সম্ভব হয়নি।'
            ];
        }

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $fileName = $zip->getNameIndex($i);

            // ডিরেক্টরি চেক করুন (ফোল্ডার)
            // জিপ ফাইলে ডিরেক্টরি সাধারণত '/' দিয়ে শেষ হয়
            if (substr($fileName, -1) === '/') {
                // ডিরেক্টরি是完全正常的, এটা স্কিপ করুন
                continue;
            }

            // ব্লেড ফাইল চেক করুন (এগুলো অনুমোদিত)
            if ($this->isBladeFile($fileName)) {
                continue;
            }

            $extension = pathinfo($fileName, PATHINFO_EXTENSION);

            // ১. ব্লকড এক্সটেনশন চেক করুন
            if (in_array(strtolower($extension), $this->blockedExtensions)) {
                $dangerousFiles[] = [
                    'file'      => $fileName,
                    'type'      => 'blocked_extension',
                    'extension' => $extension
                ];
                continue;
            }

            // ২. ফাইলের এক্সটেনশন নেই এমন ফাইল চেক করুন
            if (empty($extension)) {
                // এক্সটেনশন নেই, কিন্তু ডিরেক্টরি না হলে সেটা সন্দেহজনক
                if (!is_dir($fileName)) {
                    $dangerousFiles[] = [
                        'file'      => $fileName,
                        'type'      => 'no_extension',
                        'message'   => 'ফাইলের কোনো এক্সটেনশন নেই'
                    ];
                }
                continue;
            }

            // ৩. অনুমোদিত এক্সটেনশন চেক করুন
            if (!in_array(strtolower($extension), $this->allowedExtensions)) {
                $dangerousFiles[] = [
                    'file'      => $fileName,
                    'type'      => 'unexpected_extension',
                    'extension' => $extension
                ];
                continue;
            }

            // ৪. আপার ডিরেক্টরি অ্যাক্সেস চেক করুন (../)
            if (strpos($fileName, '../') !== false || strpos($fileName, '..\\') !== false) {
                $dangerousFiles[] = [
                    'file'    => $fileName,
                    'type'    => 'path_traversal',
                    'message' => 'ডিরেক্টরি ট্রাভার্সাল অ্যাটাক সম্ভাব্য'
                ];
                continue;
            }

            // ৫. হিডেন ফাইল চেক করুন (.htaccess, .env ইত্যাদি)
            $baseName = basename($fileName);
            if (strpos($baseName, '.') === 0 && $baseName !== '.gitignore') {
                $dangerousFiles[] = [
                    'file'    => $fileName,
                    'type'    => 'hidden_file',
                    'message' => 'হিডেন ফাইল অনুমোদিত নয়'
                ];
                continue;
            }

            // ৬. PHP ফাইল চেক করুন (শুধুমাত্র public ফোল্ডারে অনুমোদিত নয়, বরং完全不 অনুমোদিত)
            if ($extension === 'php') {
                $dangerousFiles[] = [
                    'file'    => $fileName,
                    'type'    => 'php_file_not_allowed',
                    'message' => 'PHP ফাইল থিমে অনুমোদিত নয় (শুধু ব্লেড ফাইল ব্যবহার করুন)'
                ];
            }
        }

        $zip->close();

        if (count($dangerousFiles) > 0) {
            $message = $this->generateZipSecurityReport($dangerousFiles);
            return [
                'safe'            => false,
                'message'         => $message,
                'dangerous_files' => $dangerousFiles
            ];
        }

        return [
            'safe'    => true,
            'message' => 'জিপ ফাইল নিরাপদ।'
        ];
    }

    /**
     * সন্দেহজনক কোডের লাইন নম্বর বের করুন
     */
    private function getLineNumber(string $content, string $pattern): int
    {
        $lines = explode("\n", $content);
        foreach ($lines as $lineNum => $line) {
            if (preg_match('/' . $pattern . '/i', $line)) {
                return $lineNum + 1;
            }
        }
        return 0;
    }

    /**
     * নিরাপত্তা রিপোর্ট তৈরি করুন
     */
    private function generateSecurityReport(array $dangerousFiles, array $suspiciousContent): string
    {
        $report = [];

        if (count($dangerousFiles) > 0) {
            $report[] = "⚠️ বিপজ্জনক ফাইল পাওয়া গেছে (" . count($dangerousFiles) . "টি):";
            foreach ($dangerousFiles as $file) {
                if ($file['type'] === 'blocked_extension') {
                    $report[] = "   - {$file['path']} (অনুমোদিত নয় .{$file['extension']})";
                } elseif ($file['type'] === 'unexpected_extension') {
                    $report[] = "   - {$file['path']} (অননুমোদিত এক্সটেনশন .{$file['extension']})";
                } elseif ($file['type'] === 'oversized_file') {
                    $report[] = "   - {$file['path']} (ফাইলের সাইজ {$file['size']} > {$file['limit']})";
                }
            }
        }

        if (count($suspiciousContent) > 0) {
            $report[] = "\n⚠️ সন্দেহজনক কোড পাওয়া গেছে (" . count($suspiciousContent) . "টি):";
            foreach ($suspiciousContent as $content) {
                $report[] = "   - {$content['path']} (লাইন {$content['line']}: {$content['pattern']})";
            }
        }

        $report[] = "\n❌ নিরাপত্তাজনিত কারণে থিমটি ইনস্টল করা যাবে না।";
        $report[] = "দয়া করে থিমটি পর্যালোচনা করে আবার জিপ করুন।";

        return implode("\n", $report);
    }

    /**
     * জিপ নিরাপত্তা রিপোর্ট তৈরি করুন
     */
    private function generateZipSecurityReport(array $dangerousFiles): string
    {
        $report = ["⚠️ জিপ ফাইলে নিচের বিপজ্জনক ফাইল/কন্টেন্ট পাওয়া গেছে:\n"];

        foreach ($dangerousFiles as $file) {
            if ($file['type'] === 'blocked_extension') {
                $report[] = "   - {$file['file']} (.{$file['extension']} ফাইল অনুমোদিত নয়)";
            } elseif ($file['type'] === 'unexpected_extension') {
                $report[] = "   - {$file['file']} (.{$file['extension']} এক্সটেনশন অনুমোদিত নয়)";
            } elseif ($file['type'] === 'no_extension') {
                $report[] = "   - {$file['file']} ({$file['message']})";
            } elseif ($file['type'] === 'path_traversal') {
                $report[] = "   - {$file['file']} ({$file['message']})";
            } elseif ($file['type'] === 'hidden_file') {
                $report[] = "   - {$file['file']} ({$file['message']})";
            } elseif ($file['type'] === 'php_file_not_allowed') {
                $report[] = "   - {$file['file']} ({$file['message']})";
            }
        }

        $report[] = "\n❌ নিরাপত্তাজনিত কারণে থিমটি ইনস্টল করা যাবে না।";

        return implode("\n", $report);
    }
    /**
     * বাইট ফরম্যাট করুন
     */
    private function formatBytes(int $bytes, int $precision = 2): string
    {
        $units  = ['B', 'KB', 'MB', 'GB'];
        $bytes  = max($bytes, 0);
        $pow    = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow    = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
