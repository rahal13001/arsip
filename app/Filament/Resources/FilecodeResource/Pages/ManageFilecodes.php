<?php

namespace App\Filament\Resources\FilecodeResource\Pages;

use App\Filament\Resources\FilecodeResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageFilecodes extends ManageRecords
{
    protected static string $resource = FilecodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
