<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use \Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class ReservationController extends Controller
{
    //Afficher toutes les réservations
    public function index()
    {
        $reservations =Reservation::with(['local', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'reservations' => $reservations
        ], 200);
    }

    //Afficher les reservations d'un utilisateur
    public function userReservations()
    {
        $user = Auth::user();
        $reservations =Reservation::with(['local', 'user'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'reservations' => $reservations
        ], 200);
    }

    //Afficher une réservation spécifique
    public function show($id)
    {
        $reservation = Reservation::with(['local', 'user'])
            ->findOrFail($id);

        return response()->json([
            'reservation' => $reservation
        ], 200);
    }

    //Faire un reservattion


    
}
