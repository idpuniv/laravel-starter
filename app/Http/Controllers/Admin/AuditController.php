<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    public function index(Request $request)
    {
        $logs = AuditLog::with('actor')
            ->when($request->event, fn($q, $v) => $q->where('event', $v))
            ->when($request->outcome, fn($q, $v) => $q->where('event_outcome', $v))
            ->when($request->user_id, fn($q, $v) => $q->where('actor_id', $v))
            ->when($request->date_from, fn($q, $v) => $q->whereDate('created_at', '>=', $v))
            ->when($request->date_to, fn($q, $v) => $q->whereDate('created_at', '<=', $v))
            ->orderBy('created_at', 'desc')
            ->paginate(30);
        
        $stats = [
            'total' => AuditLog::count(),
            'today' => AuditLog::whereDate('created_at', today())->count(),
            'events' => AuditLog::selectRaw('event, count(*) as total')->groupBy('event')->pluck('total', 'event'),
        ];
        
        return view('admin.audit.index', compact('logs', 'stats'));
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