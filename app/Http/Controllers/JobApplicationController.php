<?php

namespace App\Http\Controllers;

use App\Notifications\JobApplicationNotification;
use Illuminate\Http\Request;
use App\Models\JobOffert;
use App\Models\JobApplication;
use App\Notifications\ApplicationStatusUpdated;

class JobApplicationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'job_offert_id' => 'required|exists:job_offerts,id',
            'cover_letter' => 'nullable|string|max:3000',
        ]);

        $job = JobOffert::findOrFail($request->job_offert_id);

        // Blocca doppia candidatura
        if ($job->applications()->where('user_id', auth()->id())->exists()) {
            return redirect()->route('jobs.publicShow', $request->job_offert_id)->with('error', 'Hai già inviato la candidatura.');
        }
       
        $application = $job->applications()->create([
            'user_id' => auth()->id(),
            'cover_letter' => $request->cover_letter,
            'status' => 'sent',
        ]);

        $recruiter = $application->job->company->user;
        $recruiter->notify(new JobApplicationNotification($application));

        return redirect()->route('jobs.publicShow', $request->job_offert_id)->with('success', 'Candidatura inviata con successo!');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:sent,accepted,rejected',
        ]);

        $application = JobApplication::findOrFail($id);

        if ($application->job->company->user_id !== auth()->id()) {
            abort(403);
        }

        $application->status = $request->status;
        $application->save();

        $application->user->notify(new ApplicationStatusUpdated($application));

        return back()->withInput(['tab' => 'candidate'])->with(['success', 'Stato aggiornato con successo.']);
    }
}
