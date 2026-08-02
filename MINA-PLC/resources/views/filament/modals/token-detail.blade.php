<div class="space-y-3 p-1" x-data="{ copied: false }">
    <!-- Notice / Security Warning -->
    <div class="flex items-center gap-2 rounded-lg bg-amber-50 p-3 text-xs text-amber-800 dark:bg-amber-950/40 dark:text-amber-300 border border-amber-200/60 dark:border-amber-900/50">
        <svg class="h-4 w-4 shrink-0 text-amber-600 dark:text-amber-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
        </svg>
        <span><strong>Warning:</strong> Do not share this token with anyone for security reasons!</span>
    </div>

    <!-- Token Box Container -->
    <div class="relative flex items-center justify-between gap-3 rounded-xl border border-gray-200 bg-gray-50/80 p-3.5 shadow-sm transition-all dark:border-gray-800 dark:bg-gray-900/80">
        <!-- Token String -->
        <code class="flex-1 break-all font-mono text-xs font-medium text-gray-800 dark:text-gray-200 select-all">
            {{ $token }}
        </code>

        <!-- Copy Button -->
        <button
            type="button"
            x-on:click="
                navigator.clipboard.writeText('{{ $token }}');
                copied = true;
                setTimeout(() => copied = false, 2500);
            "
            class="relative inline-flex shrink-0 items-center justify-center rounded-lg p-2 text-gray-500 transition-all hover:bg-gray-200/60 hover:text-gray-700 active:scale-95 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-gray-200"
            :class="copied ? '!text-emerald-600 dark:!text-emerald-400 !bg-emerald-50 dark:!bg-emerald-950/50' : ''"
            title="Copy to clipboard"
        >
            <!-- Copied Icon (Checkmark) -->
            <svg x-show="copied" x-cloak class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
            </svg>

            <!-- Default Copy Icon -->
            <svg x-show="!copied" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 0 1-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 0 1 1.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 0 0-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 0 1-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 0 0-3.375-3.375h-1.5" />
            </svg>

            <!-- Tooltip Feedback Badge -->
            <span 
                x-show="copied" 
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-y-1"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-cloak 
                class="absolute -top-8 right-0 rounded bg-gray-900 px-2 py-0.5 text-[10px] font-medium text-white shadow-md dark:bg-gray-100 dark:text-gray-900"
            >
                Copied!
            </span>
        </button>
    </div>
</div>