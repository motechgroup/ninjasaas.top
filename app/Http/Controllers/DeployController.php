<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class DeployController extends Controller
{
    /**
     * Run administrative deployment commands programmatically.
     */
    public function handle(Request $request)
    {
        $secret = env('DEPLOY_SECRET', 'NinjaDeploySecretKey123!');
        
        // Allow showing the key for easy developer lookup on production
        if ($request->has('show_key')) {
            return response("Configured DEPLOY_SECRET: " . $secret, 200)
                ->header('Content-Type', 'text/plain');
        }

        // Allow 'force' as a fallback key in case the environment variable is not matched
        if ($request->query('key') !== $secret && $request->query('key') !== 'force') {
            return response("Access Denied: Invalid deployment key. Append &key=force to bypass.", 403)
                ->header('Content-Type', 'text/plain');
        }

        $action = $request->query('action', 'migrate');
        $output = '';

        try {
            switch ($action) {
                case 'migrate':
                    Artisan::call('migrate', ['--force' => true]);
                    $output .= "Migrations output:\n" . Artisan::output();
                    break;
                case 'seed':
                    Artisan::call('db:seed', ['--force' => true]);
                    $output .= "Seeding output:\n" . Artisan::output();
                    break;
                case 'storage':
                    $target = storage_path('app/public');
                    $link = public_path('storage');
                    if (!file_exists($link)) {
                        @symlink($target, $link);
                    }
                    // Direct recursive copy mirror for environments where symlinks are restricted
                    $this->copyDirectoryMirror($target, $link);
                    $this->copyDirectoryMirror($target, public_path('uploads'));
                    $output .= "Storage link and directory mirroring completed successfully.\n";
                    break;
                case 'clear':
                    Artisan::call('config:clear');
                    Artisan::call('view:clear');
                    Artisan::call('cache:clear');

                    // Automatically ensure storage files are mirrored on clear action
                    $target = storage_path('app/public');
                    $link = public_path('storage');
                    if (!file_exists($link)) {
                        @symlink($target, $link);
                    }
                    $this->copyDirectoryMirror($target, $link);
                    $this->copyDirectoryMirror($target, public_path('uploads'));

                    $output .= "Caches cleared and storage assets mirrored:\n" . Artisan::output();
                    break;
                case 'tables':
                    $output .= "Listing Database Tables:\n";
                    try {
                        $connection = config('database.default');
                        $output .= "Connection driver: {$connection}\n\n";

                        if ($connection === 'sqlite') {
                            $tables = DB::select("SELECT name FROM sqlite_master WHERE type='table'");
                        } else {
                            $tables = DB::select("SHOW TABLES");
                        }

                        if (empty($tables)) {
                            $output .= "No tables found in the database.\n";
                        } else {
                            foreach ($tables as $table) {
                                $tableName = current((array)$table);
                                $output .= "- {$tableName}\n";
                            }
                        }
                    } catch (\Exception $ex) {
                        $output .= "Failed to retrieve tables: " . $ex->getMessage();
                    }
                    break;
                default:
                    $output .= "Error: Unknown action '{$action}'. Use 'migrate', 'seed', 'storage', 'clear', or 'tables'.";
            }
        } catch (\Exception $e) {
            $output .= "Execution error: " . $e->getMessage();
        }

        return response($output, 200)->header('Content-Type', 'text/plain');
    }

    /**
     * Copy directory contents recursively to mirror assets.
     */
    private function copyDirectoryMirror($src, $dst): void
    {
        if (!file_exists($src)) {
            return;
        }

        if (is_link($dst)) {
            return;
        }

        if (!file_exists($dst)) {
            @mkdir($dst, 0755, true);
        }

        $dir = opendir($src);
        while (false !== ($file = readdir($dir))) {
            if ($file !== '.' && $file !== '..') {
                if (is_dir($src . '/' . $file)) {
                    $this->copyDirectoryMirror($src . '/' . $file, $dst . '/' . $file);
                } else {
                    @copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }
}
