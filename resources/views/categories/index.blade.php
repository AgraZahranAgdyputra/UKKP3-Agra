<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm font-medium text-indigo-600">Pengaturan data</p>
                <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">Kategori</h1>
                <p class="mt-1 text-sm text-slate-500">Kelola kategori untuk pengelompokan pengaduan.</p>
            </div>
            <a href="{{ route('categories.create') }}"
               class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                Tambah kategori
            </a>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        @if (session('success'))
            <div class="mb-5 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-5 py-4">
                <h2 class="font-semibold text-slate-900">Daftar kategori</h2>
                <p class="mt-1 text-sm text-slate-500">{{ $categories->count() }} kategori terdaftar</p>
            </div>

            @if ($categories->isEmpty())
                <div class="px-5 py-12 text-center">
                    <h3 class="font-semibold text-slate-900">Belum ada kategori</h3>
                    <p class="mt-1 text-sm text-slate-500">Tambahkan kategori pertama agar pengaduan dapat dikelompokkan.</p>
                    <a href="{{ route('categories.create') }}"
                       class="mt-4 inline-flex rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                        Tambah kategori
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[520px] divide-y divide-slate-200 text-left text-sm">
                        <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                            <tr>
                                <th class="px-5 py-3 font-semibold">ID</th>
                                <th class="px-5 py-3 font-semibold">Nama kategori</th>
                                <th class="px-5 py-3 text-right font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($categories as $category)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-5 py-4 text-slate-500">{{ $category->id }}</td>
                                    <td class="px-5 py-4 font-medium text-slate-900">{{ $category->name }}</td>
                                    <td class="px-5 py-4">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('categories.edit', $category) }}"
                                               class="rounded-md border border-slate-300 px-3 py-1.5 text-sm font-medium text-slate-700 hover:bg-slate-100">
                                                Edit
                                            </a>
                                            <form method="POST" action="{{ route('categories.destroy', $category) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="rounded-md border border-rose-200 px-3 py-1.5 text-sm font-medium text-rose-700 hover:bg-rose-50">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>
    </div>
</x-app-layout>
