<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NodeTypeMiddleware
{
    /**
     * Restrict route to a specific node type (cloud | local).
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $requiredNode): Response
    {
        $nodeType = config('app.node_type', env('NODE_TYPE', 'local'));

        if ($nodeType !== $requiredNode) {
            return response()->json([
                'status' => false,
                'message' => "This endpoint is only available on the {$requiredNode} node.",
            ], 403);
        }

        return $next($request);
    }
}
