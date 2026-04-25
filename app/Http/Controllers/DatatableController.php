<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\DataTables\UsersDataTable;

class DatatableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index(Request $request)
    // {
    //     // On ajoute with('roles') pour la performance (évite le N+1)
    //     return DataTable::make(User::query()->with('roles'), $request)
    //         ->searchable(['username', 'email'])
    //         ->filters([
    //             'status'      => ['type' => 'single'],
    //             'roles'       => [
    //                 'type' => 'relation',
    //                 'relation' => 'roles',
    //                 'column' => 'id'
    //             ],
    //             'date_start' => [
    //                 'type'      => 'range',
    //                 'column'    => 'created_at',
    //                 'is_date'   => true, // Important pour le format Date SQL
    //                 'start_key' => 'date_start',
    //                 'end_key'   => 'date_end'
    //             ],
    //             // AJOUT DES FILTRES SPÉCIAUX
    //             'no_role'     => [
    //                 'type' => 'no_relation',
    //                 'relation' => 'roles'
    //             ],
    //             'multi_roles' => [
    //                 'type' => 'has_count',
    //                 'relation' => 'roles',
    //                 'operator' => '>',
    //                 'count' => 1
    //             ],
    //         ])
    //         ->views('admin.users.partials.table')
    //         ->export('exports.users-pdf')
    //         ->paginate(25)
    //         ->render('admin.users.index', [
    //             'roles' => Role::all(),
    //             'total_active' => User::where('status', 'active')->count(),
    //             'page_title' => 'Gestion des utilisateurs'
    //         ]);
    // }

    public function index(Request $request)
    {
        // Toutes les éventualités (erreurs, reset, filtres) 
        // sont gérées par la classe UserDataTable.
        return UsersDataTable::make($request)->render();
    }
    
}
