<?php

namespace App\Providers;

use App\Models\User;
use App\Services\Auth\AuthAppService;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        $httpClient = new Client();
        $authAppBaseUrl = config('services.auth_app.base_url');
        $authAppService = new AuthAppService($httpClient, $authAppBaseUrl);
        $this->app->instance(AuthAppService::class, $authAppService);

        Auth::viaRequest('auth-app', function (Request $request) {
            /** @var AuthAppService $authAppService */
            $authAppService = resolve(AuthAppService::class);
            $tokenHeader = $request->header('Authorization');
            $tokenValue = preg_replace('/Bearer\s+/', '', $tokenHeader);
            return $authAppService->getUserByAccessToken($tokenValue);
        });
    }
}
