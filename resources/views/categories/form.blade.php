<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-medium text-indigo-600">Pengaturan data</p>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">
                {{ $category->exists ? 'Edit kategori' : 'Tambah kategori' }}
            </h1>
        </div>
    </x-slot>

    <div class="mx-auto max-w-2xl px-4 py-8 sm:px-6 lg:px-8">
        <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            <p class="mb-6 text-sm leading-6 text-slate-600">
                {{ $category->exists ? 'Perbarui nama kategori di bawah ini.' : 'Isi nama untuk membuat kategori baru.' }}
            </p>

            @if ($errors->any())
                <div class="mb-5 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
                    <p class="font-semibold">Periksa kembali isian berikut:</p>
                    <ul class="mt-2 list-inside list-disc space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST"
                  action="{{ $category->exists ? route('categories.update', $category) : route('categories.store') }}"
                  class="space-y-5">
                @csrf
                @if ($category->exists)
                    @method('PUT')
                @endif

                <div>
                    <label for="name" class="mb-1.5 block text-sm font-medium text-slate-700">Nama kategori</label>
                    <input id="name" name="name" value="{{ old('name', $category->name) }}" required
                           class="block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div class="flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end">
                    <a href="{{ route('categories.index') }}"
                       class="inline-flex justify-center rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                        Batal
                    </a>
                    <button type="submit"
                            class="inline-flex justify-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Simpan kategori
                    </button>
                </div>
            </form>
        </section>
    </div>
</x-app-layout>
