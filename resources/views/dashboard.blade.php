<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-medium text-indigo-600">Ruang kerja</p>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">Dashboard</h1>
        </div>
    </x-slot>

    @php($role = auth()->user()->getRoleNames()->first())

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <section class="rounded-2xl bg-gradient-to-br from-slate-900 to-indigo-900 px-6 py-8 text-white shadow-sm sm:px-8">
            <p class="text-sm font-medium text-indigo-200">Selamat datang kembali</p>
            <h2 class="mt-2 text-2xl font-bold">{{ auth()->user()->name }}</h2>
            <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-200">
                Kelola pengaduan dan informasi sesuai akses akun Anda.
            </p>
            <div class="mt-5 inline-flex rounded-full bg-white/10 px-3 py-1 text-sm font-medium text-white">
                Role: {{ $role ? ucfirst($role) : 'Belum ditentukan' }}
            </div>
        </section>

        <section class="mt-8">
            <div class="mb-4">
                <h2 class="text-lg font-semibold text-slate-900">Menu utama</h2>
                <p class="mt-1 text-sm text-slate-500">Pilih bagian yang ingin Anda kelola.</p>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @can('view pengaduan')
                    <a href="{{ route('pengaduans.index') }}"
                       class="group rounded-xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-indigo-300 hover:shadow-md">
                        <h3 class="font-semibold text-slate-900 group-hover:text-indigo-700">Pengaduan</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">Lihat dan tindak lanjuti data pengaduan.</p>
                        <span class="mt-4 inline-block text-sm font-semibold text-indigo-700">Buka pengaduan</span>
                    </a>
                @endcan

                @can('manage categories')
                    <a href="{{ route('categories.index') }}"
                       class="group rounded-xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-indigo-300 hover:shadow-md">
                        <h3 class="font-semibold text-slate-900 group-hover:text-indigo-700">Kategori</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">Atur kategori yang digunakan pada pengaduan.</p>
                        <span class="mt-4 inline-block text-sm font-semibold text-indigo-700">Kelola kategori</span>
                    </a>
                @endcan

                @can('manage users')
                    <a href="{{ route('users.index') }}"
                       class="group rounded-xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-indigo-300 hover:shadow-md">
                        <h3 class="font-semibold text-slate-900 group-hover:text-indigo-700">Kelola user</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">Kelola akun dan hak akses pengguna.</p>
                        <span class="mt-4 inline-block text-sm font-semibold text-indigo-700">Buka daftar user</span>
                    </a>
                @endcan
            </div>
        </section>
    </div>
</x-app-layout>
