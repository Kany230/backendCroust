<?php

namespace App\Http\Controllers;

use App\Mail\MaintenanceAffecteeMail;
use App\Mail\RapportMaintenanceMail;
use App\Models\Maintenance;
use App\Models\Reclamation;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;


class MaintenanceController extends Controller
{

    public function index(){
        $maintenance = Maintenance::with(['reclamation.local', 'technicien', 'user'])->get();

        return response()->json($maintenance);
    }

    public function show(Maintenance $maintenance){
        $maintenance->load(['reclamation.local', 'reclamation.user', 'technicien', 'user']);

        return response()->json($maintenance);
    }

    //Enregistre une nouvelle maintenance
    //DB::transaction nous permet la synchronisation dans la base de donne
    //Car sanstransaction si la 2eme ption echoue on aura une maintenace creer mais la reclamtion restera status ouvert
    public function store(Request $request){
        $data = $request->validate([
            'id_reclamation' => 'required|exists:reclamations,id',
            'id_technicien' => 'required|exists:users,id',
            'description' => 'required|string',
            'priorite' => 'required|in:faible,normal,eleve,ugente',
            'date_debut' => 'nullable|date|after_or_equal:today',
            'date_fin_prevue'=>'nullable|date|after:date_debut',
            'remarques' => 'nullable|string'
        ]);
        $maintenance = null;
        try {
            DB::transaction(function() use ($data, &$maintenance) {
            $maintenance = Maintenance::create([
                'id_reclamation' => $data['id_reclamation'],
                'id_technicien' => $data['id_technicien'],
                'id_user' => Auth::id(),
                'description' => $data['description'],
                'priorite' => $data['priorite'],
                'date_signalement' => now(),
                'date_debut' => $data['date_debut'] ?? null,
                'date_fin_prevue' => $data['date_fin_prevue'] ?? null,
                'statut' => $data['date_debut'] ? 'programme' : 'en_attente',
                'remarques' => $data['remarques'] ?? null
            ]);

            Reclamation::where('id', $data['id_reclamation'])
                        ->update([
                            'statut' => 'assigne',
                        ]);
        });

         
        if (!$maintenance) {
            throw new \Exception('Maintenance creation failed');
        }
        
        $maintenance->load(['technicien', 'reclamation.local']);

        Mail::to($maintenance->technicien->email)->send(new MaintenanceAffecteeMail($maintenance));

        return response()->json($maintenance);

    } catch (\Exception $e) {
         return response()->json([
            'error' => 'Une erreur est survenue lors de la création de la maintenance.',
            'message' => $e->getMessage()
        ], 500);
    }
}
    public function mesMaintenances($id){
        $maintences = Maintenance::with(['reclamation.local', 'user'])
        ->pourTechnicien(Auth::id())
        ->orderBy('priorite', 'desc')
        ->orderBy('date_debut', 'asc')
        ->paginate(10);
        
        return response()->json($maintences);
    }

    public function createMaintenanceParUser($id){
        $reclamation = Reclamation::with(['local'])->findOrFail($id);

        $techniciens = User::where('role', 'technicien')
        ->where('statut', 'actif')
        ->get();

        return response()->json([$reclamation, $techniciens]);
    }

    //modifier une maintenance
    public function edit(Maintenance $maintenance){
        $techniciens = User::where('role', 'technicien')
        ->where('statut', 'actif')
        ->get();

        return response()->json([$techniciens, $maintenance]);
    }

    public function update(Request $request, Maintenance $maintenance){
        $data = $request->validate([
            'id_technicien' => 'required|exists:users,id',
            'description' => 'required|string',
            'priorite' => 'required|in:faible,normal,eleve,urgente',
            'date_debut' => 'nullable|date',
            'date_fin_prevue' => 'nullable|date|after:date_debut',
            'date_fin_reelle' => 'nullable|date|after:date_debut',
            'statut' => 'required|in:programme,en_cours,termine,annule,en_attente',
            'remarques' => 'nullable|string',
            'rapport_final' => 'nullable|string',
            'materiel_utilise' => 'nullable|array'
        ]);

        DB::transaction(function () use ($maintenance, $data) {
            $maintenance->update($data);

            $statutReclamtion = match($data['statut']){
                'en_cours' => 'en cours',
                'termine' => 'resolue',
                'annule' => 'ouvert',
                default => 'assigne'
            };

            $maintenance->load('reclamation');
            $maintenance->reclamation->update(['statut' => $statutReclamtion]);
        });

        return response()->json($maintenance);
    }

    public function demarrer(Maintenance $maintenance){
        $maintenance->update([
            'statut' => 'en_cours',
            'date_debut' => $maintenance->date_debut ?? now()
        ]);

        $maintenance->reclamation->update(['statut' => 'en cours']);

        return response()->json('Maintenance demarree');
    }

    public function termine(Request $request, Maintenance $maintenance){
        $data = $request->validate([
            'rapport_final' => 'required|string',
            'materiel_utilise' => 'nullable|array'
        ]);

        DB::transaction(function() use ($maintenance, $data){
            $maintenance->update([
                'statut' => 'termine',
                'date_fin_reelle' => now(),
                'rapport_final' => $data['rapport_final'],
                'materiel_utilise' => $data['materiel_utilise'] ?? []
            ]);

            $maintenance->reclamation->update(['statut' => 'resolue']);
        });

        $maintenance = $maintenance->load(['reclamation', 'user', 'technicien']);

        
        $pdf = Pdf::loadView('maintenance.rapport', ['maintenance' => $maintenance]);
        $path = 'rapports/maintenance_'.$maintenance->id.'.pdf';
        Storage::disk('public')->put($path, $pdf->output());
        $maintenance->update(['rapport_pdf_path' => $path]);
        
        $user = $maintenance->user;
        if ($user && $user->email) {
                Mail::to($user->email)->send(new RapportMaintenanceMail($maintenance));
        }

        return response()->json([
            'message' => 'Maintenance termine', 
            'pdf_url' => asset('storage/' . $path)
        ]);
    }

    public function telechargerPdfRapport(Maintenance $maintenance){
        $maintenance = $maintenance->load(['reclamation', 'user', 'technicien']);
        $pdf = Pdf::loadView('maintenance.rapport', ['maintenance' => $maintenance]);

        return $pdf->download('rapport_maintenance_'.$maintenance->id.'.pdf');
    }

    public function destroy(Maintenance $maintenance){
        DB::transaction(function() use ($maintenance){
            $maintenance->reclamation->update(['statut' => 'ouvert']);

            $maintenance->delete();
        });

        return response()->json('Maintenance supprime');
    }

    public function statistiques(){
        $data = [
            'total' => Maintenance::count(),
            'en_cours' => Maintenance::where('statut', 'en_cours')->count(),
            'terminees' => Maintenance::where('statut', 'termine')->count(),
            'en_retard' => Maintenance::enRetard()->count()
        ];

        $maintenanceParMois = Maintenance::selectRaw("DATE_FORMAT(created_at, '%M') as mois, COUNT(*) as total")
        ->groupBy('mois')
        ->orderByRaw("MONTH(created_at)")
        ->get();

        return response()->json([
            'par mois' => $maintenanceParMois, 
            'data' => $data
        ]);
    }
}
