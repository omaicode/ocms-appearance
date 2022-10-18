<?php

namespace Modules\Appearance\Http\Middleware;

use Closure;
use Modules\Appearance\Facades\ThemesManager;
use Illuminate\Http\Request;

class ThemeLoader
{
    /**
     * Handle an incoming request.
     *
     * @param string $theme
     * @param string $layout
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $theme = null)
    {
        // Do not load theme if API request
        if ($request->expectsJson()) {
            return $next($request);
        }

        if (!empty($theme)) {
            ThemesManager::set($theme);
        } else {
            if ($theme = config('appearance.fallbackTheme')) {
                ThemesManager::set($theme);
            }
        }

        return $next($request);
    }
}
