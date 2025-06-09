<?php

namespace App\Filament\Resources\ArchivelendingResource\Pages;

use App\Filament\Resources\ArchivelendingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListArchivelendings extends ListRecords
{
    protected static string $resource = ArchivelendingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
