<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-medium text-indigo-600">Administrasi</p>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">
                {{ $user->exists ? 'Edit user' : 'Tambah user' }}
            </h1>
        </div>
    </x-slot>

    <div class="mx-auto max-w-2xl px-4 py-8 sm:px-6 lg:px-8">
        <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            <p class="mb-6 text-sm leading-6 text-slate-600">
                Isi data akun dan pilih satu role. Pastikan role sesuai tanggung jawab pengguna.
            </p>

            @if ($errors->any())
                <div class="mb-6 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
                    <p class="font-semibold">User belum dapat disimpan. Periksa isian berikut:</p>
                    <ul class="mt-2 list-inside list-disc space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST"
                  action="{{ $user->exists ? route('users.update', $user) : route('users.store') }}"
                  class="space-y-5">
                @csrf
                @if ($user->exists)
                    @method('PUT')
                @endif

                <div>
                    <label for="name" class="mb-1.5 block text-sm font-medium text-slate-700">Nama</label>
                    <input id="name" name="name" value="{{ old('name', $user->name) }}" required
                           autocomplete="name"
                           class="block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label for="email" class="mb-1.5 block text-sm font-medium text-slate-700">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required
                           autocomplete="email"
                           class="block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label for="role" class="mb-1.5 block text-sm font-medium text-slate-700">Role</label>
                    <select id="role" name="role" required
                            class="block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Pilih role</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}"
                                @selected(old('role', $user->roles->first()?->name) === $role->name)>
                                {{ ucfirst($role->name) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="password" class="mb-1.5 block text-sm font-medium text-slate-700">
                            Password {{ $user->exists ? '(opsional)' : '' }}
                        </label>
                        <input id="password" type="password" name="password"
                               {{ $user->exists ? '' : 'required' }}
                               autocomplete="new-password"
                               class="block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @if ($user->exists)
                            <p class="mt-1.5 text-xs text-slate-500">Kosongkan jika tidak ingin mengganti password.</p>
                        @endif
                    </div>

                    <div>
                        <label for="password_confirmation" class="mb-1.5 block text-sm font-medium text-slate-700">
                            Konfirmasi password
                        </label>
                        <input id="password_confirmation" type="password" name="password_confirmation"
                               {{ $user->exists ? '' : 'required' }}
                               autocomplete="new-password"
                               class="block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>

                <div class="flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end">
                    <a href="{{ route('users.index') }}"
                       class="inline-flex justify-center rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                        Batal
                    </a>
                    <button type="submit"
                            class="inline-flex justify-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        {{ $user->exists ? 'Simpan perubahan' : 'Buat user' }}
                    </button>
                </div>
            </form>
        </section>
    </div>
</x-app-layout>
