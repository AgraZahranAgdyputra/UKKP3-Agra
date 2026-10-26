<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-medium text-indigo-600">Layanan warga</p>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">
                {{ $pengaduan->exists ? 'Edit pengaduan' : 'Buat pengaduan' }}
            </h1>
        </div>
    </x-slot>

    <div class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
        <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            <p class="mb-6 text-sm leading-6 text-slate-600">
                Isi informasi pengaduan dengan jelas. Lampiran bersifat opsional dan dapat memilih beberapa file sekaligus.
            </p>

            @if ($errors->any())
                <div class="mb-6 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
                    <p class="font-semibold">Pengaduan belum dapat disimpan. Periksa isian berikut:</p>
                    <ul class="mt-2 list-inside list-disc space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST"
                  enctype="multipart/form-data"
                  action="{{ $pengaduan->exists ? route('pengaduans.update', $pengaduan) : route('pengaduans.store') }}"
                  class="space-y-5">
                @csrf
                @if ($pengaduan->exists)
                    @method('PUT')
                @endif

                <div>
                    <label for="category_id" class="mb-1.5 block text-sm font-medium text-slate-700">Kategori</label>
                    <select id="category_id" name="category_id" required
                            class="block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Pilih kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                @selected((string) old('category_id', $pengaduan->category_id) === (string) $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="title" class="mb-1.5 block text-sm font-medium text-slate-700">Judul pengaduan</label>
                    <input id="title" name="title" value="{{ old('title', $pengaduan->title) }}" required
                           class="block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label for="description" class="mb-1.5 block text-sm font-medium text-slate-700">Isi pengaduan</label>
                    <textarea id="description" name="description" rows="6" required
                              class="block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $pengaduan->description) }}</textarea>
                </div>

                <div>
                    <label for="location" class="mb-1.5 block text-sm font-medium text-slate-700">Lokasi</label>
                    <input id="location" name="location" value="{{ old('location', $pengaduan->location) }}"
                           class="block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label for="upload_file" class="mb-1.5 block text-sm font-medium text-slate-700">Lampiran</label>
                    @if ($pengaduan->exists)
                        <p class="mb-2 text-sm text-slate-600">
                            Lampiran yang sudah tersimpan:
                            <a href="{{ route('pengaduans.files', $pengaduan) }}" class="font-medium text-indigo-700 underline hover:text-indigo-900">
                                lihat lampiran
                            </a>
                        </p>
                    @endif
                    <input id="upload_file" type="file" name="upload_file[]" multiple accept=".pdf,.jpg,.jpeg,.png"
                           class="block w-full rounded-lg border border-slate-300 text-sm text-slate-700 file:mr-4 file:rounded-md file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-indigo-700 hover:file:bg-indigo-100">
                    <p class="mt-1.5 text-xs text-slate-500">Format: PDF, JPG, JPEG, PNG. Maksimal 10 file, 5 MB per file.</p>
                </div>

                <div class="flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end">
                    <a href="{{ route('pengaduans.index') }}"
                       class="inline-flex justify-center rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                        Batal
                    </a>
                    <button type="submit"
                            class="inline-flex justify-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        {{ $pengaduan->exists ? 'Simpan perubahan' : 'Kirim pengaduan' }}
                    </button>
                </div>
            </form>
        </section>
    </div>
</x-app-layout>
