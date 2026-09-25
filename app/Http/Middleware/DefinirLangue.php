<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Langue du site public : ?lang=fr|en|ar (mémorisée en session), sinon la langue déjà choisie, sinon le français.
 * L'administration reste en français (middleware non appliqué à ses routes).
 */
class DefinirLangue
{
    public const LANGUES = [
        'fr' => 'Français',
        'en' => 'English',
        'ar' => 'العربية',
    ];

    public function handle(Request $request, Closure $next)
    {
        if (isset(self::LANGUES[$request->query('lang')])) {
            $request->session()->put('langue', $request->query('lang'));
        }

        $langue = $request->session()->get('langue', config('app.locale'));
        app()->setLocale(isset(self::LANGUES[$langue]) ? $langue : 'fr');
        \Carbon\Carbon::setLocale(app()->getLocale());

        return $next($request);
    }
}
