<x-app-layout>
    <div class="max-w-5xl mx-auto p-6 mt-10 bg-white rounded-lg shadow">
        <h1 class="text-3xl font-bold mb-6">Offerte Salvate</h1>

        @if ($jobs->isEmpty())
            <p class="text-gray-600">Non hai ancora salvato nessuna offerta.</p>
        @else
            <ul class="space-y-4">
                @foreach ($jobs as $job)
                    <li class="border p-4 rounded hover:shadow transition">
                        <a href="{{ route('jobs.publicShow', $job) }}" class="text-xl font-semibold text-blue-600 hover:underline">
                            {{ $job->title }}
                        </a>
                        <p class="text-gray-500">{{ $job->company->name }} — {{ $job->location }}</p>
                        <p class="mt-2 text-gray-700">{{ Str::limit($job->description, 150) }}</p>
                    </li>
                @endforeach
            </ul>

            <div class="mt-6">
                {{ $jobs->links() }}
            </div>
        @endif

        <div class="mt-6">
            <a href="{{ route('jobs.publicIndex') }}" class="text-blue-600 hover:underline">
                ← Torna alle offerte
            </a>
        </div>
    </div>
</x-app-layout>
