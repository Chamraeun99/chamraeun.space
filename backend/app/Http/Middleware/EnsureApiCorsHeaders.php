<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Laravel's HandleCors does not decorate responses built from uncaught throwables mid-stack.
 * Browsers report that as “CORS”; this middleware runs outermost so API routes still emit ACAO when errors occur.
 */
class EnsureApiCorsHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $response = $next($request);
        } catch (\Throwable $e) {
            $rendered = app(ExceptionHandler::class)->render($request, $e);
            $response = $rendered instanceof Response
                ? $rendered
                : new Response((string) $rendered, 500, ['Content-Type' => 'text/html; charset=UTF-8']);

            return $this->decorateCors($request, $response);
        }

        return $this->decorateCors($request, $response);
    }

    protected function decorateCors(Request $request, Response $response): Response
    {
        foreach (config('cors.paths', []) as $pattern) {
            if ($request->is($pattern)) {
                return $this->maybeSetAllowedOrigin($request, $response);
            }
        }

        return $response;
    }

    protected function maybeSetAllowedOrigin(Request $request, Response $response): Response
    {
        if ($response->headers->has('Access-Control-Allow-Origin')) {
            return $response;
        }

        $origin = $request->headers->get('Origin');
        if ($origin === null || $origin === '') {
            return $response;
        }

        if (! $this->isOriginAllowed($origin)) {
            return $response;
        }

        $response->headers->set('Access-Control-Allow-Origin', $origin);

        if (config('cors.supports_credentials')) {
            $response->headers->set('Access-Control-Allow-Credentials', 'true');
            $vary = (string) $response->headers->get('Vary', '');
            if ($vary === '' || ! preg_match('/\bOrigin\b/i', $vary)) {
                $response->headers->set('Vary', trim($vary . ', Origin', ', '));
            }
        }

        return $response;
    }

    protected function isOriginAllowed(string $origin): bool
    {
        foreach (config('cors.allowed_origins', []) as $allowed) {
            if ((string) $allowed !== '' && strcasecmp((string) $allowed, $origin) === 0) {
                return true;
            }
        }

        foreach (config('cors.allowed_origins_patterns', []) as $pattern) {
            if ($pattern !== '' && @preg_match((string) $pattern, $origin)) {
                return true;
            }
        }

        return false;
    }
}
