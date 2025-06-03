<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Cmgmyr\Messenger\Models\Thread;
use App\Notifications\NewMessageReceived;
use Cmgmyr\Messenger\Models\Message;
use Illuminate\Support\Facades\Auth;
use App\Models\JobApplication;

class InterviewProposalController extends Controller
{
    public function propose(Request $request, Thread $thread)
    {
        $request->validate([
            'job_offert_id' => 'required|exists:job_offerts,id',
            'datetime' => 'required|date|after:now',
        ]);

        $recruiter = Auth::user();
        $job = $recruiter->company->jobs()->findOrFail($request->job_offert_id);

        // Costruisci il messaggio
        $datetime = \Carbon\Carbon::parse($request->datetime)->format('d/m/Y H:i');
        $body = "📢 Ti propongo un colloquio per la posizione \"{$job->title}\" il giorno **{$datetime}**.\n\nPuoi accettare o rifiutare.";

        $message = Message::create([
            'thread_id' => $thread->id,
            'user_id' => $recruiter->id,
            'body' => $body,
        ]);

        $thread->activateAllParticipants();

        // Notifica il candidato
        $candidate = $thread->participants()->where('user_id', '!=', $recruiter->id)->with('user')->first();
        if ($candidate && $candidate->user) {
            $candidate->user->notify(new NewMessageReceived($message));
        }

        return redirect()->route('messages.show', $thread->id)->with('success', 'Proposta inviata.');
    }

    public function respond(Request $request, Thread $thread, $response)
    {
        $validResponses = ['accept', 'decline'];
        abort_unless(in_array($response, $validResponses), 400);

        $user = Auth::user();

        // Recupera l'ultimo messaggio di proposta
        $proposalMessage = $thread->messages()
            ->where('body', 'like', '%📢%')
            ->latest()
            ->first();

        if ($proposalMessage && $response === 'accept') {
            $title = $this->extractTitleFromMessage($proposalMessage->body);
            $datetime = $this->extractDatetimeFromMessage($proposalMessage->body); 

            $application = JobApplication::where('user_id', $user->id)
                ->whereHas('job', function ($query) use ($title) {
                    $query->where('title', $title);
                })
                ->first();

            if ($application && $datetime) {
                $application->interview_datetime = $datetime; 
                $application->save();
            }
        }


        // Salva la risposta nella chat
        $status = $response === 'accept' ? '✅ Ho accettato il colloquio.' : '❌ Ho rifiutato il colloquio.';
        Message::create([
            'thread_id' => $thread->id,
            'user_id' => $user->id,
            'body' => $status,
        ]);

        return redirect()->route('messages.show', $thread->id)->with('success', 'Risposta inviata.');
    }

    // (opzionale) helper per estrarre il titolo dell'offerta
    protected function extractTitleFromMessage($body)
    {
        preg_match('/posizione "(.*?)"/', $body, $matches);
        return $matches[1] ?? null;
    }

    protected function extractDatetimeFromMessage($body)
    {
        // Cerca qualcosa tipo 03/06/2025 12:30
        preg_match('/\*\*(\d{2}\/\d{2}\/\d{4} \d{2}:\d{2})\*\*/', $body, $matches);
        if (isset($matches[1])) {
            return \Carbon\Carbon::createFromFormat('d/m/Y H:i', $matches[1]);
        }
        return null;
    }




}
