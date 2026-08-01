<?php

namespace App\Filament\Admin\Pages;

use BackedEnum;
use UnitEnum;
use Filament\Pages\Page;

class ApiDocs extends Page
{
    // Icon menu (bisa pakai icon buku atau icon key)
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-s-book-open';

    // Samakan dengan ApiKeyResource agar berada di grup yang sama
    protected static string|null|UnitEnum $navigationGroup = 'API Access';

    // Urutan menu di dalam grup Access (API Key = 1, API Docs = 2)
    protected static ?int $navigationSort = 2;

    // Label yang tampil di sidebar menu
    protected static ?string $navigationLabel = 'API Docs';

    // Judul di bagian atas halaman
    protected static ?string $title = 'API Documentation';

    protected string $view = 'filament.admin.pages.api-docs';
}
