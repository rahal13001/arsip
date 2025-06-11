<?php

namespace App\Filament\Guest\Resources;

use App\Filament\Guest\Resources\ArchiveResource\Pages;
use App\Filament\Guest\Resources\ArchiveResource\RelationManagers;
use App\Models\Archive;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Carbon;

class ArchiveResource extends Resource
{
    protected static ?string $model = Archive::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function canViewAny(): bool
    {
        return true;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->paginated([10, 25, 50, 75])
            ->extremePaginationLinks()
            ->striped()
//            ->deferLoading()
            ->columns([
                Tables\Columns\TextColumn::make('index')
                    ->label('#')
                    ->alignment(Alignment::Center)
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('archive_number')
                    ->label('Nomor Arsip')
                    ->sortable()
                    ->formatStateUsing(function ($record) {
                        // Get archive_number from your model (e.g. '12')
                        $archiveNumber = $record->archive_number;
                        // Get related file_code (make sure filecode relationship is loaded)
                        $fileCode = optional($record->filecode)->file_code;
                        // In case date_input is a string, use:
                        $year = Carbon::parse($record->date_input)->format('Y');
                        // Build suffix, fallback to empty string if file_code or date_input missing
                        return $archiveNumber . '/' . ($fileCode ?: '-') . '/' . ($year ?: '-');
                    })
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        // This allows searching for individual parts, like "1", "MK", or "2025"
                        // as well as combined parts like "1/MK"
                        if (str_contains($search, '/')) {
                            $searchParts = explode('/', $search);

                            // Apply a query for each part of the search string
                            return $query->where(function (Builder $q) use ($searchParts) {
                                if (isset($searchParts[0]) && !empty($searchParts[0])) {
                                    $q->where('archive_number', 'like', '%' . $searchParts[0] . '%');
                                }
                                if (isset($searchParts[1]) && !empty($searchParts[1])) {
                                    $q->whereHas('filecode', function (Builder $subQ) use ($searchParts) {
                                        $subQ->where('file_code', 'like', '%' . $searchParts[1] . '%');
                                    });
                                }
                                if (isset($searchParts[2]) && !empty($searchParts[2])) {
                                    $q->whereYear('date_input', $searchParts[2]);
                                }
                            });
                        }

                        // Fallback: If no "/", search across multiple individual fields.
                        // This makes it possible to search for "MK" or "2025" directly.
                        return $query->where(function (Builder $q) use ($search) {
                            $q->where('archive_number', 'like', "%{$search}%")
                                ->orWhereHas('filecode', function (Builder $subQ) use ($search) {
                                    $subQ->where('file_code', 'like', "%{$search}%");
                                })
                                ->orWhereRaw("YEAR(date_input) like ?", ["%{$search}%"]);
                        });
                    }),
                Tables\Columns\TextColumn::make('archive_name')
                    ->label('Nama Arsip')
                    ->sortable()
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

                Tables\Columns\TextColumn::make('archiveuser.archive_status')
                    ->label('Status Arsip')
                    ->formatStateUsing(function ($record) {

                        return $record->archiveuser->archive_status == 1 ? 'Permanen' : 'Musnah';

                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('archivetype.archive_type')
                    ->label('Jenis Arsip')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pembuat')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('archiveuser.archive_properties')
                    ->label('Sifat Arsip')
                    ->alignment(Alignment::Center)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date_make')
                    ->date('d M Y')
                    ->alignment(Alignment::Center)
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('filecode.file_code')
                    ->label('Kode File')
                    ->alignment(Alignment::Center)
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('date_input')
                    ->date('d M Y')
                    ->alignment(Alignment::Center)
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('storage_location')
                    ->label('Lokasi Penyimpanan')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('development')
                    ->label('Tingkat Pengembangan')
                    ->alignment(Alignment::Center)
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            // These have been removed for the guest panel to prevent CSRF errors.
            // ->actions([
            //     Tables\Actions\EditAction::make(),
            // ])
            // ->bulkActions([
            //     Tables\Actions\BulkActionGroup::make([
            //         Tables\Actions\DeleteBulkAction::make(),
            //     ]),
            // ])
            ;
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListArchives::route('/'),
//            'create' => Pages\CreateArchive::route('/create'),
//            'edit' => Pages\EditArchive::route('/{record}/edit'),
        ];
    }
}
