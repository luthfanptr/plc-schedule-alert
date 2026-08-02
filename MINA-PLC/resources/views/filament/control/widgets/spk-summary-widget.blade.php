<x-filament-widgets::widget>
    <div class="p-4 ring-1 ring-gray-200 dark:ring-gray-700 rounded-xl">
        <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">SPK Overview</h3>
        <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">{{ $rangeLabel }}</p>

        <div class="flex items-center gap-6">
            {{-- Kiri: Stat --}}
            <div class="flex-1 space-y-4">
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Total SPK</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalSpk }}</p>
                </div>

                <div class="flex gap-4">
                    <div>
                        <div class="flex items-center gap-1.5 mb-0.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-green-500 inline-block"></span>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Done</p>
                        </div>
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-green-100 dark:bg-green-900/30 text-gray-900 dark:text-white font-bold text-xl">
                            {{ $doneCount }}
                            <span class="text-xs font-normal text-gray-500">({{ $donePercent }}%)</span>
                        </span>
                    </div>
                    <div>
                        <div class="flex items-center gap-1.5 mb-0.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-red-500 inline-block"></span>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Delay</p>
                        </div>
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-red-100 dark:bg-red-900/30 text-gray-900 dark:text-white font-bold text-xl">
                            {{ $delayCount }}
                            <span class="text-xs font-normal text-gray-500">({{ $delayPercent }}%)</span>
                        </span>
                    </div>
                </div>

                <div class="border-t border-gray-200 dark:border-gray-700 pt-3 space-y-1.5">
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-500 dark:text-gray-400">Avg Resolution</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ $avgResolution }}</span>
                    </div>
                    @if($longestDelay)
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-500 dark:text-gray-400">Longest Delay</span>
                        <span class="font-medium text-red-600 dark:text-red-400">{{ $longestDelayFormatted }}</span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Kanan: Donut Chart --}}
            <div class="relative w-32 h-32 flex-shrink-0">
                <canvas
                    x-data="{
                        async init() {
                            if (typeof Chart === 'undefined') {
                                await new Promise((resolve) => {
                                    const script = document.createElement('script');
                                    script.src = 'https://cdn.jsdelivr.net/npm/chart.js';
                                    script.onload = resolve;
                                    document.head.appendChild(script);
                                });
                            }
                            new Chart(this.$refs.canvas, {
                                type: 'doughnut',
                                data: {
                                    datasets: [{
                                        data: [{{ $doneCount }}, {{ $delayCount }}],
                                        backgroundColor: ['#22c55e', '#ef4444'],
                                        borderWidth: 0,
                                        hoverOffset: 4,
                                    }],
                                },
                                options: {
                                    cutout: '75%',
                                    plugins: { legend: { display: false }, tooltip: { enabled: true } },
                                },
                            });
                        }
                    }"
                    x-ref="canvas"
                    x-init="init()"
                ></canvas>
                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                    <span class="text-xl font-bold text-gray-900 dark:text-white">{{ $totalSpk }}</span>
                    <span class="text-xs text-gray-500 dark:text-gray-400">SPK Total</span>
                </div>
            </div>
        </div>

        {{-- Tombol Export --}}
        <div class="mt-4 flex justify-end gap-2">
            <x-filament::button
                wire:click="exportExcel"
                icon="heroicon-o-table-cells"
                color="success"
                size="sm"
            >
                Export Excel
            </x-filament::button>

            {{-- <x-filament::button
                onclick="printSpkReport()"
                icon="heroicon-o-printer"
                color="gray"
                size="sm"
            >
                Preview & Print PDF
            </x-filament::button> --}}
        </div>
    </div>

    {{-- Print Area --}}
    <div id="spk-print-area" style="display:none;">
        <div style="padding: 24px; font-family: sans-serif;">
            <h2 style="margin-bottom: 4px; font-size: 18px;">SPK Report</h2>
            <p style="color: #666; font-size: 13px; margin-bottom: 8px;">{{ now()->translatedFormat('F Y') }}</p>

            <div style="display: flex; gap: 24px; margin-bottom: 16px; font-size: 13px;">
                <span>Total SPK: <strong>{{ $totalSpk }}</strong></span>
                <span style="color: #16a34a;">Done: <strong>{{ $doneCount }}</strong></span>
                <span style="color: #dc2626;">Delay: <strong>{{ $delayCount }}</strong></span>
                <span>Avg Resolution: <strong>{{ $avgResolution }}</strong></span>
                @if($longestDelay)
                <span>Longest Delay: <strong>{{ $longestDelayFormatted }}</strong></span>
                @endif
            </div>

            <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
                <thead>
                    <tr style="background: #f3f4f6;">
                        <th style="border: 1px solid #e5e7eb; padding: 8px; text-align: left;">SPK Number</th>
                        <th style="border: 1px solid #e5e7eb; padding: 8px; text-align: left;">PLC ID</th>
                        <th style="border: 1px solid #e5e7eb; padding: 8px; text-align: left;">Line</th>
                        <th style="border: 1px solid #e5e7eb; padding: 8px; text-align: left;">Line Name</th>
                        <th style="border: 1px solid #e5e7eb; padding: 8px; text-align: left;">Component</th>
                        <th style="border: 1px solid #e5e7eb; padding: 8px; text-align: left;">Status</th>
                        <th style="border: 1px solid #e5e7eb; padding: 8px; text-align: left;">Teknisi</th>
                        <th style="border: 1px solid #e5e7eb; padding: 8px; text-align: left;">Start Date</th>
                        <th style="border: 1px solid #e5e7eb; padding: 8px; text-align: left;">Finish Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($printData as $row)
                    <tr>
                        <td style="border: 1px solid #e5e7eb; padding: 8px;">{{ $row->spk_number }}</td>
                        <td style="border: 1px solid #e5e7eb; padding: 8px;">{{ $row->plc_id }}</td>
                        <td style="border: 1px solid #e5e7eb; padding: 8px;">{{ $row->line }}</td>
                        <td style="border: 1px solid #e5e7eb; padding: 8px;">{{ trim($row->line_name) }}</td>
                        <td style="border: 1px solid #e5e7eb; padding: 8px;">{{ trim($row->component_name) }}</td>
                        <td style="border: 1px solid #e5e7eb; padding: 8px;">{{ $row->status }}</td>
                        <td style="border: 1px solid #e5e7eb; padding: 8px;">{{ $row->teknisi }}</td>
                        <td style="border: 1px solid #e5e7eb; padding: 8px;">
                            {{ $row->spk_start_date ? \Carbon\Carbon::parse($row->spk_start_date)->format('d M Y H:i') : '-' }}
                        </td>
                        <td style="border: 1px solid #e5e7eb; padding: 8px;">
                            {{ $row->spk_finish_date ? \Carbon\Carbon::parse($row->spk_finish_date)->format('d M Y H:i') : '-' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- <script>
    function printSpkReport() {
        const content = document.getElementById('spk-print-area').innerHTML;
        const win = window.open('', '_blank');
        win.document.write(
            '<!DOCTYPE html>' +
            '<html><head><title>SPK Report</title>' +
            '<style>' +
            'body { font-family: sans-serif; padding: 24px; }' +
            'table { width: 100%; border-collapse: collapse; font-size: 12px; }' +
            'th, td { border: 1px solid #e5e7eb; padding: 8px; text-align: left; }' +
            'th { background: #f3f4f6; }' +
            '</style></head>' +
            '<body>' + content + '</body>' +
            '</html>'
        );
        win.document.close();
        win.focus();
        win.print();
    }
    </script> --}}
</x-filament-widgets::widget>