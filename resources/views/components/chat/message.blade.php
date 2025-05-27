@props(['message'])

<div class="p-3 rounded {{ $message->user_id === auth()->id() ? 'bg-blue-100 text-right' : 'bg-gray-100 text-left' }}">
    <div class="text-sm text-gray-600">
        {{ $message->user->name }} – {{ $message->created_at->diffForHumans() }}
    </div>
    <div class="mt-1">{{ $message->body }}</div>
</div>
