<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class InstallController extends Controller
{
    public function index()
    {
        return view('install.index');
    }

    public function step1()
    {
        $data['APP_KEY'] = config('app.key');
        return view('install.step1', compact('data'));
    }

    public function step2()
    {
        return view('install.database_config');
    }

    public function step3()
    {
        // You might perform any final checks or setup tasks here

        return view('install.step3');
    }

    public function setup(Request $request)
    {
        $data = $request->validate([
            'app_name' => 'required',
            'app_env' => 'required',
            'app_debug' => 'required',
            'app_key' => 'required',
        ]);

        $data['app_url'] = $request->getSchemeAndHttpHost();

        $result = $this->changeEnv($data);

        if ($result) {
            return redirect()->route('install.step2')->with('success', 'App settings updated successfully.');
        }

        return redirect()->back()->with('error', 'Unable to update environment settings.');
    }

    public function setupDatabase(Request $request)
    {
        // Validate the form data
        $data = $request->validate([
            'db_host' => 'required',
            'db_database' => 'required',
            'db_username' => 'required',
            'db_password' => 'sometimes',
        ]);

        // Attempt to set database configuration dynamically
        config([
            'database.connections.temp' => [
                'driver' => 'mysql',
                'host' => $data['db_host'],
                'database' => $data['db_database'],
                'username' => $data['db_username'],
                'password' => $data['db_password'] ?? null,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'strict' => true,
                'engine' => null,
            ],
        ]);

        try {
            // Attempt to connect to the database
            DB::connection('temp')->getPdo();

            ini_set('max_execution_time', 300); // 300 seconds = 5 minutes

            // Run the migrations and seed the database
            Artisan::call('migrate:fresh', ['--force' => true, '--seed' => true]);
        } catch (\Exception $e) {
            // If connection fails, redirect back with error message
            return redirect()->back()->with('error', 'Unable to connect to the database. Please check your credentials.');
        }

        $result = $this->changeEnv($data);

        if ($result) {
            return redirect()->route('install.step3')->with('success', 'Environment settings updated successfully.');
        }

        return redirect()->back()->with('error', 'Unable to update environment settings.');
    }

    public function finalSetup(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);


        // Create the user in the database
        $user = new User([
            'name' => 'admin',
            'username' => $request->input('username'),
            'password' => Hash::make($request->input('password')),
            'role_id' => 1
        ]);

        $user->save();

        if ($user) {
            Storage::disk('public')->put('installed', 'Contents');
            Artisan::call('storage:link');
        }

        // Redirect to the desired route after successful setup
        return redirect('home')->with('success', 'Admin account created successfully. Please login to continue.');
    }

    public function getAppKey()
    {
        Artisan::call('key:generate', ['--show' => true]);
        $output = Artisan::output();
        $output = substr($output, 0, -2);
        return $output;
    }

    public function changeEnv($data = [])
    {
        if (count($data) > 0) {
            // Read .env-file
            $env = file_get_contents(base_path() . '/.env');

            // Split string on every " " and write into array
            $env = preg_split('/(\r\n|\n|\r)/', $env);

            // Loop through given data
            foreach ((array) $data as $key => $value) {
                $key = strtoupper($key); // Ensure the key is uppercase

                // Check if the value contains spaces and wrap it in quotes
                if (strpos($value, ' ') !== false) {
                    $value = '"' . $value . '"';
                }

                // Loop through .env-data
                foreach ($env as $env_key => $env_value) {
                    // Turn the value into an array and stop after the first split
                    // So it's not possible to split e.g. the App-Key by accident
                    $entry = explode('=', $env_value, 2);

                    // Check, if new key fits the actual .env-key
                    if ($entry[0] == $key) {
                        // If yes, overwrite it with the new one
                        if ($value !== null) {
                            $env[$env_key] = $key . '=' . $value;
                        }
                    } else {
                        // If not, keep the old one
                        $env[$env_key] = $env_value;
                    }
                }
            }

            // Turn the array back to an String
            $env = implode("\n", $env);

            // And overwrite the .env with the new data
            file_put_contents(base_path() . '/.env', $env);

            return true;
        } else {
            return false;
        }
    }
}
