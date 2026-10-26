<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm font-medium text-indigo-600">Layanan warga</p>
                <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">Pengaduan</h1>
                <p class="mt-1 text-sm text-slate-500">Daftar pengaduan dan informasi tindak lanjut.</p>
            </div>
            @can('create pengaduan')
                <a href="{{ route('pengaduans.create') }}"
                   class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Buat pengaduan
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        @if (session('success'))
            <div class="mb-5 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        @if ($pengaduans->isEmpty())
            <section class="rounded-xl border border-slate-200 bg-white px-6 py-14 text-center shadow-sm">
                <h2 class="text-lg font-semibold text-slate-900">Belum ada pengaduan</h2>
                <p class="mt-2 text-sm text-slate-500">
                    @can('create pengaduan')
                        Buat pengaduan pertama Anda melalui tombol di atas.
                    @else
                        Pengaduan yang tersedia akan ditampilkan di sini.
                    @endcan
                </p>
            </section>
        @else
            <div class="mb-4 flex items-center justify-between">
                <p class="text-sm text-slate-500">{{ $pengaduans->count() }} pengaduan</p>
            </div>

            <div class="space-y-4">
                @foreach ($pengaduans as $pengaduan)
                    <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <h2 class="text-lg font-semibold text-slate-900">{{ $pengaduan->title }}</h2>
                                    <span class="rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-800">
                                        {{ $pengaduan->status }}
                                    </span>
                                </div>
                                <p class="mt-2 text-sm text-slate-600">
                                    Kategori: <span class="font-medium text-slate-800">{{ $pengaduan->category->name }}</span>
                                </p>
                                <p class="mt-1 text-sm text-slate-600">
                                    Pelapor: <span class="font-medium text-slate-800">{{ $pengaduan->user->name }}</span>
                                    <span class="text-slate-400">({{ $pengaduan->user->email }})</span>
                                </p>
                                <p class="mt-1 text-sm text-slate-600">
                                    Lokasi: <span class="font-medium text-slate-800">{{ $pengaduan->location ?: 'Belum diisi' }}</span>
                                </p>
                            </div>
                            <p class="shrink-0 text-xs text-slate-500">
                                {{ $pengaduan->created_at->format('d M Y, H:i') }}
                            </p>
                        </div>

                        <div class="mt-4 border-t border-slate-100 pt-4">
                            <p class="whitespace-pre-line text-sm leading-6 text-slate-700">{{ $pengaduan->description }}</p>
                        </div>

                        <div class="mt-5 flex flex-wrap items-center gap-2 border-t border-slate-100 pt-4">
                            <a href="{{ route('pengaduans.files', $pengaduan) }}"
                               class="rounded-lg border border-slate-300 px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                                Lihat lampiran
                            </a>

                            @can('edit pengaduan')
                                <a href="{{ route('pengaduans.edit', $pengaduan) }}"
                                   class="rounded-lg border border-indigo-200 px-3 py-2 text-sm font-medium text-indigo-700 hover:bg-indigo-50">
                                    Edit
                                </a>
                            @endcan

                            @can('delete pengaduan')
                                <form method="POST" action="{{ route('pengaduans.destroy', $pengaduan) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="rounded-lg border border-rose-200 px-3 py-2 text-sm font-medium text-rose-700 hover:bg-rose-50">
                                        Hapus
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
