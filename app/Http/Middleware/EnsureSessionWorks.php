<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class EnsureSessionWorks
{
    /**
     * Handle an incoming request for better session handling on Render
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Force start session if not started
        if (!$request->hasSession()) {
            $request->setSession(app('session.store'));
        }
        
        // Ensure session is properly started
        if (!Session::isStarted()) {
            Session::start();
        }

        $response = $next($request);

        // For AJAX requests, include session flash data in response headers
        if ($request->ajax() || $request->wantsJson()) {
            $flashData = [];
            
            if (Session::has('success')) {
                $flashData['success'] = Session::get('success');
            }
            
            if (Session::has('error')) {
                $flashData['error'] = Session::get('error');
            }
            
            if (Session::has('info')) {
                $flashData['info'] = Session::get('info');
            }
            
            if (!empty($flashData)) {
                $response->headers->set('X-Flash-Messages', json_encode($flashData));
            }
        }

        return $response;
    }
}