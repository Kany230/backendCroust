<?php

use App\Http\Controllers\CartographieController;
use App\Http\Controllers\ChambreController;
use App\Http\Controllers\ContratController;
use App\Http\Controllers\EquipementController;
use App\Http\Controllers\MaintenanceController;
use App\Models\Chambre;
use App\Models\Equipement;
use App\Models\Maintenance;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('cartographie')->group( function () {
        Route::get('/donne', [CartographieController::class, 'getDonneeCartographie']);
        Route::get('/index', [CartographieController::class, 'index']);
        Route::get('{id}/site', [CartographieController::class, 'getDonneeSite']);
        Route::get('{id}/local', [CartographieController::class, 'getDonneeLocal']);
        Route::get('{id}/element', [CartographieController::class, 'getDetailsElements']);
        Route::get('{id}/show', [CartographieController::class, 'show']);
    });

    Route::prefix('/chambres')->group(function(){
        Route::get('/index', [ChambreController::class, 'index']);
        Route::get('{id}/show', [ChambreController::class, 'show']);
        Route::post('store', [ChambreController::class, 'store']);
        Route::put('{id}/update', [ChambreController::class, 'update']);
        Route::delete('{id}/destroy', [ChambreController::class, 'destroy']);
        Route::get('{id}/parpavillon', [ChambreController::class, 'getParPavillon']);
        Route::get('disponible', [ChambreController::class, 'getDisponible']);
        Route::post('{id}/dupliquer', [ChambreController::class, 'dupliquerChambre']);
        Route::post('{IdChambre}/assigner', [ChambreController::class, 'assignerUser']);
        Route::delete('{idChambre}/retirer/{idUser}', [ChambreController::class, 'retirerUser']);
    });

    Route::prefix('/equipements')->group(function(){
        Route::get('/index', [EquipementController::class, 'index']);
        Route::get('{id}/show', [EquipementController::class, 'show']);
        Route::post('store', [EquipementController::class, 'store']);
        Route::put('{id}/update', [EquipementController::class, 'update']);
        Route::delete('{id}/delete', [EquipementController::class, 'destroy']);
        Route::get('statistique', [EquipementController::class, 'getStatistique']);
        Route::get('parCantine', [EquipementController::class, 'getParCantine']);
        Route::get('parPavillon/{id}', [EquipementController::class, 'getParPavillon']);
        Route::get('parChambre/{id}', [EquipementController::class, 'getParChambre']);
    });

    Route::prefix('/contrats')->group(function(){
        Route::get('index', [ContratController::class, 'index']);
        Route::get('{id}/show', [ContratController::class, 'show']);
        Route::post('store', [ContratController::class, 'store']);
        Route::post('{reservationId}/generer', [ContratController::class, 'genereContratApresAffectation']);
    });

    Route::prefix('/maintenances')->group(function(){
        Route::get('index', [MaintenanceController::class, 'index']);
        Route::get('{id}/show', [MaintenanceController::class, 'show']);
        Route::post('store', [MaintenanceController::class, 'store']);
        Route::get('mesmaintenances', [MaintenanceController::class, 'mesMaintenances']);
        Route::get('{id}/createmaintenance', [MaintenanceController::class, 'createMaintenanceParUser']);
        Route::get('{id}/edit', [MaintenanceController::class, 'edit']);
        Route::put('{maintenance}/update', [MaintenanceController::class, 'update']);
        Route::post('{maintenance}/demarrer', [MaintenanceController::class, 'demarrer']);
        Route::post('{maintenance}/termine', [MaintenanceController::class, 'termine']);
        Route::get('{maintenance}/telecharge', [MaintenanceController::class, 'telechargerPdfRapport']);
        Route::delete('{maintenance}/destroy', [MaintenanceController::class, 'destroy']);
        Route::get('statistiques', [MaintenanceController::class, 'statistiques']);
    });

});
