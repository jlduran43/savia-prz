<?php

namespace App\Services;

use App\Models\MicrosoftToken;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MicrosoftAuthService
{
    /**
     * Devuelve un access token válido.
     *
     * Si el actual está vencido o próximo a vencer,
     * lo renueva automáticamente.
     */
    public function getValidAccessToken(): ?string
    {
        $token = MicrosoftToken::where(
            'provider',
            'microsoft'
        )->first();

        if (!$token) {
            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | Token todavía vigente
        |--------------------------------------------------------------------------
        |
        | Dejamos un margen de 1 minuto para evitar utilizar
        | un token justo cuando está expirando.
        |
        */

        if (
            $token->access_token &&
            $token->expires_at &&
            now()->addMinute()->lt($token->expires_at)
        ) {
            return $token->access_token;
        }

        /*
        |--------------------------------------------------------------------------
        | Token vencido
        |--------------------------------------------------------------------------
        */

        return $this->refreshAccessToken();
    }


    /**
     * Fuerza la renovación mediante refresh_token.
     */
    public function refreshAccessToken(): ?string
    {
        $token = MicrosoftToken::where(
            'provider',
            'microsoft'
        )->first();

        if (!$token || !$token->refresh_token) {
            return null;
        }

        $response = Http::asForm()->post(
            'https://login.microsoftonline.com/common/oauth2/v2.0/token',
            [
                'client_id' =>
                config('services.microsoft.client_id'),

                'client_secret' =>
                config('services.microsoft.client_secret'),

                'refresh_token' =>
                $token->refresh_token,

                'grant_type' =>
                'refresh_token',

                'scope' => implode(' ', [
                    'User.Read',
                    'Files.ReadWrite',
                    'offline_access',
                ]),
            ]
        );

        if ($response->failed()) {

            Log::error(
                'Error renovando token de Microsoft',
                [
                    'status' => $response->status(),
                    'response' => $response->json(),
                ]
            );

            return null;
        }

        $tokens = $response->json();

        /*
        |--------------------------------------------------------------------------
        | Actualizamos tokens
        |--------------------------------------------------------------------------
        |
        | Microsoft puede devolver un refresh_token nuevo.
        | Si lo hace, reemplazamos el anterior.
        |
        */

        $token->update([
            'access_token' =>
            $tokens['access_token'],

            'refresh_token' =>
            $tokens['refresh_token']
                ?? $token->refresh_token,

            'expires_at' =>
            now()->addSeconds(
                $tokens['expires_in'] ?? 3600
            ),
        ]);

        return $tokens['access_token'];
    }
}