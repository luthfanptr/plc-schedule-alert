<x-filament-panels::page>
    <div x-data="{
            iframeHeight: 800,
            resizeToContent() {
                const iframe = this.$refs.frame;
                try {
                    const doc = iframe.contentDocument || iframe.contentWindow.document;

                    // paksa konten di dalam iframe tidak scroll sendiri,
                    // biar tingginya mengikuti konten asli (bukan 100vh)
                    let styleTag = doc.getElementById('force-natural-height');
                    if (!styleTag) {
                        styleTag = doc.createElement('style');
                        styleTag.id = 'force-natural-height';
                        doc.head.appendChild(styleTag);
                    }
                    styleTag.innerHTML = `
                        html, body {
                            height: auto !important;
                            min-height: 0 !important;
                            overflow: visible !important;
                        }
                        * {
                            overflow-y: visible !important;
                        }
                    `;

                    // ukur tinggi konten asli
                    const contentHeight = doc.documentElement.scrollHeight;
                    this.iframeHeight = contentHeight + 32; // sedikit buffer

                    // pantau perubahan tinggi konten (misal saat expand endpoint)
                    if (!this._observed) {
                        this._observed = true;
                        const ro = new ResizeObserver(() => this.resizeToContent());
                        ro.observe(doc.body);
                    }
                } catch (e) {
                    // fallback kalau gagal akses contentDocument (mis. beda origin)
                    this.iframeHeight = window.innerHeight - 200;
                    console.warn('Tidak bisa akses isi iframe, pakai fallback height', e);
                }
            }
        }"
        class="w-full"
    >
        <iframe
            x-ref="frame"
            @load="resizeToContent()"
            :style="`height: ${iframeHeight}px`"
            src="{{ url('/docs/api') }}"
            class="w-full border-0 rounded-lg shadow-sm block overflow-hidden"
            scrolling="no"
        ></iframe>
    </div>
</x-filament-panels::page>