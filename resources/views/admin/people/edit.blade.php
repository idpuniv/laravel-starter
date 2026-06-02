@section('title', 'Modifier une personne')

<x-admin-layout>

<div class="container py-4">

    {{-- Carte informations de la personne --}}
    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-header bg-transparent border-bottom-0 pt-4 px-4">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-person-circle text-primary fs-4"></i>
                <h5 class="mb-0">Informations personnelles</h5>
            </div>
        </div>
        <div class="card-body p-4 pt-0">
            <form method="POST" action="{{ route('admin.people.update', $person->id) }}">
                @csrf
                @method('PUT')

                @include('admin.people.partials.form')

                <div class="mt-4 d-flex justify-content-end gap-2">
                    <button type="submit" class="btn btn-primary">
                     Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Carte compte utilisateur --}}
    @if($person->user)
    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-header bg-transparent border-bottom-0 pt-4 px-4">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-person-check text-primary fs-4"></i>
                <h5 class="mb-0">Compte utilisateur</h5>
            </div>
        </div>
        <div class="card-body p-4 pt-0">
            <form method="POST" action="{{ route('admin.users.update', $person->user->id) }}">
                @csrf
                @method('PUT')
                
                @include('admin.users.partials.form', ['user' => $person->user])
                
                <div class="mt-4 d-flex justify-content-end gap-2">
                    <button type="submit" class="btn btn-primary">
                     Mettre à jour le compte
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Carte rôles et permissions --}}
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-transparent border-bottom-0 pt-4 px-4">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-shield-lock text-primary fs-4"></i>
                <h5 class="mb-0">Rôles et permissions</h5>
            </div>
        </div>
        <div class="card-body p-4 pt-0">
            {{-- Rôles --}}
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-shield-check text-primary"></i>
                        <strong class="mb-0">Rôles</strong>
                    </div>
                    @can(\App\Permissions\UserPermissions::UPDATE_ROLE)
                        <a href="{{ route('admin.users.roles.edit', $person->user->id) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil"></i> Gérer les rôles
                        </a>
                    @endcan
                </div>
                <div class="d-flex flex-wrap gap-2">
                    @forelse($person->user->roles as $role)
                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                            <i class="bi bi-shield-check"></i> {{ $role->label }}
                        </span>
                    @empty
                        <span class="text-muted">Aucun rôle assigné</span>
                    @endforelse
                </div>
            </div>

            {{-- Permissions directes --}}
            <div class="pt-3 border-top">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-key text-primary"></i>
                        <strong class="mb-0">Permissions directes</strong>
                    </div>
                    @can(\App\Permissions\UserPermissions::UPDATE_PERMISSION)
                        <a href="{{ route('admin.users.permissions.edit', $person->user->id) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil"></i> Gérer les permissions
                        </a>
                    @endcan
                </div>
                <div class="d-flex flex-wrap gap-2">
                    @forelse($person->user->permissions as $permission)
                        <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill">
                            <i class="bi bi-check-lg"></i> {{ $permission->label }}
                        </span>
                    @empty
                        <span class="text-muted">Aucune permission directe</span>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4 text-center">
            <i class="bi bi-info-circle text-muted fs-1"></i>
            <p class="mt-2 mb-0">Aucun compte utilisateur associé</p>
            <a href="{{ route('admin.people.show-add-user-form', $person->id) }}" class="btn btn-sm btn-primary mt-3">
                <i class="bi bi-plus-lg"></i> Créer un compte
            </a>
        </div>
    </div>
    @endif

    {{-- ============================================ --}}
{{-- SECTION GROUPES --}}
{{-- ============================================ --}}
<div class="card shadow-sm border-0 rounded-4 mb-4">
    <div class="card-header bg-transparent border-bottom-0 pt-4 px-4">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-people-fill text-primary fs-4"></i>
                <h5 class="mb-0">Groupes</h5>
            </div>
            <a href="#" class="btn btn-sm btn-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#newGroupModal">
                <i class="bi bi-plus-lg"></i> Nouveau groupe
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="ps-4 py-3" width="35%">Groupe</th>
                        <th class="py-3" width="45%">Rôles associés</th>
                        <th class="pe-4 py-3 text-end" width="20%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Groupe 1 : Administrateurs --}}
                    <tr id="group-1">
                        <td class="ps-4">
                            <div>
                                <div class="fw-semibold">Administrateurs</div>
                                <div class="text-secondary small">Accès complet au système</div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-wrap gap-1" id="group-roles-1">
                                <span class="badge bg-primary bg-opacity-10 text-primary px-2 py-1 rounded-pill">Super Admin</span>
                                <span class="badge bg-primary bg-opacity-10 text-primary px-2 py-1 rounded-pill">Admin</span>
                            </div>
                        </td>
                        <td class="pe-4 text-end">
                            <a href="#" class="btn btn-sm btn-outline-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#editGroupModal" data-group-id="1" data-group-name="Administrateurs">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="#" class="btn btn-sm btn-outline-danger rounded-pill ms-1">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>

                    {{-- Groupe 2 : Modérateurs --}}
                    <tr id="group-2">
                        <td class="ps-4">
                            <div>
                                <div class="fw-semibold">Modérateurs</div>
                                <div class="text-secondary small">Gestion des utilisateurs et contenus</div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-wrap gap-1" id="group-roles-2">
                                <span class="badge bg-info bg-opacity-10 text-info px-2 py-1 rounded-pill">Modérateur</span>
                            </div>
                        </td>
                        <td class="pe-4 text-end">
                            <a href="#" class="btn btn-sm btn-outline-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#editGroupModal" data-group-id="2" data-group-name="Modérateurs">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="#" class="btn btn-sm btn-outline-danger rounded-pill ms-1">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>

                    {{-- Groupe 3 : Éditeurs --}}
                    <tr id="group-3">
                        <td class="ps-4">
                            <div>
                                <div class="fw-semibold">Éditeurs</div>
                                <div class="text-secondary small">Gestion des contenus uniquement</div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-wrap gap-1" id="group-roles-3">
                                <span class="badge bg-success bg-opacity-10 text-success px-2 py-1 rounded-pill">Éditeur</span>
                                <span class="badge bg-success bg-opacity-10 text-success px-2 py-1 rounded-pill">Rédacteur</span>
                            </div>
                        </td>
                        <td class="pe-4 text-end">
                            <a href="#" class="btn btn-sm btn-outline-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#editGroupModal" data-group-id="3" data-group-name="Éditeurs">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="#" class="btn btn-sm btn-outline-danger rounded-pill ms-1">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>

                    {{-- Groupe 4 : Visualisateurs --}}
                    <tr id="group-4">
                        <td class="ps-4">
                            <div>
                                <div class="fw-semibold">Visualisateurs</div>
                                <div class="text-secondary small">Accès en lecture seule</div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-wrap gap-1" id="group-roles-4">
                                <span class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1 rounded-pill">Visiteur</span>
                                <span class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1 rounded-pill">Invité</span>
                            </div>
                        </td>
                        <td class="pe-4 text-end">
                            <a href="#" class="btn btn-sm btn-outline-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#editGroupModal" data-group-id="4" data-group-name="Visualisateurs">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="#" class="btn btn-sm btn-outline-danger rounded-pill ms-1">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ============================================ --}}
{{-- SECTION RÔLES ET PERMISSIONS --}}
{{-- ============================================ --}}
<div class="card shadow-sm border-0 rounded-4">
    <div class="card-header bg-transparent border-bottom-0 pt-4 px-4">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-shield-lock text-primary fs-4"></i>
                <h5 class="mb-0">Rôles et permissions</h5>
            </div>
            <a href="#" class="btn btn-sm btn-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#newRoleModal">
                <i class="bi bi-plus-lg"></i> Nouveau rôle
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="ps-4 py-3" width="30%">Rôle</th>
                        <th class="py-3" width="55%">Permissions</th>
                        <th class="pe-4 py-3 text-end" width="15%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Rôle 1 : Super Admin --}}
                    <tr id="role-1">
                        <td class="ps-4">
                            <div>
                                <div class="fw-semibold">Super Admin</div>
                                <div class="text-secondary small">Accès total</div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-danger bg-opacity-10 text-danger px-2 py-1 rounded-pill">Toutes les permissions</span>
                        </td>
                        <td class="pe-4 text-end">
                            <a href="#" class="btn btn-sm btn-outline-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#editRoleModal" data-role-id="1" data-role-name="Super Admin">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="#" class="btn btn-sm btn-outline-danger rounded-pill ms-1">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>

                    {{-- Rôle 2 : Admin --}}
                    <tr id="role-2">
                        <td class="ps-4">
                            <div>
                                <div class="fw-semibold">Admin</div>
                                <div class="text-secondary small">Gestion avancée</div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-wrap gap-1">
                                <span class="badge bg-primary bg-opacity-10 text-primary px-2 py-1 rounded-pill">Voir</span>
                                <span class="badge bg-primary bg-opacity-10 text-primary px-2 py-1 rounded-pill">Créer</span>
                                <span class="badge bg-primary bg-opacity-10 text-primary px-2 py-1 rounded-pill">Modifier</span>
                                <span class="badge bg-primary bg-opacity-10 text-primary px-2 py-1 rounded-pill">Supprimer</span>
                            </div>
                        </td>
                        <td class="pe-4 text-end">
                            <a href="#" class="btn btn-sm btn-outline-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#editRoleModal" data-role-id="2" data-role-name="Admin">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="#" class="btn btn-sm btn-outline-danger rounded-pill ms-1">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>

                    {{-- Rôle 3 : Modérateur --}}
                    <tr id="role-3">
                        <td class="ps-4">
                            <div>
                                <div class="fw-semibold">Modérateur</div>
                                <div class="text-secondary small">Modération</div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-wrap gap-1">
                                <span class="badge bg-info bg-opacity-10 text-info px-2 py-1 rounded-pill">Voir</span>
                                <span class="badge bg-info bg-opacity-10 text-info px-2 py-1 rounded-pill">Modifier</span>
                                <span class="badge bg-info bg-opacity-10 text-info px-2 py-1 rounded-pill">Supprimer</span>
                            </div>
                        </td>
                        <td class="pe-4 text-end">
                            <a href="#" class="btn btn-sm btn-outline-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#editRoleModal" data-role-id="3" data-role-name="Modérateur">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="#" class="btn btn-sm btn-outline-danger rounded-pill ms-1">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>

                    {{-- Rôle 4 : Éditeur --}}
                    <tr id="role-4">
                        <td class="ps-4">
                            <div>
                                <div class="fw-semibold">Éditeur</div>
                                <div class="text-secondary small">Création et modification</div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-wrap gap-1">
                                <span class="badge bg-success bg-opacity-10 text-success px-2 py-1 rounded-pill">Créer</span>
                                <span class="badge bg-success bg-opacity-10 text-success px-2 py-1 rounded-pill">Modifier</span>
                            </div>
                        </td>
                        <td class="pe-4 text-end">
                            <a href="#" class="btn btn-sm btn-outline-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#editRoleModal" data-role-id="4" data-role-name="Éditeur">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="#" class="btn btn-sm btn-outline-danger rounded-pill ms-1">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>

                    {{-- Rôle 5 : Invité --}}
                    <tr id="role-5">
                        <td class="ps-4">
                            <div>
                                <div class="fw-semibold">Invité</div>
                                <div class="text-secondary small">Lecture seule</div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1 rounded-pill">Voir</span>
                        </td>
                        <td class="pe-4 text-end">
                            <a href="#" class="btn btn-sm btn-outline-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#editRoleModal" data-role-id="5" data-role-name="Invité">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="#" class="btn btn-sm btn-outline-danger rounded-pill ms-1">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ============================================ --}}
{{-- MODAL ÉDITER GROUPE (avec checkboxes pour assigner les rôles) --}}
{{-- ============================================ --}}
<div class="modal fade" id="editGroupModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4">
            <div class="modal-header border-0 pt-4 px-4">
                <h5 class="modal-title">Assigner des rôles au groupe</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 pt-0">
                <div class="mb-3">
                    <label class="form-label">Groupe : <strong id="modalGroupName">Administrateurs</strong></label>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <strong>Rôles disponibles</strong>
                    <div class="form-check form-switch">
                        <input type="checkbox" class="form-check-input" id="masterRolesSwitch">
                        <label class="form-check-label">Tout cocher</label>
                    </div>
                </div>

                <div class="border rounded-3 p-3">
                    <div class="form-check mb-2">
                        <input type="checkbox" class="form-check-input role-checkbox" data-role-id="1" data-role-name="Super Admin" id="group_role_1" checked>
                        <label for="group_role_1">Super Admin</label>
                    </div>
                    <div class="form-check mb-2">
                        <input type="checkbox" class="form-check-input role-checkbox" data-role-id="2" data-role-name="Admin" id="group_role_2" checked>
                        <label for="group_role_2">Admin</label>
                    </div>
                    <div class="form-check mb-2">
                        <input type="checkbox" class="form-check-input role-checkbox" data-role-id="3" data-role-name="Modérateur" id="group_role_3">
                        <label for="group_role_3">Modérateur</label>
                    </div>
                    <div class="form-check mb-2">
                        <input type="checkbox" class="form-check-input role-checkbox" data-role-id="4" data-role-name="Éditeur" id="group_role_4">
                        <label for="group_role_4">Éditeur</label>
                    </div>
                    <div class="form-check mb-2">
                        <input type="checkbox" class="form-check-input role-checkbox" data-role-id="5" data-role-name="Rédacteur" id="group_role_5">
                        <label for="group_role_5">Rédacteur</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input role-checkbox" data-role-id="6" data-role-name="Invité" id="group_role_6">
                        <label for="group_role_6">Invité</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pb-4 px-4">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="saveGroupRolesBtn" data-bs-dismiss="modal">Enregistrer</button>
            </div>
        </div>
    </div>
