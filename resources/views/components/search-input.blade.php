<form method="GET" action="{{ $action }}" class="flex items-center gap-2 mb-4">
    <input
        type="text"
        name="search"
        value="{{ request('search') }}"
        placeholder="{{ $placeholder }}"
        class="border border-gray-300 rounded px-3 py-2 w-full text-sm"
    >

    <input type="hidden" name="tab" value="{{ $tab ?? request('tab', 'threads') }}">

    @if(request('search'))
        <a href="{{ $action }}?tab={{ request('tab') }}" class="text-sm text-blue-600 hover:underline">
            ✕
        </a>
    @endif
</form>
