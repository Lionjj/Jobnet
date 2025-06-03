<?php

namespace App\Http\Controllers;

use App\Models\JobOffert;
use Illuminate\Support\Facades\Auth;
use App\Models\JobApplication;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $upcomingInterviews = JobApplication::where('user_id', $user->id)
            ->whereNotNull('interview_datetime')
            ->where('interview_datetime', '>', now())
            ->with(['job', 'job.company'])
            ->get();

        $jobOffers = JobOffert::whereHas('company', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->whereHas('applications') // solo offerte con almeno una candidatura
        ->with(['applications' => function ($query) {
            $query->latest(); // ordina le candidature recenti
        }])
        ->latest('updated_at') // ordina le offerte per aggiornamento recente
        ->take(5) // opzionale: limita a 5 offerte più recenti
        ->get();


        return view('dashboard', compact('upcomingInterviews', 'jobOffers' ));
    }
}

