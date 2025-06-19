<?php

namespace App\Http\Controllers;

use App\Mail\ContratMail;
use App\Models\Contrat;
use App\Models\Reservation;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class ContratController extends Controller
{
    public function index(){
        $contrats = Contrat::with(['user', 'reservation'])->orderBy('created_at', 'desc')->paginate(15);

        return response()->json($contrats);
    }

    public function show(Contrat $contrat){
        $contrat->load(['user', 'reservation']);

        return response()->json($contrat);
    }

    public function store(Request $request){
        $data = $request->validate([
            'id_reservation' => 'required|exists:reservations,id',
            'dateDebut' => 'required|date',
            'dateFin' => 'required|date|after_or_equal:dateDebut',
            'montant' => 'required|numeric|min:0',
            'frequence_paiement' => 'required|in:mensuel,annuel',
            'type' => 'required|in:location,sous-location,convention',
            'statut' => 'required|in:actif,resilie,expire'
        ]);

        $reservation = Reservation::findOrFail($data['id_reservation']);

        $contrat = $this->storeContrat($reservation, $data);

        return response()->json($contrat);
    }

    private function storeContrat(Reservation $reservation, array $data){
        $contrat = Contrat::create([
            'id_reservation' => $reservation->id,
            'id_user' => $reservation->id_user,
            'dateDebut' => $data['dateDebut'],
            'dateFin' => $data['dateFin'],
            'montant' => $data['montant'],
            'frequence_paiement' => $data['frequence_paiement'],
            'type' => $data['type'],
            'statut' => $data['statut'],
            'conditions' => Contrat::getDefaultTermsAndConditions()
        ]);

        $contrat->reference = $contrat->generateReference();
        $contrat->save();

        return $contrat;
    }

    private function cautionPaye(Reservation $reservation){
        return $reservation->paiement()
            ->where('type', 'caution')
            ->where('statut', 'validee')
            ->exists();
    }

    public function generePDFContrat(Contrat $contrat){
        $data = [
            'contrat' => $contrat,
            'reservation' => $contrat->reservation,
            'user' => $contrat->user
        ];

        $pdf = Pdf::loadView('contrat.pdfTemplate', $data);

        $filename = 'contrats/contrat_'. $contrat->reference . '.pdf';
        Storage::disk('public')->put($filename, $pdf->output());

        $contrat->update(['pdf_path' => $filename]);

        return $filename;
    }

    public function contratMail(Contrat $contrat){
        Mail::to($contrat->user->email)->send(new ContratMail($contrat));

        $contrat->update([
            'statut' => 'actif',
            'envoye_le' => now()
        ]);
    }

    public function genereContratApresAffectation($reservationId, Request $request){
        // Validation des données d'entrée
        $data = $request->validate([
            'dateDebut' => 'required|date',
            'dateFin' => 'required|date|after_or_equal:dateDebut',
            'montant' => 'required|numeric|min:0',
            'frequence_paiement' => 'required|in:mensuel,annuel',
            'type' => 'required|in:location,sous-location,convention',
            'statut' => 'required|in:actif,resilie,expire'
        ]);

        // Récupérer la réservation
        $reservation = Reservation::findOrFail($reservationId);

        if($reservation->statutDemande !== 'approuvee'){
            return response()->json([
                'success' => false,
                'message' => 'La réservation n\'est pas encore acceptée'
            ], 400);
        }

        if(!$this->cautionPaye($reservation)){
            return response()->json([
                'success' => false,
                'message' => 'La caution n\'est pas payée'
            ], 400);
        }

        if($reservation->contrat){
            return response()->json([
                'success' => false,
                'message' => 'Le contrat existe déjà'
            ], 400);
        }

        try {
            $contrat = $this->storeContrat($reservation, $data);

            $this->generePDFContrat($contrat);

            $this->contratMail($contrat);

            return response()->json([
                'success' => true,
                'message' => 'Contrat généré et envoyé avec succès',
                'contrat' => $contrat
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la génération du contrat: ' . $e->getMessage()
            ], 500);
        }
    }
}