<nav class="border-b border-slate-200 bg-white">
    <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
        <a href="{{ route('dashboard') }}" class="text-lg font-bold tracking-tight text-slate-900">
            Portal Pengaduan
        </a>

        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('dashboard') }}"
               class="{{ request()->routeIs('dashboard') ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }} rounded-lg px-3 py-2 text-sm font-medium transition">
                Dashboard
            </a>

            @can('manage users')
                <a href="{{ route('users.index') }}"
                   class="{{ request()->routeIs('users.*') ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }} rounded-lg px-3 py-2 text-sm font-medium transition">
                    Kelola user
                </a>
            @endcan

            @can('manage categories')
                <a href="{{ route('categories.index') }}"
                   class="{{ request()->routeIs('categories.*') ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }} rounded-lg px-3 py-2 text-sm font-medium transition">
                    Kategori
                </a>
            @endcan

            @can('view pengaduan')
                <a href="{{ route('pengaduans.index') }}"
                   class="{{ request()->routeIs('pengaduans.*') ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }} rounded-lg px-3 py-2 text-sm font-medium transition">
                    Pengaduan
                </a>
            @endcan
        </div>

        <div class="flex items-center gap-3 border-t border-slate-100 pt-3 sm:border-0 sm:pt-0">
            <div class="text-right">
                <p class="text-sm font-semibold text-slate-800">{{ Auth::user()->name }}</p>
                <p class="text-xs text-slate-500">{{ Auth::user()->getRoleNames()->first() ?? 'Tanpa role' }}</p>
            </div>
            <a href="{{ route('profile.edit') }}"
               class="rounded-lg px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100 hover:text-slate-900">
                Profil
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="rounded-lg border border-slate-300 px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                    Keluar
                </button>
            </form>
        </div>
    </div>
</nav>
