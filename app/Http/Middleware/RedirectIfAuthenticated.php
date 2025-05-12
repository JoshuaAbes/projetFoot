<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware de redirection pour utilisateurs déjà authentifiés
 * 
 * Ce middleware vérifie si un utilisateur est déjà connecté et le redirige
 * vers le tableau de bord administrateur s'il tente d'accéder aux pages
 * d'authentification (login, register).
 */
class RedirectIfAuthenticated
{
    /**
     * Gère la requête entrante et vérifie si l'utilisateur est déjà authentifié
     *
     * @param  Request $request  Requête HTTP entrante
     * @param  Closure $next     Fonction de rappel pour passer à l'étape suivante
     * @param  string  ...$guards  Gardes d'authentification à vérifier (optionnel)
     * @return Response Réponse HTTP (redirection ou requête normale)
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        // Utilise le garde par défaut si aucun n'est spécifié
        $guards = empty($guards) ? [null] : $guards;

        // Vérifie chaque garde d'authentification
        foreach ($guards as $guard) {
            // Si l'utilisateur est déjà connecté, redirection vers le tableau de bord
            if (Auth::guard($guard)->check()) {
                return redirect('/admin/dashboard');
            }
        }

        // Poursuit le traitement de la requête si non authentifié
        return $next($request);
    }
}