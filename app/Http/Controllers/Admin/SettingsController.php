<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = [
            'site_name' => config('app.name'),
            'site_description' => 'NearU - Find your perfect dorm near campus',
            'contact_email' => config('mail.from.address', 'admin@nearu.com'),
            'max_listings_per_owner' => 10,
            'require_verification' => true,
            'auto_approve_listings' => false,
            'maintenance_mode' => false,
            'app_url' => config('app.url'),
            'mail_driver' => config('mail.default'),
            'mail_host' => config('mail.mailers.smtp.host'),
            'mail_port' => config('mail.mailers.smtp.port'),
            'mail_username' => config('mail.mailers.smtp.username'),
            'mail_encryption' => config('mail.mailers.smtp.encryption'),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string|max:500',
            'contact_email' => 'required|email',
            'max_listings_per_owner' => 'required|integer|min:1|max:100',
            'require_verification' => 'boolean',
            'auto_approve_listings' => 'boolean',
            'maintenance_mode' => 'boolean',
            'app_url' => 'required|url',
            'mail_driver' => 'required|string',
            'mail_host' => 'required|string',
            'mail_port' => 'required|integer',
            'mail_username' => 'nullable|string',
            'mail_password' => 'nullable|string',
            'mail_encryption' => 'nullable|string|in:tls,ssl',
        ]);

        // Update .env file
        $envPath = base_path('.env');
        $envContent = file_get_contents($envPath);

        $envUpdates = [
            'APP_NAME' => $request->site_name,
            'APP_URL' => $request->app_url,
            'MAIL_MAILER' => $request->mail_driver,
            'MAIL_HOST' => $request->mail_host,
            'MAIL_PORT' => $request->mail_port,
            'MAIL_USERNAME' => $request->mail_username,
            'MAIL_ENCRYPTION' => $request->mail_encryption,
            'MAIL_FROM_ADDRESS' => $request->contact_email,
        ];

        if ($request->filled('mail_password')) {
            $envUpdates['MAIL_PASSWORD'] = $request->mail_password;
        }

        foreach ($envUpdates as $key => $value) {
            $pattern = "/^{$key}=.*/m";
            $replacement = "{$key}={$value}";
            $envContent = preg_replace($pattern, $replacement, $envContent);
        }

        file_put_contents($envPath, $envContent);

        return back()->with('success', 'Settings updated successfully. Please restart the application for changes to take effect.');
    }

    public function storage()
    {
        // Calculate only application storage usage
        $profilePhotosSize = $this->getDirectorySize('profile_photos');
        $dormPhotosSize = $this->getDirectorySize('dorms');
        $verificationDocsSize = $this->getDirectorySize('verifications');
        
        $storageInfo = [
            'total_space' => $profilePhotosSize + $dormPhotosSize + $verificationDocsSize,
            'used_space' => $profilePhotosSize + $dormPhotosSize + $verificationDocsSize,
            'free_space' => 0, // Not applicable for app storage
        ];

        $storageInfo['usage_percentage'] = $storageInfo['total_space'] > 0 
            ? ($storageInfo['used_space'] / $storageInfo['total_space']) * 100 
            : 0;

        // Get file counts and sizes in storage directories
        $profilePhotos = Storage::disk('public')->allFiles('profile_photos');
        $dormPhotos = Storage::disk('public')->allFiles('dorms');
        $verificationDocs = Storage::disk('public')->allFiles('verifications');

        // Add keys expected by view
        $storageInfo['total_size'] = $this->formatBytes($storageInfo['used_space']);
        $storageInfo['files_count'] = count($profilePhotos) + count($dormPhotos) + count($verificationDocs);
        $storageInfo['dorm_images_size'] = $this->formatBytes($this->getDirectorySize('dorms'));
        $storageInfo['profile_photos_size'] = $this->formatBytes($this->getDirectorySize('profile_photos'));
        $storageInfo['verification_docs_size'] = $this->formatBytes($this->getDirectorySize('verifications'));

        // Keep original keys for compatibility
        $storageInfo['profile_photos'] = count($profilePhotos);
        $storageInfo['dorm_photos'] = count($dormPhotos);
        $storageInfo['verification_docs'] = count($verificationDocs);

        return view('admin.settings.storage', compact('storageInfo'));
    }

    private function getDirectorySize($directory)
    {
        try {
            $size = 0;
            $files = Storage::disk('public')->allFiles($directory);
            
            foreach ($files as $file) {
                try {
                    $fileSize = Storage::disk('public')->size($file);
                    $size += $fileSize;
                } catch (\Exception $e) {
                    // Skip files that can't be sized
                    continue;
                }
            }
            
            return $size;
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function clearCache()
    {
        \Artisan::call('cache:clear');
        \Artisan::call('config:clear');
        \Artisan::call('route:clear');
        \Artisan::call('view:clear');

        return back()->with('success', 'Application cache cleared successfully.');
    }

    public function backup()
    {
        try {
            \Artisan::call('backup:run');
            return back()->with('success', 'Backup completed successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Backup failed: ' . $e->getMessage());
        }
    }

    public function testEmail(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email',
        ]);

        try {
            \Mail::raw(
                'This is a test email from NearU admin panel. If you receive this, your email configuration is working correctly.',
                function ($message) use ($request) {
                    $message->to($request->test_email)
                            ->subject('NearU Email Configuration Test')
                            ->from(config('mail.from.address'), config('mail.from.name'));
                }
            );

            return back()->with('success', 'Test email sent successfully to ' . $request->test_email);
        } catch (\Exception $e) {
            return back()->with('error', 'Email test failed: ' . $e->getMessage());
        }
    }

    public function listBackups()
    {
        $backupPath = storage_path('app/backups');
        
        if (!is_dir($backupPath)) {
            return response()->json(['backups' => []]);
        }

        $backups = collect(scandir($backupPath))
            ->filter(function ($file) {
                return !in_array($file, ['.', '..']);
            })
            ->map(function ($file) use ($backupPath) {
                $filePath = $backupPath . '/' . $file;
                return [
                    'name' => $file,
                    'size' => filesize($filePath),
                    'modified' => filemtime($filePath),
                    'size_formatted' => $this->formatBytes(filesize($filePath)),
                    'date_formatted' => date('Y-m-d H:i:s', filemtime($filePath))
                ];
            })
            ->sortByDesc('modified')
            ->values();

        return response()->json(['backups' => $backups]);
    }

    public function downloadBackup($filename)
    {
        $backupPath = storage_path('app/backups/' . $filename);
        
        if (!file_exists($backupPath)) {
            abort(404, 'Backup file not found');
        }

        return response()->download($backupPath);
    }

    public function deleteBackup($filename)
    {
        $backupPath = storage_path('app/backups/' . $filename);
        
        if (!file_exists($backupPath)) {
            return back()->with('error', 'Backup file not found');
        }

        if (unlink($backupPath)) {
            return back()->with('success', 'Backup deleted successfully');
        }

        return back()->with('error', 'Failed to delete backup');
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }

    public function systemInfo()
    {
        $info = [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'database_connection' => config('database.default'),
            'cache_driver' => config('cache.default'),
            'session_driver' => config('session.driver'),
            'file_uploads' => ini_get('file_uploads'),
            'max_upload_size' => ini_get('upload_max_filesize'),
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time'),
        ];

        return view('admin.settings.systemInfo', compact('info'));
    }
}
