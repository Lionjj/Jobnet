<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <div class="flex items-center gap-10">
                <!-- Logo -->
                <div class="flex items-center gap-2">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                    <span class="text-lg font-semibold text-slate-800">Jobnet</span>
                </div>

                <!-- Navigation Links -->
                <div class="hidden sm:flex space-x-8">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    <x-nav-link :href="route('chat.redirect')" :active="request()->routeIs('chat.*') || request()->routeIs('messages.*')">
                        {{ __('Chat') }}
                    </x-nav-link>

                    @if (Auth::user()->hasRole('recruiter'))
                        <x-nav-link :href="route('companies.index')" :active="request()->routeIs('companies.*')">
                            {{ __('Company') }}
                        </x-nav-link>
                        <x-nav-link :href="route('jobs.index')" :active="request()->routeIs('jobs.*')">
                            {{ __('Jobs') }}
                        </x-nav-link>
                    @elseif(Auth::user()->hasRole('candidate'))
                        <x-nav-link :href="route('jobs.publicIndex')" :active="request()->routeIs('jobs.publicIndex')">
                            {{ __('Offerte di lavoro') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Right section: Notifiche + Profilo -->
            <div class="flex items-center gap-4">

                <!-- Profilo Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-500 hover:text-gray-700">
                            <div>{{ Auth::user()->name }}</div>
                            <svg class="ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
                
                <!-- Notifiche Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="relative text-gray-600 hover:text-gray-800">
                            <x-heroicon-o-bell class="w-5 h-5" />
                            @if(auth()->user()->unreadNotifications->count())
                                <span class="absolute -top-1 -right-1 bg-red-600 text-white text-xs rounded-full px-1.5 py-0.5">
                                    {{ auth()->user()->unreadNotifications->count() }}
                                </span>
                            @endif
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-2 font-semibold border-b text-gray-700">
                            Notifiche
                        </div>

                        @forelse(auth()->user()->unreadNotifications as $notification)
                            @php
                                $data = $notification->data;
                                $message = $data['message'] ?? '';
                                $title = $data['title'] ?? 'Notifica';

                                $type = match(true) {
                                    str_contains($message, 'accettata') => 'accepted',
                                    str_contains($message, 'rifiutata') => 'rejected',
                                    str_contains($message, 'conversazione') => 'conversation',
                                    str_contains($title, 'messaggio') || isset($data['thread_id']) => 'message',
                                    default => 'default',
                                };

                                $badgeColor = match($type) {
                                    'accepted' => 'bg-green-100 text-green-800',
                                    'rejected' => 'bg-red-100 text-red-800',
                                    'conversation' => 'bg-blue-100 text-blue-800',
                                    'message' => 'bg-indigo-100 text-indigo-800',
                                    default => 'bg-gray-100 text-gray-700',
                                };

                                $badgeText = match($type) {
                                    'accepted' => 'Accettata',
                                    'rejected' => 'Rifiutata',
                                    'conversation' => 'Nuova chat',
                                    'message' => 'Messaggio',
                                    default => 'Aggiornamento',
                                };
                            @endphp

                            <a href="{{ route('notifications.read', $notification->id) }}"
                            class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-100 transition">

                                <div class="flex justify-between items-center">
                                    <span class="font-semibold text-sm text-gray-800">{{ $title }}</span>
                                    <span class="text-xs text-gray-400">{{ $notification->created_at->diffForHumans() }}</span>
                                </div>

                                <div class="mt-1 text-sm text-gray-600">
                                    {{ $message }}
                                </div>

                                <div class="mt-2 inline-block px-2 py-0.5 rounded-full text-xs font-medium {{ $badgeColor }}">
                                    {{ $badgeText }}
                                </div>
                            </a>
                        @empty
                            <div class="px-4 py-2 text-sm text-gray-500">
                                Nessuna nuova notifica.
                            </div>
                        @endforelse
                    </x-slot>
                </x-dropdown>


                <!-- Mobile Hamburger -->
                <div class="sm:hidden">
                    <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('chat.redirect')" :active="request()->routeIs('chat.*') || request()->routeIs('messages.*')">
                {{ __('Chat') }}
            </x-responsive-nav-link>
            @if (Auth::user()->hasRole('recruiter'))
                <x-responsive-nav-link :href="route('companies.index')" :active="request()->routeIs('companies.*')">
                    {{ __('Company') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('jobs.index')" :active="request()->routeIs('jobs.*')">
                    {{ __('Jobs') }}
                </x-responsive-nav-link>
            @elseif(Auth::user()->hasRole('candidate'))
                <x-responsive-nav-link :href="route('jobs.publicIndex')" :active="request()->routeIs('jobs.publicIndex')">
                    {{ __('Offerte di lavoro') }}
                </x-responsive-nav-link>
            @endif
        </div>
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
