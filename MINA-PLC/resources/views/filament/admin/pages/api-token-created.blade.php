<x-filament-panels::page>

    <div class="space-y-6">

        <div>
            <h2 class="text-2xl font-bold">
                API Token Created
            </h2>

            <p class="text-sm text-gray-500">
                Save this token now.
                It will never be shown again.
            </p>
        </div>

        <div
            x-data="{ copied: false }"
            class="space-y-4"
        >
            <input
                id="generated-token"
                type="text"
                readonly
                value="{{ $token }}"
                class="w-full rounded-lg border border-gray-300"
            >

            <button
                type="button"
                class="fi-btn fi-btn-size-md fi-btn-color-primary"
                x-on:click="
                    navigator.clipboard.writeText(
                        document.getElementById('generated-token').value
                    );
                    copied = true;
                "
            >
                Copy Token
            </button>

            <p
                x-show="copied"
                class="text-success-600"
            >
                Token copied successfully
            </p>
        </div>

    </div>

</x-filament-panels::page>