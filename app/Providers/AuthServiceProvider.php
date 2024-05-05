<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Permission;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        $installed = Storage::disk('public')->exists('installed');
        if ($installed === true) {
            // Dynamically register permissions with Laravel's Gate.
            try {
                Permission::with('roles')
                    ->get()
                    ->map(function ($permission) {
                        Gate::define($permission->name, function ($user) use ($permission) {
                            return $user->hasPermissionTo($permission->name);
                        });
                    });
            } catch (\Exception $e) {
                // This is to avoid errors during migrations where permissions table might not exist yet.
                report($e);
            }
        }
    }
}
