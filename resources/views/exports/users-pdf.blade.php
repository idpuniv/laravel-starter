<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Export Utilisateurs</title>
    <style>
        * { font-family: DejaVu Sans, sans-serif; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { color: #333; font-size: 24px; }
        .header .date { color: #666; font-size: 14px; }
        .table { width: 100%; border-collapse: collapse; }
        .table th { background-color: #f8f9fa; border: 1px solid #dee2e6; padding: 12px; text-align: left; }
        .table td { border: 1px solid #dee2e6; padding: 10px; }
        .badge-success { background-color: #28a745; color: white; padding: 4px 8px; border-radius: 4px; }
        .badge-danger { background-color: #dc3545; color: white; padding: 4px 8px; border-radius: 4px; }
        .footer { margin-top: 30px; text-align: center; color: #6c757d; font-size: 12px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Liste des Utilisateurs</h1>
        <div class="date">Export généré le {{ date('d/m/Y à H:i') }}</div>
    </div>
    
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Prénom(s)</th>
                <th>Télephone</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $person)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $person->last_name }}</td>
                <td>{{ $person->first_name }}</td>
                <td>{{ $person->fullPhone }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="footer">
        Document généré automatiquement • Total : {{ $data->count() }} utilisateur(s)
    </div>
</body>
</html>