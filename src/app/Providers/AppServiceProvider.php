<?php

namespace App\Providers;

use Filament\Pages\Page;
use Filament\Facades\Filament;
use App\Policies\ActivityPolicy;
use Illuminate\Support\Facades\Gate;
use Filament\Actions\MountableAction;
use Filament\Support\Enums\Alignment;
use Illuminate\Support\ServiceProvider;
use Spatie\Activitylog\Models\Activity;
use Filament\Notifications\Notification;
use Filament\Support\Enums\VerticalAlignment;
use Illuminate\Validation\ValidationException;
use Filament\Notifications\Livewire\Notifications;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Activity::class, ActivityPolicy::class);
        Page::formActionsAlignment(Alignment::Right);
        Notifications::alignment(Alignment::End);
        Notifications::verticalAlignment(VerticalAlignment::End);
        Page::$reportValidationErrorUsing = function (ValidationException $exception) {
            Notification::make()
                ->title($exception->getMessage())
                ->danger()
                ->send();
        };
        MountableAction::configureUsing(function (MountableAction $action) {
            $action->modalFooterActionsAlignment(Alignment::Right);
        });

        // Pastikan ini hanya dijalankan saat bukan di CLI
        if ($this->app->runningInConsole()) {
            return;
        }

        // Tambahkan favicon ke panel admin Filament
        Filament::serving(function () {
            Filament::registerRenderHook(
                'head.start',
                fn () => '<link rel="icon" href="/assets/images/logos/logo.png" type="image/png">'
            );
        });
    }
}
