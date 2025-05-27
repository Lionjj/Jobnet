@props(['messages'])

<div class="space-y-3 mb-6">
    @foreach ($messages as $message)
        <x-chat.message :message="$message" />
    @endforeach
</div>
