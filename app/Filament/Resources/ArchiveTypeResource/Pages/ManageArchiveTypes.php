<?php

namespace App\Filament\Resources\ArchiveTypeResource\Pages;

use App\Filament\Resources\ArchiveTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageArchiveTypes extends ManageRecords
{
    protected static string $resource = ArchiveTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
