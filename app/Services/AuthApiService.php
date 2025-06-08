<?php

namespace App\Services;

class AuthApiService
{
    /**
     * Attempts to authenticate a user via the summary API.
     *
     * @param array $credentials Typically ['email' => 'user@example.com', 'password' => 'password']
     * @return array|false Returns user data from API on success, false on failure.
     */
    public function attemptLogin(array $credentials): array|false
    {
        $baseUrl = rtrim(env('SUMMARY_API_URL'), '/');
        $loginEndpoint = env('SUMMARY_API_LOGIN_ENDPOINT', '/login');
        $apiUrl = $baseUrl . $loginEndpoint;

        try {
            $response = Http::timeout(10)->post($apiUrl, [
                'email' => $credentials['email'],
                'password' => $credentials['password'],
            ]);

            if ($response->successful()) {
                $responseData = $response->json();
                // Adjust this based on your API response. We assume it returns a 'user' object on success.
                if (isset($responseData['user'])) {
                    return $responseData['user'];
                }
                Log::warning('Summary API login successful but user data not found in response.', ['response' => $responseData]);
                return false;
            }

            return false;
        } catch (\Exception $e) {
            Log::error('Error during Summary API login attempt.', [
                'message' => $e->getMessage(),
                'url' => $apiUrl,
            ]);
            return false;
        }
    }
}
