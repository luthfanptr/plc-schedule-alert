<div class="flex items-center gap-2">
    <span>{{ $plcId }}</span>
    <div style="display: flex; flex-direction: column; gap: 2px">
        @if (($standard ?? 0) > 0)
            <span
                style="
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    min-width: 18px;
                    height: 14px;
                    padding: 0 4px;
                    font-size: 9px;
                    font-weight: 900;
                    border-radius: 4px;
                    background: rgba(34, 197, 94, 0.1);
                    color: #4ade80;
                    border: 1px solid rgba(34, 197, 94, 0.2);
                "
            >
                {{ $standard }}
            </span>
        @endif

        @if (($warning ?? 0) > 0)
            <span
                style="
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    min-width: 18px;
                    height: 14px;
                    padding: 0 4px;
                    font-size: 9px;
                    font-weight: 900;
                    border-radius: 4px;
                    background: rgba(234, 179, 8, 0.15);
                    color: #facc15;
                    border: 1px solid rgba(234, 179, 8, 0.3);
                "
            >
                {{ $warning }}
            </span>
        @endif

        @if (($danger ?? 0) > 0)
            <span
                style="
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    min-width: 18px;
                    height: 14px;
                    padding: 0 4px;
                    font-size: 9px;
                    font-weight: 900;
                    border-radius: 4px;
                    background: rgba(244, 63, 94, 0.1);
                    color: #fb7185;
                    border: 1px solid rgba(244, 63, 94, 0.2);
                "
            >
                {{ $danger }}
            </span>
        @endif
    </div>
</div>
