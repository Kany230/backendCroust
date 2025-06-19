<?php

namespace App\Http\Controllers;
use \Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class ReservationController extends Controller
{
    // Faire une réservation
    public function reserve(Request $request)
    {
        
        // Utilisateur connecté soit un etudiant ou un commerçant
        // $user = Auth::user();

        // $data = $request->validate([
        //     'id_local' => 'required|exists:locals,id',
        //     'dateDebut' => 'required|date',
        //     'dateFin' => 'required|date|after_or_equal:dateDebut',
        //     'description' => 'nullable|string|max:255',
        //     'choixLocal' => 'required|string|in:local,produit,service',
        //     'produitOuService' => 'nullable|string|max:255',
        //     'qualiteQHSE' => 'nullable|string|max:255',
        //     'nombreCredit' => 'nullable|integer|min:0',
        //     'moyenneAnnuelle' => 'nullable|numeric|min:0|max:20',
        // ]);
        // // Créer la réservation
        // return response()->json(['message' => 'Réservation créée avec succès', 'reservation' => $reservation], 201);


    }
}
