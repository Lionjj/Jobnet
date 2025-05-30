<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SavedJobController extends Controller
{
    public function store(Request $request, $jobId)
    {
        $user = auth()->user();
        $user->savedJobs()->syncWithoutDetaching([$jobId]); // aggiunge senza duplicare

        return back()->with('success', 'Offerta salvata!');
    }

    public function destroy(Request $request, $jobId)
    {
        $user = auth()->user();
        $user->savedJobs()->detach($jobId);

        return back()->with('success', 'Offerta rimossa dai preferiti.');
    }

    public function savedJobs()
    {
        $jobs = auth()->user()->savedJobs()->paginate(10);
        return view('jobs.saved', compact('jobs'));
    }

}
