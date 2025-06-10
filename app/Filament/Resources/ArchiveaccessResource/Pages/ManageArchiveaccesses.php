<?php

namespace App\Filament\Resources\ArchiveaccessResource\Pages;

use App\Filament\Resources\ArchiveaccessResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageArchiveaccesses extends ManageRecords
{
    protected static string $resource = ArchiveaccessResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
