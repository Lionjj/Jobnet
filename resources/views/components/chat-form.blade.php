<form action="{{ route('messages.store', $thread->id) }}" method="POST" class="space-y-4">
    @csrf
    <textarea
        name="body"
        rows="3"
        placeholder="Scrivi il tuo messaggio..."
        class="w-full rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 resize-none"
        required
    ></textarea>

    <div class="flex justify-end">
        <x-primary-button class="ms-3">
            {{ __('Submit') }}
        </x-primary-button>
    </div>
</form>
