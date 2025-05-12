<?php

namespace App\Http\Controllers;

use App\Models\Story;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Contrôleur gérant les fonctionnalités d'administration
 * 
 * Ce contrôleur gère l'authentification des administrateurs
 * et l'accès au tableau de bord administrateur
 */
class AdminController extends Controller
{
    /**
     * Affiche le formulaire de connexion administrateur
     *
     * @return View
     */
    public function login(): View
    {
        return view('admin.login');
    }

    /**
     * Vérifie les identifiants administrateur et authentifie l'utilisateur
     *
     * @param Request $request Requête contenant les identifiants
     * @return \Illuminate\Http\RedirectResponse
     */
    public function authenticate(Request $request)
    {
        // Validation des champs requis
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // Vérification des identifiants administrateur
        if ($credentials['username'] === 'admin' && $credentials['password'] === 'admin123') {
            // Création de la session administrateur
            session(['admin_logged_in' => true]);
            return redirect()->route('admin.dashboard');
        }

        // En cas d'échec, retour au formulaire avec message d'erreur
        return back()->withErrors([
            'username' => 'Les identifiants sont incorrects.',
        ]);
    }

    /**
     * Affiche le tableau de bord administrateur
     *
     * @return View Vue du tableau de bord avec les données des histoires
     */
    public function dashboard(): View
    {
        // Récupération des histoires avec comptage des chapitres associés
        $stories = Story::withCount('chapters')->get();
        return view('admin.dashboard', compact('stories'));
    }

    /**
     * Déconnexion de l'administrateur
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        // Suppression de la session administrateur
        $request->session()->forget('admin_logged_in');
        return redirect()->route('admin.login');
    }
}