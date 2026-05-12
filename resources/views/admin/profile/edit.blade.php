<x-layouts.admin title="Edit Profil" header="Edit Profil" subtitle="Kelola informasi profil dan keamanan akun Anda">
    <div class="space-y-6">
        <div class="grid gap-6 lg:grid-cols-2">
            {{-- Profile Information --}}
            <div class="rounded-3xl border border-black/5 bg-white/70 p-6 shadow-sm backdrop-blur dark:border-white/10 dark:bg-gray-950/40">
                <div class="mb-6">
                    <h3 class="text-lg font-bold text-gray-950 dark:text-white">Informasi Profil</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Perbarui informasi profil akun dan alamat email Anda.
                    </p>
                </div>

                <form method="post" action="{{ route('admin.profile.update') }}" class="space-y-4">
                    @csrf
                    @method('patch')

                    <div>
                        <x-input-label for="name" :value="__('Nama')" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>

                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
                        <x-input-error class="mt-2" :messages="$errors->get('email')" />
                    </div>

                    <div class="flex items-center gap-4 pt-2">
                        <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-gradient-to-r from-red-600 to-red-700 px-6 py-2.5 text-sm font-bold text-white shadow-lg shadow-red-600/20 transition hover:from-red-700 hover:to-red-800 active:scale-[0.98]">
                            Simpan Perubahan
                        </button>

                        @if (session('status') === 'profile-updated')
                            <p
                                x-data="{ show: true }"
                                x-show="show"
                                x-transition
                                x-init="setTimeout(() => show = false, 2000)"
                                class="text-sm text-green-600 dark:text-green-400"
                            >Berhasil disimpan.</p>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Update Password --}}
            <div class="rounded-3xl border border-black/5 bg-white/70 p-6 shadow-sm backdrop-blur dark:border-white/10 dark:bg-gray-950/40">
                <div class="mb-6">
                    <h3 class="text-lg font-bold text-gray-950 dark:text-white">Perbarui Kata Sandi</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Pastikan akun Anda menggunakan kata sandi yang panjang dan acak untuk tetap aman.
                    </p>
                </div>

                <form method="post" action="{{ route('password.update') }}" class="space-y-4">
                    @csrf
                    @method('put')

                    <div>
                        <x-input-label for="update_password_current_password" :value="__('Kata Sandi Saat Ini')" />
                        <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" />
                        <x-input-error class="mt-2" :messages="$errors->updatePassword->get('current_password')" />
                    </div>

                    <div>
                        <x-input-label for="update_password_password" :value="__('Kata Sandi Baru')" />
                        <x-text-input id="update_password_password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                        <x-input-error class="mt-2" :messages="$errors->updatePassword->get('password')" />
                    </div>

                    <div>
                        <x-input-label for="update_password_password_confirmation" :value="__('Konfirmasi Kata Sandi')" />
                        <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                        <x-input-error class="mt-2" :messages="$errors->updatePassword->get('password_confirmation')" />
                    </div>

                    <div class="flex items-center gap-4 pt-2">
                        <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-gradient-to-r from-yellow-500 to-yellow-600 px-6 py-2.5 text-sm font-bold text-white shadow-lg shadow-yellow-500/20 transition hover:from-yellow-600 hover:to-yellow-700 active:scale-[0.98]">
                            Perbarui Kata Sandi
                        </button>

                        @if (session('status') === 'password-updated')
                            <p
                                x-data="{ show: true }"
                                x-show="show"
                                x-transition
                                x-init="setTimeout(() => show = false, 2000)"
                                class="text-sm text-green-600 dark:text-green-400"
                            >Berhasil diperbarui.</p>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.admin>
