<?php

namespace App\Http\Middleware;

use App\Models\Terminal;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Resolve terminal from X-API-Key (or Bearer token that looks like a terminal key).
 * Also accepts keys listed in .env REGISTERED_API_KEYS as registered to the system.
 * Sets the terminal on the request when key is a terminal key; sets api_key_registered when key is from env.
 */
class ResolveTerminalByApiKey
{
    public function handle(Request $request, Closure $next): Response
    {
        $key = $this->extractApiKey($request);

        if (! $key || $key === '') {
            return $next($request);
        }

        // 1) Try per-terminal key (DB): prefix lookup + hash check
        if (strlen($key) >= 12 && str_starts_with($key, Terminal::API_KEY_PREFIX_LABEL)) {
            $terminal = Terminal::where('api_key_prefix', substr($key, 0, 12))->first();
            if ($terminal && $terminal->checkApiKey($key) && $terminal->is_active) {
                $terminal->update(['api_key_last_used_at' => now()]);
                $request->attributes->set('terminal', $terminal);

                return $next($request);
            }
        }

        // 2) Check if key is registered in .env (REGISTERED_API_KEYS)
        $registeredKeys = config('api.registered_api_keys', []);
        if (in_array($key, $registeredKeys, true)) {
            $request->attributes->set('api_key_registered', true);
        }

        return $next($request);
    }

    private function extractApiKey(Request $request): ?string
    {
        $key = $request->header('X-API-Key');
        if ($key !== null && $key !== '') {
            return trim($key);
        }
        $auth = $request->header('Authorization');
        if ($auth && str_starts_with($auth, 'Bearer ')) {
            return trim(substr($auth, 7));
        }

        return null;
    }
}
