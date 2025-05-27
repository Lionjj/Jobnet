@props(['users'])

<div class="max-w-2xl mx-auto p-6">
    <h1 class="text-xl font-bold mb-4">Seleziona un candidato</h1>

    <ul class="divide-y divide-gray-200 bg-white shadow rounded">
        @foreach ($users as $user)
            <li>
                <a href="{{ route('chat.avvia', $user->id) }}" class="block px-4 py-3 hover:bg-gray-100">
                    {{ $user->name }}
                </a>
            </li>
        @endforeach
    </ul>

    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>
