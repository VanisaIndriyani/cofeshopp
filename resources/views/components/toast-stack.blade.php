<div class="pointer-events-none fixed inset-x-0 top-4 z-50 flex justify-center px-4">
    <div class="w-full max-w-md space-y-2">
        <template x-for="t in $store.toast.items" :key="t.id">
            <div
                class="pointer-events-auto rounded-2xl border border-gray-200 bg-white/90 p-4 text-gray-900 shadow-xl backdrop-blur transition dark:border-white/10 dark:bg-gray-950/80 dark:text-white"
                x-transition.opacity.duration.200ms
            >
                <div class="flex items-start gap-3">
                    <div class="mt-0.5">
                        <template x-if="t.type === 'success'">
                            <x-heroicon-o-check-circle class="h-5 w-5 text-emerald-600 dark:text-emerald-300" />
                        </template>
                        <template x-if="t.type === 'error'">
                            <x-heroicon-o-x-circle class="h-5 w-5 text-rose-600 dark:text-rose-300" />
                        </template>
                        <template x-if="t.type === 'info'">
                            <x-heroicon-o-information-circle class="h-5 w-5 text-sky-600 dark:text-sky-300" />
                        </template>
                        <template x-if="t.type === 'warning'">
                            <x-heroicon-o-exclamation-triangle class="h-5 w-5 text-amber-600 dark:text-amber-300" />
                        </template>
                    </div>
                    <div class="min-w-0 flex-1 text-sm leading-5" x-text="t.message"></div>
                    <button type="button" class="-m-1 rounded-lg p-1 text-gray-500 transition hover:text-gray-900 dark:text-white/70 dark:hover:text-white" @click="$store.toast.remove(t.id)">
                        <x-heroicon-o-x-mark class="h-4 w-4" />
                    </button>
                </div>
            </div>
        </template>
    </div>
</div>
