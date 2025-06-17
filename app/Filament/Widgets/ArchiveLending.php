<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Carbon;

class ArchiveLending extends BaseWidget
{
    protected static ?int $sort = 2;
    protected static ?string $heading = 'Daftar Arsip Sedang Dipinjam';
    protected int | string | array $columnSpan = 'full';
    public function table(Table $table): Table
    {
        return $table
            ->query(
                \App\Models\Archivelending::query()->with('archive')
                ->where('lending_approval', 1)
                ->whereNull('return_date')
            )
            ->columns([
                Tables\Columns\TextColumn::make('archive.archive_name')
                    ->label('Nama Arsip')
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();

                        if (strlen($state) <= $column->getCharacterLimit()) {
                            return null;
                        }
                        // Only render the tooltip if the column content exceeds the length limit.
                        return $state;
                    })
                    ->searchable(),
                        TextColumn::make('date_of_lending')
                            ->label('Tanggal Pinjam')
                            ->searchable(),
                        TextColumn::make('lending_until')
                            ->label('Batas Peminjaman')
                            ->searchable(),
                        TextColumn::make('applicant_name')
                            ->label('Nama Peminjam')
                            ->searchable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->getStateUsing(function ($record) {
                        // Pastikan 'lending_until' adalah objek Carbon
                        $lendingUntil = Carbon::parse($record->lending_until);

                        if ($lendingUntil->isPast()) {
                            return 'Terlambat';
                        }

                        if ($lendingUntil->isToday()) {
                            return 'Hari Terakhir Peminjaman';
                        }

                        return 'Dalam Rentang Waktu';
                    })
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Terlambat' => 'danger',
                        'Hari Terakhir Peminjaman' => 'warning',
                        'Dalam Rentang Waktu' => 'success',
                    })
            ]);
    }
}
