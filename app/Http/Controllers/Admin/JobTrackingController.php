<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobTracking;
use Illuminate\Http\Request;

class JobTrackingController extends Controller
{
    public function index(Request $request)
    {
        $jobs = JobTracking::when($request->status, function($q, $status) {
                return $q->where('status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $stats = [
            'total' => JobTracking::count(),
            'pending' => JobTracking::where('status', 'pending')->count(),
            'processing' => JobTracking::where('status', 'processing')->count(),
            'completed' => JobTracking::where('status', 'completed')->count(),
            'failed' => JobTracking::where('status', 'failed')->count(),
        ];

        return view('admin.job-tracking.index', compact('jobs', 'stats'));
    }

    public function show(JobTracking $jobTracking)
    {
        return view('admin.job-tracking.show', compact('jobTracking'));
    }

    public function retry($id)
    {
        $job = JobTracking::findOrFail($id);
        $jobClass = $job->job_class;

        if (class_exists($jobClass)) {
            dispatch(new $jobClass());
        }

        return back()->with('success', 'Job relancé');
    }

    public function destroy(JobTracking $jobTracking)
    {
        $jobTracking->delete();
        return redirect()->route('admin.job-tracking.index')->with('success', 'Job supprimé');
    }
}