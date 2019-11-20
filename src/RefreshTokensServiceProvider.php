<?php


namespace UonSoftware\RefreshTokens;


use UonSoftware\RefreshTokens\Service\RefreshTokenEncoder;
use UonSoftware\RefreshTokens\Contracts\{RefreshTokenDecoder as Decoder,
    RefreshTokenEncoder as Encoder,
    RefreshTokenGenerator as Generator,
    RefreshTokenVerifier as Verifier};
use UonSoftware\RefreshTokens\Service\RefreshTokenDecoder;
use Illuminate\Support\ServiceProvider;
use UonSoftware\RefreshTokens\Service\RefreshTokenVerifier;
use UonSoftware\RefreshTokens\Service\RefreshTokenGenerator;

/**
 * Class RefreshTokensServiceProvider
 * @package UonSoftware\RefreshTokens
 */
class RefreshTokensServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/refresh_tokens.php', 'refresh_tokens');

        // Services
        $this->app->singleton(Decoder::class, RefreshTokenDecoder::class);
        $this->app->singleton(Encoder::class, RefreshTokenEncoder::class);
        $this->app->singleton(Generator::class, RefreshTokenGenerator::class);
        $this->app->singleton(Verifier::class, RefreshTokenVerifier::class);
    }


    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/config/refresh_tokens.php' => config_path('refresh_tokens.php'),
        ]);

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }
}
