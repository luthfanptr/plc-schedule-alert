<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class GreetingHeader extends Widget
{
    protected string $view = 'filament.widgets.greeting-header';

    protected int|string|array $columnSpan = 'full';

    protected static bool $isLazy = false;

    protected static ?int $sort = 1;

    /**
     * @return array<string, mixed>
     */
    protected function getViewData(): array
    {
        $hour = now()->hour; 

        $greeting = match(true) {
            $hour >= 5 && $hour < 12  => 'Good Morning',
            $hour >= 12 && $hour < 18 => 'Good Afternoon',
            $hour >= 18 && $hour < 21 => 'Good Evening',
            default                   => 'Good Night',
        };

        return [
            'month' => now()->translatedFormat('F Y'),
            'greeting' => $greeting . ', ' . Auth::user()->name,
        ];
    }
}