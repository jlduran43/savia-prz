<?php

namespace App\Filament\Pages\Auth;

use App\Notifications\ResetPasswordNotification;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Facades\Filament;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Auth\Events\PasswordResetLinkSent;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log;
use LogicException;
use SensitiveParameter;

class RequestPasswordReset extends \Filament\Auth\Pages\PasswordReset\RequestPasswordReset
{
    public function request(): void
    {
        try {
            $this->rateLimit(2);
        } catch (TooManyRequestsException $exception) {
            $this->getRateLimitedNotification($exception)?->send();

            return;
        }

        $data = $this->form->getState();

        $status = Password::broker(
            Filament::getAuthPasswordBroker()
        )->sendResetLink(
            $this->getCredentialsFromFormData($data),

            function (
                CanResetPassword $user,
                #[SensitiveParameter] string $token
            ): void {

                if (
                    ($user instanceof FilamentUser) &&
                    (! $user->canAccessPanel(
                        Filament::getCurrentOrDefaultPanel()
                    ))
                ) {
                    return;
                }

                if (! method_exists($user, 'notify')) {
                    $userClass = $user::class;

                    throw new LogicException(
                        "Model [{$userClass}] does not have a [notify()] method."
                    );
                }

                $url = Filament::getResetPasswordUrl(
                    $token,
                    $user
                );

                Log::info('RESET CALLBACK EJECUTADO', [
                    'email' => $user->getEmailForPasswordReset(),
                    'url' => $url,
                ]);

                $user->notify(
                    new ResetPasswordNotification($url)
                );

                if (class_exists(PasswordResetLinkSent::class)) {
                    event(new PasswordResetLinkSent($user));
                }
            }
        );

        Log::info('ESTADO PASSWORD RESET', [
            'status' => $status,
        ]);

        if ($status !== Password::RESET_LINK_SENT) {
            $this->getFailureNotification($status)?->send();

            return;
        }

        $this->getSentNotification($status)?->send();

        $this->form->fill();
    }
}