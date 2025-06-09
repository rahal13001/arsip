<?php

namespace App\Filament\Resources\ArchivelendingResource\Pages;

use App\Filament\Resources\ArchivelendingResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewArchivelending extends ViewRecord
{
    protected static string $resource = ArchivelendingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
