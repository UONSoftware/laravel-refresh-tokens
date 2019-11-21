<?php


namespace UonSoftware\RefreshTokens\Http\Middleware;


use Closure;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use UonSoftware\RefreshTokens\Contracts\RefreshTokenDecoder as Decoder;
use UonSoftware\RefreshTokens\Contracts\TokenSigner;
use UonSoftware\RefreshTokens\Contracts\UserJwtExpired;
use UonSoftware\RefreshTokens\RefreshToken;
use RuntimeException;
use Throwable;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Psr\Container\ContainerInterface;
use Illuminate\Contracts\Config\Repository as Config;
use UonSoftware\RefreshTokens\Exceptions\RefreshTokenExpired;
use UonSoftware\RefreshTokens\Contracts\RefreshTokenVerifier;
use UonSoftware\RefreshTokens\Exceptions\InvalidRefreshToken;
use UonSoftware\RefreshTokens\Contracts\RefreshTokenGenerator;
use UonSoftware\RefreshTokens\Exceptions\RefreshTokenNotFound;

class RefreshMiddleware
{
    
    /**
     * @var ContainerInterface
     */
    protected $container;
    
    /**
     * @var Config
     */
    protected $config;
    
    /**
     * @var RefreshTokenGenerator
     */
    protected $refreshTokenGenerator;
    
    /**
     * @var RefreshTokenVerifier
     */
    protected $refreshTokenVerifier;
    
    
    /**
     * @var Decoder
     */
    protected $decoder;
    
    public function __construct(
        ContainerInterface $container,
        Config $config,
        Decoder $decoder,
        RefreshTokenGenerator $refreshTokenGenerator,
        RefreshTokenVerifier $refreshTokenVerifier
    ) {
        $this->container = $container;
        $this->config = $config;
        $this->refreshTokenGenerator = $refreshTokenGenerator;
        $this->refreshTokenVerifier = $refreshTokenVerifier;
        $this->decoder = $decoder;
    }
    
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     *
     * @return mixed
     * @throws Exception
     */
    public function handle($request, Closure $next)
    {
        $data = [];
        
        $userJwtExpiredClass = $this->config->get('refresh_tokens.jwt_expired');
        $tokenSingerClass = $this->config->get('refresh_tokens.token_signer');
        $authenticateClass = $this->config->get('refresh_tokens.authenticate');
        
        if ($userJwtExpiredClass === null || $tokenSingerClass === null || $authenticateClass === null) {
            throw new RuntimeException('Jwt expired or token signer or authenticate interface is null');
        }
        
        /** @var UserJwtExpired $instance */
        $instance = $this->container->get($userJwtExpiredClass);
        
        /** @var TokenSigner $tokenSinger */
        $tokenSinger = $this->container->get($tokenSingerClass);
        
        if ($instance->hasTokenExpired()) {
            return $next($request);
        } else {
            $refreshToken = $request->header('X-Refresh-Token', null);
            
            try {
                $rf = $this->refreshTokenVerifier->verify($refreshToken);
                /** @var RefreshToken $rf */
                [1 => $refreshToken] = $this->refreshTokenGenerator->generateNewRefreshToken($rf);
                
                $user = $rf->user;
                /** @var string $jwt */
                $jwt = $tokenSinger->sign($user);
                
                $request->headers->replace([
                    'Authorization' => 'Bearer '.$jwt,
                ]);
                
                $resource = $this->config->get('refresh_tokens.resource');
                $data = [
                    'auth' => [
                        'token' => $jwt,
                        'refreshToken' => $refreshToken,
                        'user' => new $resource($user),
                    ],
                ];
            } catch (InvalidRefreshToken | RefreshTokenExpired $e) {
                return response()->json(['message' => 'Forbidden.'], 403);
            } catch (RefreshTokenNotFound $e) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            } catch (ModelNotFoundException $e) {
                return response()->json(['message' => 'Refresh token not found'], 404);
            } catch (Throwable $e) {
                return response()->json(['message' => 'Internal server error'], 500);
            }
            /** @var JsonResponse $response */
            $response = $next($request);
            return $response->setData(array_merge((array) $response->getData(true), $data));
        }
    }
}
