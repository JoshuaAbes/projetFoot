<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware de contrôle d'accès administrateur
 * 
 * Ce middleware vérifie si l'utilisateur est authentifié en tant qu'administrateur
 * en contrôlant la présence d'une clé spécifique dans la session.
 * Si l'utilisateur n'est pas authentifié, il est redirigé vers la page de connexion.
 */
class AdminAuthMiddleware
{
    /**
     * Gère la requête entrante et vérifie les droits d'administrateur
     * 
     * @param Request $request Requête HTTP entrante
     * @param Closure $next Fonction de rappel pour passer à l'étape suivante
     * @return Response Réponse HTTP (redirection ou requête normale)
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifie si la session contient la clé d'authentification admin
        if (!session()->has('admin_logged_in')) {
            // Redirection vers la page de connexion si non authentifié
            return redirect()->route('admin.login');
        }

        // Poursuit le traitement de la requête si authentifié
        return $next($request);
    }
}