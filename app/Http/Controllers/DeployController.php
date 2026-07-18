<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class DeployController extends Controller
{
    /**
     * Run administrative deployment commands programmatically.
     */
    public function handle(Request $request)
    {
        $secret = env('DEPLOY_SECRET', 'NinjaDeploySecretKey123!');
        
        if ($request->query('key') !== $secret) {
            return response("Access Denied: Invalid deployment key.", 403)
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
                    if (file_exists($link)) {
                        $output .= "Storage link/directory already exists at '{$link}'.\n";
                    } else {
                        if (symlink($target, $link)) {
                            $output .= "Storage symlink created successfully via native PHP.\n";
                        } else {
                            $output .= "Failed to create storage symlink.\n";
                        }
                    }
                    break;
                case 'clear':
                    Artisan::call('config:clear');
                    Artisan::call('view:clear');
                    Artisan::call('cache:clear');
                    $output .= "Caches cleared output:\n" . Artisan::output();
                    break;
                default:
                    $output .= "Error: Unknown action '{$action}'. Use 'migrate', 'seed', 'storage', or 'clear'.";
            }
        } catch (\Exception $e) {
            $output .= "Execution error: " . $e->getMessage();
        }

        return response($output, 200)->header('Content-Type', 'text/plain');
    }
}
