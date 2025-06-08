<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use App\Models\User; // Your User model in 'arsip'
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Illuminate\Http\Client\ConnectionException;

class RemoteLoginController extends Controller
{
    public function showLoginForm()
    {
        // Ensure you have this view: resources/views/auth/login.blade.php
        return view('auth.login');
    }

    public function login(Request $request)
    {
//        dd($request);
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $summaryApiLoginUrl = rtrim(env('SUMMARY_API_URL'), '/') . '/api/login';

        try {
            $response = Http::timeout(15)->post($summaryApiLoginUrl, [
                'email' => $request->email,
                'password' => $request->password,
                'device_name' => 'arsip_app_auth_token', // Helps identify token on summary side
            ]);

            if ($response->failed()) {
                $errors = $response->json('errors');
                $message = $response->json('message', __('auth.failed')); // Default message

                if ($response->status() === 422 && $errors && isset($errors['email'])) {
                    $message = $errors['email'][0];
                } elseif ($response->status() === 401) {
                    $message = 'Unauthorized access to authentication service.';
                }

                throw ValidationException::withMessages(['email' => [$message]]);
            }

            $data = $response->json();
            $summaryUserData = $data['user'];
            $summaryApiToken = $data['token'];

            // Find or create a local user in 'arsip'
            // This user record allows 'arsip' to have its own associations (roles, permissions specific to arsip)
            // The password for this local user is not used for login.
            $localUser = User::updateOrCreate(
                ['email' => $summaryUserData['email']],
                [
                    'name' => $summaryUserData['name'],
                    'summary_user_id' => $summaryUserData['id'], // Store the original user ID from 'summary'
                    'password' => Hash::make(Str::random(32)), // Set a random, unusable password
                    // Sync any other relevant fields from $summaryUserData
                    'email_verified_at' => now(), // Assuming if they logged in, email is verified
                ]
            );

            Auth::login($localUser, $request->boolean('remember'));

            // Store the token from 'summary' in session if 'arsip' needs to make further
            // authenticated API calls to 'summary' on behalf of the user.
            Session::put('summary_api_token', $summaryApiToken);
            Session::put('summary_user_data', $summaryUserData); // Optional: store user data for quick access

            return redirect()->intended(config('filament.path', env('FILAMENT_PATH', '/admin')));

        } catch (ConnectionException $e) {
            Log::error("Connection to summary API failed: " . $e->getMessage());
            throw ValidationException::withMessages([
                'email' => ['Could not connect to the authentication service. Please try again later.'],
            ]);
        } catch (ValidationException $e) {
            throw $e; // Re-throw to display on form
        } catch (\Exception $e) {
            Log::error("Arsip remote login error: " . $e->getMessage());
            throw ValidationException::withMessages([
                'email' => ['An unexpected error occurred during login.'],
            ]);
        }
    }

    public function logout(Request $request)
    {
        $summaryApiToken = Session::get('summary_api_token');
        $summaryApiLogoutUrl = rtrim(env('SUMMARY_API_URL'), '/') . '/api/logout';

        if ($summaryApiToken && env('SUMMARY_API_URL')) {
            try {
                // Attempt to invalidate the token on the 'summary' app's side
                Http::withToken($summaryApiToken)
                    ->timeout(5)
                    ->post($summaryApiLogoutUrl);
            } catch (\Exception $e) {
                // Log the error, but don't prevent logout from 'arsip'
                Log::warning("Failed to revoke token on summary service during logout: " . $e->getMessage());
            }
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        Session::forget(['summary_api_token', 'summary_user_data']);

        return redirect('/');
    }
}
