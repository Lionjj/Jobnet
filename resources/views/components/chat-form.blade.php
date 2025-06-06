{{-- Box azioni recruiter --}}

<div class="mt-10 bg-white border border-gray-200 rounded-lg shadow p-6 space-y-6">
    <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
        ✉️ Invia un messaggio
    </h2>

    {{-- Form messaggio classico --}}
    <form method="POST" action="{{ route('messages.store', $thread->id) }}" class="space-y-3">
        @csrf
        <textarea name="body" rows="3" placeholder="Scrivi il tuo messaggio..."
                  class="w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"></textarea>

        <div class="text-right">
            <x-primary-button>
                📩 Invia messaggio
            </x-primary-button>
        </div>
    </form>

        {{-- Form proposta colloquio --}}
        @if(Auth::user()->hasRole('recruiter') && empty(Auth::user()->company()) && empty(Auth::user()->company()->jobs))
            <hr class="border-gray-200">
            <div x-data="{ open: false }">
                <button @click="open = !open"
                    class="mt-2 text-sm text-indigo-600 hover:underline flex items-center gap-1">
                    📅 Proponi un colloquio
                </button>

                <div x-show="open" class="mt-4 border border-gray-100 p-4 rounded-md bg-gray-50 space-y-4">
                    <form action="{{ route('interviews.propose', $thread->id) }}" method="POST" class="space-y-4">
                        @csrf

                        <div>
                            <label for="job_offert_id" class="block text-sm font-medium text-gray-700">Offerta di lavoro</label>
                            <select name="job_offert_id" id="job_offert_id" required
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @foreach(Auth::user()->company->jobs as $job)
                                    <option value="{{ $job->id }}">{{ $job->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="datetime" class="block text-sm font-medium text-gray-700">Data e ora</label>
                            <input type="datetime-local" name="datetime" required
                                class="w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
                        </div>

                        <div class="text-right">
                            <button type="submit"
                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md transition">
                                ✅ Invia proposta
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
</div>
