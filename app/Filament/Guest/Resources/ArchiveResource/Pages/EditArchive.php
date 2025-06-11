<?php

namespace App\Filament\Guest\Resources\ArchiveResource\Pages;

use App\Filament\Guest\Resources\ArchiveResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditArchive extends EditRecord
{
    protected static string $resource = ArchiveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
