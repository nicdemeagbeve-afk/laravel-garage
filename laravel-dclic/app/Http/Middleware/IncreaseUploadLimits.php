<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IncreaseUploadLimits
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Augmenter les limites pour les uploads
        ini_set('upload_max_filesize', '7M');
        ini_set('post_max_size', '10M');
        ini_set('memory_limit', '256M');
        
        return $next($request);
    }
}
