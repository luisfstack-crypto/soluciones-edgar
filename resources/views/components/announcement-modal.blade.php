<div
    x-data="{ open: !sessionStorage.getItem('aviso_enterado') }"
    x-show="open"
    x-cloak
    x-on:keydown.escape.window="open = false; sessionStorage.setItem('aviso_enterado', 'true')"
    class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto p-4 sm:p-6"
    role="dialog"
    aria-modal="true"
    aria-labelledby="announcement-modal-title"
>
    <div class="fixed inset-0 bg-gray-950/40 backdrop-blur-sm" aria-hidden="true"></div>

    <div class="relative w-full max-w-lg overflow-hidden rounded-xl bg-white shadow-2xl ring-1 ring-gray-950/10 dark:bg-gray-900 dark:ring-white/10">
        <div class="p-6 sm:p-8">
            <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-full bg-primary-50 text-primary-600 dark:bg-primary-400/10 dark:text-primary-400">
                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.34 3.94a1.5 1.5 0 0 1 2.32 0l1.18 1.47c.2.25.49.4.81.43l1.87.17a1.5 1.5 0 0 1 1.64 1.64l.17 1.87c.03.32.18.61.43.81l1.47 1.18a1.5 1.5 0 0 1 0 2.32l-1.47 1.18c-.25.2-.4.49-.43.81l-.17 1.87a1.5 1.5 0 0 1-1.64 1.64l-1.87.17c-.32.03-.61.18-.81.43l-1.18 1.47a1.5 1.5 0 0 1-2.32 0l-1.18-1.47a1.2 1.2 0 0 0-.81-.43l-1.87-.17a1.5 1.5 0 0 1-1.64-1.64l-.17-1.87a1.2 1.2 0 0 0-.43-.81l-1.47-1.18a1.5 1.5 0 0 1 0-2.32l1.47-1.18c.25-.2.4-.49.43-.81l.17-1.87a1.5 1.5 0 0 1 1.64-1.64l1.87-.17c.32-.03.61-.18.81-.43l1.18-1.47Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="m9 12 2 2 4-4" />
                </svg>
            </div>

            <h2 id="announcement-modal-title" class="text-xl font-semibold text-gray-950 dark:text-white">
                Aviso Importante
            </h2>
            <p class="mt-3 text-sm leading-6 text-gray-600 dark:text-gray-300">
                Aquí se mostrará el aviso importante para nuestros usuarios. Mantente al tanto de las novedades y actualizaciones del servicio.
            </p>

            <div class="mt-7 flex justify-end">
                <button
                    type="button"
                    x-on:click="open = false; sessionStorage.setItem('aviso_enterado', 'true')"
                    class="inline-flex items-center justify-center rounded-lg bg-primary-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-600 focus:ring-offset-2 dark:focus:ring-offset-gray-900"
                >
                    Enterado
                </button>
            </div>
        </div>
    </div>
</div>