</div>

{{-- ============================================ --}}
{{-- MODAL ÉDITER RÔLE (avec section permissions directes pliable) --}}
{{-- ============================================ --}}
<div class="modal fade" id="editRoleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4">
            <div class="modal-header border-0 pt-4 px-4">
                <h5 class="modal-title">Modifier le rôle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 pt-0">
                <div class="mb-4">
                    <label class="form-label">Nom du rôle</label>
                    <input type="text" class="form-control" id="roleNameInput" value="Admin">
                </div>

                {{-- SECTION PLIABLE : PERMISSIONS DIRECTES --}}
                <div class="accordion" id="permissionsDirectesAccordion">
                    <div class="accordion-item border rounded-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePermissionsDirectes">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-key text-primary"></i>
                                    <strong>Permissions directes</strong>
                                </div>
                            </button>
                        </h2>
                        <div id="collapsePermissionsDirectes" class="accordion-collapse collapse show" data-bs-parent="#permissionsDirectesAccordion">
                            <div class="accordion-body">
                                {{-- Groupe Utilisateurs --}}
                                <div class="mb-3">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <i class="bi bi-people text-primary small"></i>
                                        <strong class="small">Utilisateurs</strong>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input permission-switch" id="perm_user_view" checked>
                                                <label for="perm_user_view">Voir les utilisateurs</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input permission-switch" id="perm_user_create" checked>
                                                <label for="perm_user_create">Créer des utilisateurs</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input permission-switch" id="perm_user_edit" checked>
                                                <label for="perm_user_edit">Modifier des utilisateurs</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input permission-switch" id="perm_user_delete">
                                                <label for="perm_user_delete">Supprimer des utilisateurs</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Groupe Contenus --}}
                                <div class="mb-3">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <i class="bi bi-file-text text-primary small"></i>
                                        <strong class="small">Contenus</strong>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input permission-switch" id="perm_content_view" checked>
                                                <label for="perm_content_view">Voir les contenus</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input permission-switch" id="perm_content_create" checked>
                                                <label for="perm_content_create">Créer des contenus</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input permission-switch" id="perm_content_edit" checked>
                                                <label for="perm_content_edit">Modifier des contenus</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input permission-switch" id="perm_content_delete">
                                                <label for="perm_content_delete">Supprimer des contenus</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Groupe Système --}}
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <i class="bi bi-gear text-primary small"></i>
                                        <strong class="small">Système</strong>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input permission-switch" id="perm_logs_view">
                                                <label for="perm_logs_view">Voir les logs</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input permission-switch" id="perm_settings_edit">
                                                <label for="perm_settings_edit">Modifier les paramètres</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pb-4 px-4">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Supprimer</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Enregistrer</button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL NOUVEAU GROUPE --}}
