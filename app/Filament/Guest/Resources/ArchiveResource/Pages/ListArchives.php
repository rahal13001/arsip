<?php

namespace App\Filament\Guest\Resources\ArchiveResource\Pages;

use App\Filament\Guest\Resources\ArchiveResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListArchives extends ListRecords
{
    protected static string $resource = ArchiveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
