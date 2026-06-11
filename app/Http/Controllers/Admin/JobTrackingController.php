<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobTracking;
use App\Support\Flash;
use Illuminate\Support\Facades\Log;

class JobTrackingController extends Controller
{
    public function index()
    {
        $jobs = JobTracking::orderByDesc('created_at')
            ->paginate(20);

        $stats = JobTracking::selectRaw("
            COUNT(*) as total,
            SUM(status = 'pending') as pending,
            SUM(status = 'processing') as processing,
            SUM(status = 'completed') as completed,
            SUM(status = 'failed') as failed
        ")->first();

        return view('admin.job-tracking.index', compact('jobs', 'stats'));
    }

    public function show(JobTracking $jobTracking)
    {
        return view('admin.job-tracking.show', compact('jobTracking'));
    }

    public function retry(JobTracking $jobTracking)
    {
        try {
            $jobClass = $jobTracking->job_class;

            if (class_exists($jobClass)) {
                dispatch(new $jobClass());
            }

            return back()->with(
                Flash::SUCCESS,
                __('messages.job.retried')
            );

        } catch (\Throwable $e) {
            Log::error($e);

            return back()->with(
                Flash::ERROR,
                __('messages.operation_failed')
            );
        }
    }

    public function destroy(JobTracking $jobTracking)
    {
        try {
            $jobTracking->delete();

            return redirect()
                ->route('admin.job-tracking.index')
                ->with(
                    Flash::SUCCESS,
                    __('messages.deleted')
                );

        } catch (\Throwable $e) {
            Log::error($e);

            return redirect()
                ->route('admin.job-tracking.index')
                ->with(
                    Flash::ERROR,
                    __('messages.delete_failed')
                );
        }
    }
}