<div class="modal fade" id="newGroupModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4">
            <div class="modal-header border-0 pt-4 px-4">
                <h5 class="modal-title">Nouveau groupe</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 pt-0">
                <div class="mb-3">
                    <label class="form-label">Nom du groupe</label>
                    <input type="text" class="form-control" placeholder="Ex: Modérateurs">
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea class="form-control" rows="2" placeholder="Description du groupe"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Couleur</label>
                    <select class="form-select">
                        <option value="primary">Bleu</option>
                        <option value="success">Vert</option>
                        <option value="info">Cyan</option>
                        <option value="warning">Orange</option>
                        <option value="danger">Rouge</option>
                        <option value="secondary">Gris</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer border-0 pb-4 px-4">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Créer</button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL NOUVEAU RÔLE --}}
<div class="modal fade" id="newRoleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4">
            <div class="modal-header border-0 pt-4 px-4">
                <h5 class="modal-title">Nouveau rôle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 pt-0">
                <div class="mb-3">
                    <label class="form-label">Nom du rôle</label>
                    <input type="text" class="form-control" placeholder="Ex: Éditeur">
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea class="form-control" rows="2" placeholder="Description du rôle"></textarea>
                </div>
                <div class="accordion" id="newPermissionsAccordion">
                    <div class="accordion-item border rounded-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#newCollapsePermissions">
                                <i class="bi bi-key text-primary me-2"></i>
                                <strong>Permissions directes</strong>
                            </button>
                        </h2>
                        <div id="newCollapsePermissions" class="accordion-collapse collapse show">
                            <div class="accordion-body">
                                <div class="mb-3">
                                    <strong class="small">Utilisateurs</strong>
                                    <div class="form-check form-switch mt-2">
                                        <input type="checkbox" class="form-check-input">
                                        <label>Voir les utilisateurs</label>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input">
                                        <label>Créer des utilisateurs</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pb-4 px-4">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Créer</button>
            </div>
        </div>
    </div>
</div>
</div>
</x-admin-layout>