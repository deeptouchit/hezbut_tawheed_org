<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Theme;
use App\Services\ThemeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use ZipArchive;

class ThemeController extends Controller
{
    protected $themeService;

    public function __construct(ThemeService $themeService)
    {
        $this->themeService = $themeService;
    }

    public function index()
    {
        $themes      = $this->themeService->getAllAvailableThemes();
        $activeTheme = Theme::getActiveTheme();

        return view('admin.themes.index', compact('themes', 'activeTheme'));
    }

    public function activate($id)
    {
        try {
            $theme = Theme::findOrFail($id);

            if (!$this->themeService->isValidThemeFolder($theme->folder)) {
                return response()->json([
                    'success' => false,
                    'message' => 'থিম ফোল্ডারটি বৈধ নয়।'
                ], 400);
            }

            $theme->activate();
            $this->themeService->clearCache();

            return response()->json([
                'success' => true,
                'message' => "{$theme->name} থিম সফলভাবে একটিভেট করা হয়েছে।"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'থিম একটিভেট করতে সমস্যা হয়েছে: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deactivate($id)
    {
        try {
            $theme = Theme::findOrFail($id);

            if (!$theme->isDeactivatable()) {
                return response()->json([
                    'success' => false,
                    'message' => 'এই থিম ডিঅ্যাকটিভেট করা যাবে না।'
                ], 400);
            }

            $theme->deactivate();
            $this->themeService->clearCache();

            return response()->json([
                'success' => true,
                'message' => "{$theme->name} থিম ডিঅ্যাকটিভেট করা হয়েছে।"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'থিম ডিঅ্যাকটিভেট করতে সমস্যা হয়েছে।'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $theme = Theme::findOrFail($id);

            if (!$theme->isDeletable()) {
                return response()->json([
                    'success' => false,
                    'message' => 'কোর থিম ডিলিট করা যাবে না।'
                ], 400);
            }

            $themePath = resource_path('views/themes/' . $theme->folder);
            if (File::exists($themePath)) {
                File::deleteDirectory($themePath);
            }

            $assetPath = public_path('themes/' . $theme->folder);
            if (File::exists($assetPath)) {
                File::deleteDirectory($assetPath);
            }

            $theme->delete();
            $this->themeService->clearCache();

            return response()->json([
                'success' => true,
                'message' => "{$theme->name} থিম সফলভাবে ডিলিট করা হয়েছে।"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'থিম ডিলিট করতে সমস্যা হয়েছে।'
            ], 500);
        }
    }

    public function upload(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'theme_zip' => 'required|file|mimes:zip|max:10240',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            if (!class_exists('ZipArchive')) {
                return response()->json([
                    'success' => false,
                    'message' => 'ZipArchive এক্সটেনশন ইনস্টল নেই।'
                ], 500);
            }

            $file       = $request->file('theme_zip');
            $tempFolder = storage_path('app/temp/' . uniqid());

            if (!File::exists($tempFolder)) {
                File::makeDirectory($tempFolder, 0755, true);
            }

              // ============================================
              // নিরাপত্তা স্ক্যানিং - জিপ ফাইল চেক করুন (আগে)
              // ============================================
            $zipScan = $this->themeService->scanZipForSecurity($file->path());

            if (!$zipScan['safe']) {
                if (isset($tempFolder) && File::exists($tempFolder)) {
                    File::deleteDirectory($tempFolder);
                }
                return response()->json([
                    'success' => false,
                    'message' => $zipScan['message']
                ], 400);
            }

            $zip = new ZipArchive();
            if ($zip->open($file->path()) !== true) {
                return response()->json([
                    'success' => false,
                    'message' => 'জিপ ফাইল খোলা সম্ভব হয়নি।'
                ], 400);
            }

            $zip->extractTo($tempFolder);
            $zip->close();

              // থিম ফোল্ডার সনাক্ত করুন
            $directories = File::directories($tempFolder);
            $files       = File::files($tempFolder);

            if (count($directories) === 1 && count($files) === 0) {
                $extractedPath = $directories[0];
                $themeFolder   = basename($directories[0]);
            } else {
                $extractedPath = $tempFolder;
                $themeFolder   = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $themeFolder   = Str::slug($themeFolder);
            }

              // ============================================
              // নিরাপত্তা স্ক্যানিং - এক্সট্র্যাক্ট করা ফাইল চেক করুন
              // ============================================
            $securityScan = $this->themeService->scanThemeForSecurity($extractedPath);

            if (!$securityScan['safe']) {
                File::deleteDirectory($tempFolder);
                return response()->json([
                    'success' => false,
                    'message' => $securityScan['message']
                ], 400);
            }

              // ভ্যালিডেশন
            $hasLayoutFile = File::exists($extractedPath . '/layouts/app.blade.php');
            if (!$hasLayoutFile) {
                File::deleteDirectory($tempFolder);
                return response()->json([
                    'success' => false,
                    'message' => 'বৈধ থিম নয়। layouts/app.blade.php ফাইল প্রয়োজন।'
                ], 400);
            }

            $themeFolder = Str::slug($themeFolder);

            $existingTheme = Theme::where('folder', $themeFolder)->first();
            if ($existingTheme) {
                File::deleteDirectory($tempFolder);
                return response()->json([
                    'success' => false,
                    'message' => 'এই থিম ইতিমধ্যে ইনস্টল করা আছে।'
                ], 400);
            }

              // থিম কপি করুন
            $destinationPath = resource_path('views/themes/' . $themeFolder);
            if (File::exists($destinationPath)) {
                File::deleteDirectory($destinationPath);
            }
            File::copyDirectory($extractedPath, $destinationPath);

              // পাবলিক অ্যাসেট কপি করুন (শুধুমাত্র অনুমোদিত ফাইল - CSS, JS, Images)
            $assetSource      = $extractedPath . '/public';
            $assetDestination = public_path('themes/' . $themeFolder);
            if (File::exists($assetSource)) {
                if (File::exists($assetDestination)) {
                    File::deleteDirectory($assetDestination);
                }
                  // শুধুমাত্র নিরাপদ ফাইল কপি করুন (CSS, JS, Images)
                $this->copySafeAssets($assetSource, $assetDestination);
            }

              // theme.json পড়ুন
            $themeInfo = $this->themeService->getThemeInfoFromFolder($themeFolder);

              // ডাটাবেসে সংরক্ষণ
            $theme = Theme::create([
                'name'        => $themeInfo['name'],
                'folder'      => $themeFolder,
                'version'     => $themeInfo['version'],
                'author'      => $themeInfo['author'],
                'description' => $themeInfo['description'],
                'is_core'     => false,
                'is_active'   => false,
                'status'      => 'installed',
                'settings'    => $themeInfo['settings'] ?? [],
                'requires'    => $themeInfo['requires'] ?? [],
            ]);

            File::deleteDirectory($tempFolder);
            $this->themeService->clearCache();

            $successMessage = "{$theme->name} থিম সফলভাবে ইনস্টল করা হয়েছে।";
            if ($securityScan['safe'] && isset($securityScan['message'])) {
                $successMessage .= "\n" . $securityScan['message'];
            }

            return response()->json([
                'success' => true,
                'message' => $successMessage,
                'theme'   => $theme
            ]);
        } catch (\Exception $e) {
            if (isset($tempFolder) && File::exists($tempFolder)) {
                File::deleteDirectory($tempFolder);
            }

            return response()->json([
                'success' => false,
                'message' => 'থিম ইনস্টল করতে সমস্যা হয়েছে: ' . $e->getMessage()
            ], 500);
        }
    }

      /**
     * নিরাপদ অ্যাসেট ফাইল কপি করুন (শুধুমাত্র CSS, JS, Images)
     */
    private function copySafeAssets(string $source, string $destination): void
    {
        $allowedExtensions = ['css', 'js', 'jpg', 'jpeg', 'png', 'gif', 'svg', 'webp', 'ico', 'woff', 'woff2', 'ttf', 'eot'];

        if (!File::exists($destination)) {
            File::makeDirectory($destination, 0755, true);
        }

        $files = File::allFiles($source);

        foreach ($files as $file) {
            $extension    = $file->getExtension();
            $relativePath = $file->getRelativePathname();
            $targetPath   = $destination . '/' . $relativePath;

              // শুধুমাত্র অনুমোদিত এক্সটেনশন কপি করুন
            if (in_array(strtolower($extension), $allowedExtensions)) {
                $targetDir = dirname($targetPath);
                if (!File::exists($targetDir)) {
                    File::makeDirectory($targetDir, 0755, true);
                }
                File::copy($file->getPathname(), $targetPath);
            }
        }
    }

      /**
     * থিম লাইভ প্রিভিউ
     */
    public function preview($id)
    {
        try {
            $theme = Theme::findOrFail($id);

              // থিম ফোল্ডার বৈধ কিনা চেক করুন
            if (!$this->themeService->isValidThemeFolder($theme->folder)) {
                return redirect()->back()->with('error', 'থিম ফোল্ডারটি বৈধ নয়।');
            }

              // সেশনে প্রিভিউ থিম সংরক্ষণ করুন
            session(['preview_theme' => $theme->folder]);
            session(['preview_theme_id' => $theme->id]);
            session(['preview_mode' => true]);

              // হোম পেজে রিডাইরেক্ট করুন
            return redirect('/')->with('success', "‘{$theme->name}’ থিমের প্রিভিউ মোডে আছেন।");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'প্রিভিউ লোড করতে সমস্যা হয়েছে: ' . $e->getMessage());
        }
    }

      /**
     * প্রিভিউ মোড থেকে বের হওয়া
     */
    public function exitPreview()
    {
          // সেশন থেকে প্রিভিউ ডাটা মুছুন
        session()->forget('preview_theme');
        session()->forget('preview_theme_id');
        session()->forget('preview_mode');

        return redirect('/')->with('success', 'প্রিভিউ মোড থেকে বের হয়ে গেছেন।');
    }
}
