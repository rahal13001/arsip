<?php

namespace App\Filament\Resources\ArchiveResource\Pages;

use App\Filament\Resources\ArchiveResource;
use App\Models\Archive;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Cache;

class CreateArchive extends CreateRecord
{
    protected static string $resource = ArchiveResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {

        $data['archive_number'] = Cache::lock('archive_number_generation', 10)->get(function () {
            $currentYear = now()->year;

            // This logic is now protected from race conditions
            $lastRecord = Archive::whereYear('date_input', $currentYear)
                ->orderBy('archive_number', 'desc')
                ->first();

            return $lastRecord ? ((int)$lastRecord->archive_number + 1) : 1;
        });

//        dd($data);

        return $data;
    }

}
