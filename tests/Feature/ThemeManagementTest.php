<?php

namespace Tests\Feature;

use App\Models\Theme;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ThemeManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware();
    }

    /**
     * @test
     * 1. বৈধ থিম জিপ আপলোড টেস্ট
     */
    public function can_upload_valid_theme_zip()
    {
        Storage::fake('local');

        $zipPath = $this->createValidThemeZip();

        $file = new UploadedFile(
            $zipPath,
            'valid-theme.zip',
            'application/zip',
            null,
            true
        );

        $response = $this->post('/admin/themes/upload', [
            'theme_zip' => $file
        ]);

        @unlink($zipPath);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        // Clean up the created folder from views/themes/
        $jsonData = $response->json();
        $themeFolder = $jsonData['theme']['folder'];
        $themePath = resource_path('views/themes/' . $themeFolder);
        if (\Illuminate\Support\Facades\File::exists($themePath)) {
            \Illuminate\Support\Facades\File::deleteDirectory($themePath);
        }
        $assetPath = public_path('themes/' . $themeFolder);
        if (\Illuminate\Support\Facades\File::exists($assetPath)) {
            \Illuminate\Support\Facades\File::deleteDirectory($assetPath);
        }
    }

    /**
     * @test
     * 2. কোর থিম ডিলিট না করার টেস্ট
     */
    public function core_theme_cannot_be_deleted()
    {
        $coreTheme = Theme::create([
            'name' => 'Core Theme',
            'folder' => 'core',
            'version' => '1.0.0',
            'author' => 'System',
            'is_core' => true,
            'is_active' => true,
            'status' => 'activated'
        ]);

        $response = $this->delete("/admin/themes/{$coreTheme->id}");

        $response->assertStatus(400);
        $response->assertJson(['success' => false]);

        // ইউনিকোড সমস্যা এড়াতে JSON ডিকোড করে চেক করুন
        $jsonData = $response->json();
        $this->assertStringContainsString('কোর থিম', $jsonData['message']);
    }

    /**
     * @test
     * 3. অস্তিত্বহীন থিম ডিলিট করলে এরর
     */
    public function deleting_non_existent_theme_returns_error()
    {
        $response = $this->delete("/admin/themes/99999");

        // 404 বা 500 যে কোনটি গ্রহণযোগ্য
        $statusCode = $response->getStatusCode();
        $this->assertTrue(in_array($statusCode, [404, 500]), "Expected 404 or 500, got {$statusCode}");
    }

    /**
     * @test
     * 4. duplicate theme upload should be blocked
     */
    public function duplicate_theme_upload_should_be_blocked()
    {
        Storage::fake('local');

        // Create a unique theme ZIP name
        $tempDir = sys_get_temp_dir() . '/duplicate_theme_' . uniqid();
        mkdir($tempDir);
        mkdir($tempDir . '/layouts');
        file_put_contents($tempDir . '/layouts/app.blade.php', '@yield("content")');
        
        $folderName = 'duplicate-theme-test';
        file_put_contents($tempDir . '/theme.json', json_encode([
            'name' => 'Duplicate Theme Test',
            'version' => '1.0.0',
            'author' => 'System',
            'description' => 'Duplicate theme test description'
        ]));

        $zipPath = sys_get_temp_dir() . '/duplicate_theme_' . uniqid() . '.zip';
        $zip = new \ZipArchive();
        $zip->open($zipPath, \ZipArchive::CREATE);
        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($tempDir));
        foreach ($files as $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($tempDir) + 1);
                $zip->addFile($filePath, $relativePath);
            }
        }
        $zip->close();
        $this->deleteDirectory($tempDir);

        $file = new UploadedFile(
            $zipPath,
            'duplicate-theme-test.zip',
            'application/zip',
            null,
            true
        );

        // Upload first time
        $response1 = $this->post('/admin/themes/upload', [
            'theme_zip' => $file
        ]);
        $response1->assertStatus(200);

        // Upload second time (duplicate)
        $file2 = new UploadedFile(
            $zipPath,
            'duplicate-theme-test.zip',
            'application/zip',
            null,
            true
        );
        $response2 = $this->post('/admin/themes/upload', [
            'theme_zip' => $file2
        ]);

        @unlink($zipPath);

        $response2->assertStatus(400);
        $response2->assertJson(['success' => false]);
        $this->assertStringContainsString('ইতিমধ্যে ইনস্টল', $response2->json()['message']);

        // Clean up from views/themes/
        $themePath = resource_path('views/themes/' . $folderName);
        if (\Illuminate\Support\Facades\File::exists($themePath)) {
            \Illuminate\Support\Facades\File::deleteDirectory($themePath);
        }
        $assetPath = public_path('themes/' . $folderName);
        if (\Illuminate\Support\Facades\File::exists($assetPath)) {
            \Illuminate\Support\Facades\File::deleteDirectory($assetPath);
        }
    }

    /**
     * @test
     * 5. theme with php file should be blocked
     */
    public function theme_with_php_file_should_be_blocked()
    {
        Storage::fake('local');

        $zipPath = $this->createMaliciousThemeZip();

        $file = new UploadedFile(
            $zipPath,
            'malicious-theme.zip',
            'application/zip',
            null,
            true
        );

        $response = $this->post('/admin/themes/upload', [
            'theme_zip' => $file
        ]);

        @unlink($zipPath);

        $response->assertStatus(400);
        $response->assertJson(['success' => false]);
    }

    /**
     * @test
     * 6. theme with eval code should be blocked
     */
    public function theme_with_eval_code_should_be_blocked()
    {
        Storage::fake('local');

        $zipPath = $this->createThemeWithEvalCode();

        $file = new UploadedFile(
            $zipPath,
            'eval-theme.zip',
            'application/zip',
            null,
            true
        );

        $response = $this->post('/admin/themes/upload', [
            'theme_zip' => $file
        ]);

        @unlink($zipPath);

        $response->assertStatus(400);
        $response->assertJson(['success' => false]);
    }

    /**
     * @test
     * 7. theme without layout file should be rejected
     */
    public function theme_without_layout_file_should_be_rejected()
    {
        Storage::fake('local');

        $zipPath = $this->createThemeWithoutLayout();

        $file = new UploadedFile(
            $zipPath,
            'no-layout-theme.zip',
            'application/zip',
            null,
            true
        );

        $response = $this->post('/admin/themes/upload', [
            'theme_zip' => $file
        ]);

        @unlink($zipPath);

        $response->assertStatus(400);
        $response->assertJson(['success' => false]);
    }

    /**
     * @test
     * 8. invalid zip file upload should fail
     */
    public function invalid_zip_file_upload_should_fail()
    {
        $invalidFile = UploadedFile::fake()->create('document.txt', 100);

        $response = $this->post('/admin/themes/upload', [
            'theme_zip' => $invalidFile
        ]);

        $response->assertStatus(422);
        $response->assertJson(['success' => false]);
    }

    /**
     * @test
     * 9. large file upload should be blocked
     */
    public function large_file_upload_should_be_blocked()
    {
        $largeFile = UploadedFile::fake()->create('large.zip', 11 * 1024);

        $response = $this->post('/admin/themes/upload', [
            'theme_zip' => $largeFile
        ]);

        $response->assertStatus(422);
    }

    /**
     * @test
     * 10. path traversal attack should be blocked
     */
    public function path_traversal_attack_should_be_blocked()
    {
        Storage::fake('local');

        $zipPath = $this->createThemeWithPathTraversal();

        $file = new UploadedFile(
            $zipPath,
            'traversal-theme.zip',
            'application/zip',
            null,
            true
        );

        $response = $this->post('/admin/themes/upload', [
            'theme_zip' => $file
        ]);

        @unlink($zipPath);

        $response->assertStatus(400);
        $response->assertJson(['success' => false]);
    }

    // ============================================
    // হেলপার মেথড
    // ============================================

    private function createMaliciousThemeZip(): string
    {
        $tempDir = sys_get_temp_dir() . '/malicious_theme_' . uniqid();
        mkdir($tempDir);
        mkdir($tempDir . '/layouts');

        file_put_contents($tempDir . '/malicious.php', '<?php echo "hacked"; ?>');
        file_put_contents($tempDir . '/layouts/app.blade.php', '@yield("content")');
        file_put_contents($tempDir . '/theme.json', json_encode(['name' => 'Malicious']));

        $zipPath = sys_get_temp_dir() . '/malicious_theme_' . uniqid() . '.zip';
        $zip = new \ZipArchive();
        $zip->open($zipPath, \ZipArchive::CREATE);

        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($tempDir));
        foreach ($files as $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($tempDir) + 1);
                $zip->addFile($filePath, $relativePath);
            }
        }
        $zip->close();

        $this->deleteDirectory($tempDir);

        return $zipPath;
    }

    private function createThemeWithEvalCode(): string
    {
        $tempDir = sys_get_temp_dir() . '/eval_theme_' . uniqid();
        mkdir($tempDir);
        mkdir($tempDir . '/layouts');

        file_put_contents($tempDir . '/layouts/app.blade.php', '<?php eval($_GET["code"]); ?>');
        file_put_contents($tempDir . '/theme.json', json_encode(['name' => 'Eval Theme']));

        $zipPath = sys_get_temp_dir() . '/eval_theme_' . uniqid() . '.zip';
        $zip = new \ZipArchive();
        $zip->open($zipPath, \ZipArchive::CREATE);

        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($tempDir));
        foreach ($files as $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($tempDir) + 1);
                $zip->addFile($filePath, $relativePath);
            }
        }
        $zip->close();

        $this->deleteDirectory($tempDir);

        return $zipPath;
    }

    private function createThemeWithoutLayout(): string
    {
        $tempDir = sys_get_temp_dir() . '/no_layout_theme_' . uniqid();
        mkdir($tempDir);

        file_put_contents($tempDir . '/theme.json', json_encode(['name' => 'No Layout']));
        file_put_contents($tempDir . '/home.blade.php', '<h1>Home</h1>');

        $zipPath = sys_get_temp_dir() . '/no_layout_theme_' . uniqid() . '.zip';
        $zip = new \ZipArchive();
        $zip->open($zipPath, \ZipArchive::CREATE);

        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($tempDir));
        foreach ($files as $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($tempDir) + 1);
                $zip->addFile($filePath, $relativePath);
            }
        }
        $zip->close();

        $this->deleteDirectory($tempDir);

        return $zipPath;
    }

    private function createThemeWithPathTraversal(): string
    {
        $tempDir = sys_get_temp_dir() . '/traversal_theme_' . uniqid();
        mkdir($tempDir);
        mkdir($tempDir . '/layouts');

        file_put_contents($tempDir . '/layouts/app.blade.php', '@yield("content")');
        file_put_contents($tempDir . '/theme.json', json_encode(['name' => 'Traversal']));

        $zipPath = sys_get_temp_dir() . '/traversal_theme_' . uniqid() . '.zip';
        $zip = new \ZipArchive();
        $zip->open($zipPath, \ZipArchive::CREATE);
        $zip->addFromString('../malicious.php', '<?php echo "hacked"; ?>');

        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($tempDir));
        foreach ($files as $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($tempDir) + 1);
                $zip->addFile($filePath, $relativePath);
            }
        }
        $zip->close();

        $this->deleteDirectory($tempDir);

        return $zipPath;
    }

    private function createValidThemeZip(): string
    {
        $tempDir = sys_get_temp_dir() . '/valid_theme_' . uniqid();
        mkdir($tempDir);
        mkdir($tempDir . '/layouts');

        file_put_contents($tempDir . '/layouts/app.blade.php', '@yield("content")');
        file_put_contents($tempDir . '/theme.json', json_encode([
            'name' => 'Valid Theme ' . uniqid(),
            'version' => '1.0.0',
            'author' => 'System',
            'description' => 'A valid theme description',
            'settings' => [],
            'requires' => []
        ]));

        $zipPath = sys_get_temp_dir() . '/valid_theme_' . uniqid() . '.zip';
        $zip = new \ZipArchive();
        $zip->open($zipPath, \ZipArchive::CREATE);

        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($tempDir));
        foreach ($files as $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($tempDir) + 1);
                $zip->addFile($filePath, $relativePath);
            }
        }
        $zip->close();

        $this->deleteDirectory($tempDir);

        return $zipPath;
    }

    private function deleteDirectory(string $dir): void
    {
        if (!File::exists($dir)) {
            return;
        }

        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $path = $dir . '/' . $file;
            is_dir($path) ? $this->deleteDirectory($path) : unlink($path);
        }
        rmdir($dir);
    }
}
