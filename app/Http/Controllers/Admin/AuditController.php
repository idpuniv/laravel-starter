<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.audit.index');
    }
    
    public function show(AuditLog $auditLog)
    {
        return view('admin.audit.show', compact('auditLog'));
    }
    
    public function export(Request $request)
    {
        $logs = AuditLog::when($request->date_from, fn($q, $v) => $q->whereDate('created_at', '>=', $v))
            ->get();
        
        return response()->json($logs);
    }
    
    public function destroy(AuditLog $auditLog)
    {
        $auditLog->delete();
        return back()->with('success', 'Log supprimé');
    }
    
    public function clearOld()
    {
        AuditLog::where('created_at', '<', now()->subDays(90))->delete();
        return back()->with('success', 'Logs anciens supprimés');
    }
}