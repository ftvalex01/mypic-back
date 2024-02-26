<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CorsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Permitir solicitudes desde un origen específico
        $frontendUrl = env('FRONTEND_URL', 'http://localhost:3000');

        $headers = [
            'Access-Control-Allow-Origin' => $frontendUrl,
            'Access-Control-Allow-Origin' => 'https://lucasreact.informaticamajada.es',
            'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization, x-xsrf-token', // Agregar x-xsrf-token
            'Access-Control-Allow-Credentials' => 'true', // Permitir credenciales
        ];
        // Si la solicitud es de tipo OPTIONS, simplemente retornamos una respuesta exitosa con los encabezados CORS.
        if ($request->isMethod('OPTIONS')) {
            return response()->json('OK', 200, $headers);
        }
        // Continuar con la solicitud normalmente.
        $response = $next($request);
        // Agregar los encabezados CORS a la respuesta.
        foreach ($headers as $key => $value) {
            $response->header($key, $value);
        }
        return $response;
    }
}
