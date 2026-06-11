@section('title', 'Liste des utilisateurs')
<x-admin-layout>
    <div class="container pt-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Liste des utilisateurs</h3>
        @can(\App\Permissions\UserPermissions::CREATE)
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <i class="bi bi-plus"></i>
            Nouveau utilisateur
        </a>
        @endcan
    </div>
        <div class="row g-2 align-items-center mb-3">
            <div class="col">
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="bi bi-download"></i> Exporter
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end bg-dark">
                            <li><h6 class="dropdown-header text-muted">Page actuelle</h6></li>
                            <li><button type="button" class="dropdown-item" data-export="pdf" data-scope="current">PDF</button></li>
                            <li><button type="button" class="dropdown-item" data-export="excel" data-scope="current">Excel</button></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><h6 class="dropdown-header text-muted">Tout filtré</h6></li>
                            <li><button type="button" class="dropdown-item" data-export="pdf" data-scope="all">PDF</button></li>
                            <li><button type="button" class="dropdown-item" data-export="excel" data-scope="all">Excel</button></li>
                        </ul>
                    </div>

                    <select class="form-select form-select-sm w-auto dt-filter" name="table_length">
                        <option value="5">5</option>
                        <option value="25" selected>25</option>
                        <option value="50">50</option>
                    </select>
                    
                    <select class="form-select form-select-sm w-auto dt-filter" name="status">
                        <option value="">Tous les statuts</option>
                        <option value="active">Actif</option>
                        <option value="inactive">Inactif</option>
                    </select>

                    <div class="d-flex align-items-center gap-2 border-start ps-2">
                        <input type="date" name="date_start" id="date_start" class="form-control form-control-sm dt-filter">
                        <input type="date" name="date_end" id="date_end" class="form-control form-control-sm dt-filter">
                        <button type="button" class="btn btn-sm btn-outline-secondary" data-reset="#date_start, #date_end">
                            <i class="bi bi-x-circle"></i>
                        </button>
                    </div>

                    <div class="d-flex align-items-center gap-2 border-start ps-2">
                        <select name="roles[]" id="filter-roles" class="form-select form-select-sm w-auto dt-filter" multiple size="1">
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                        <button type="button" class="btn btn-sm btn-outline-secondary" data-reset="#filter-roles">
                            <i class="bi bi-person-badge"></i>
                        </button>
                    </div>

                    <div class="d-flex align-items-center gap-3 border-start ps-3">
                        <div class="form-check form-check-inline mb-0">
                            <input class="form-check-input dt-filter" type="checkbox" name="no_role" id="filter-no-role" value="1">
                            <label class="form-check-label small fw-bold" for="filter-no-role">Sans rôle</label>
                        </div>
                        <div class="form-check form-check-inline mb-0">
                            <input class="form-check-input dt-filter" type="checkbox" name="multi_roles" id="filter-multi-roles" value="1">
                            <label class="form-check-label small fw-bold" for="filter-multi-roles">Multi-rôles</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-auto">
                <input type="text" class="form-control form-control-sm dt-filter" name="search" placeholder="Rechercher...">
            </div>
        </div>

        <div id="table-container">
            {!! $table !!}
        </div>
    </div>
</x-admin-layout>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const userTable = new DataTableManager({
            url: "{{ route('admin.users.index') }}",
            container: '#table-container'
        });
    });
</script>