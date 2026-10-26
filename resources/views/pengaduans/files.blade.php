<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm font-medium text-indigo-600">Lampiran pengaduan</p>
                <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">{{ $pengaduan->title }}</h1>
            </div>
            <a href="{{ route('pengaduans.index') }}"
               class="text-sm font-semibold text-indigo-700 hover:text-indigo-900">
                Kembali ke daftar pengaduan
            </a>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        @if (empty($files))
            <section class="rounded-xl border border-slate-200 bg-white px-6 py-12 text-center shadow-sm">
                <h2 class="font-semibold text-slate-900">Belum ada lampiran</h2>
                <p class="mt-1 text-sm text-slate-500">Pengaduan ini belum memiliki file yang dilampirkan.</p>
            </section>
        @else
            <div class="mb-4">
                <h2 class="text-lg font-semibold text-slate-900">File terlampir</h2>
                <p class="mt-1 text-sm text-slate-500">{{ count($files) }} file</p>
            </div>

            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($files as $file)
                    @php($extension = strtolower(pathinfo($file, PATHINFO_EXTENSION)))
                    <article class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                        @if (in_array($extension, ['jpg', 'jpeg', 'png']))
                            <a href="{{ Storage::disk('public')->url($file) }}" target="_blank" class="block bg-slate-100">
                                <img src="{{ Storage::disk('public')->url($file) }}" alt="Lampiran pengaduan"
                                     class="h-64 w-full object-contain">
                            </a>
                        @else
                            <div class="flex h-40 items-center justify-center bg-slate-50 px-4 text-center">
                                <p class="text-sm font-medium text-slate-500">Dokumen {{ strtoupper($extension) }}</p>
                            </div>
                        @endif
                        <div class="flex items-center justify-between gap-3 p-4">
                            <p class="min-w-0 truncate text-sm font-medium text-slate-800">{{ basename($file) }}</p>
                            <a href="{{ Storage::disk('public')->url($file) }}" target="_blank"
                               class="shrink-0 rounded-md border border-slate-300 px-3 py-1.5 text-sm font-medium text-slate-700 hover:bg-slate-50">
                                Buka
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
