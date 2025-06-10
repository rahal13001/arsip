<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArchivelendingResource\Pages;
use App\Filament\Resources\ArchivelendingResource\RelationManagers;
use App\Models\Archive;
use App\Models\Archivelending;
use App\Models\Archivetype;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ArchivelendingResource extends Resource
{
    protected static ?string $model = Archivelending::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-library';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Fieldset::make()
                            ->schema([

                                Forms\Components\Select::make('archive_id')
                                    ->label('Nama Arsip')
                                    ->searchable()
                                    ->columnSpanFull()
                                    ->getSearchResultsUsing(function (string $search): array {
                                        $query = Archive::query()->with('filecode'); // Eager load for performance

                                        if (str_contains($search, '/')) {
                                            $searchParts = explode('/', $search);
                                            $query->where(function (Builder $q) use ($searchParts) {
                                                if (!empty($searchParts[0])) { $q->where('archive_number', 'like', '%' . $searchParts[0] . '%'); }
                                                if (!empty($searchParts[1])) { $q->whereHas('filecode', fn (Builder $subQ) => $subQ->where('file_code', 'like', '%' . $searchParts[1] . '%')); }
                                                if (!empty($searchParts[2])) { $q->whereYear('date_input', $searchParts[2]); }
                                            });
                                        } else {
                                            $query->where(function (Builder $q) use ($search) {
                                                $q->where('archive_number', 'like', "%{$search}%")
                                                    ->orWhere('archive_name', 'like', "%{$search}%")
                                                    ->orWhereHas('filecode', fn (Builder $subQ) => $subQ->where('file_code', 'like', "%{$search}%"))
                                                    ->orWhereRaw("YEAR(date_input) like ?", ["%{$search}%"]);
                                            });
                                        }

                                        return $query
                                            ->limit(50)
                                            ->get()
                                            ->mapWithKeys(function (Archive $record) {
                                                // We build the FULL desired label here for the dropdown
                                                $fileCode = $record->filecode?->file_code ?? '-';
                                                $year = $record->date_input ? Carbon::parse($record->date_input)->format('Y') : '-';
                                                $formattedString = "{$record->archive_name} ({$record->archive_number}/{$fileCode}/{$year})";
                                                return [$record->id => $formattedString];
                                            })
                                            ->toArray();
                                    })
                                    // This function will now be the ONLY source for the selected item's label.
                                    ->getOptionLabelUsing(function ($value): ?string {
                                        $record = Archive::with('filecode')->find($value);
                                        if (!$record) {
                                            return null;
                                        }

                                        $fileCode = $record->filecode?->file_code ?? '-';
                                        $year = $record->date_input ? Carbon::parse($record->date_input)->format('Y') : '-';

                                        return "{$record->archive_name} ({$record->archive_number}/{$fileCode}/{$year})";
                                    })
                                    ->required(),
                            ]),
                ]),

                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Fieldset::make()
                            ->schema([
                                Forms\Components\TextInput::make('applicant_name')
                                    ->label('Nama Pemohon')
                                    ->columnSpanFull()
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('applicant_position')
                                    ->label('Jabatan Pemohon')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('applicant_organization')
                                    ->label('Nama Organisasi Pemohon')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('applicant_address')
                                    ->label('Alamat Pemohon')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('applicant_phone')
                                    ->label('Nomor Telepon Pemohon')
                                    ->tel()
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('applicant_email')
                                    ->label('Email Pemohon')
                                    ->email()
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('applicant_id_number')
                                    ->label('Nomor ID Pemohon')
                                    ->required()
                                    ->maxLength(255),
                            ])
                ]),

                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Fieldset::make()
                            ->schema([
                                Forms\Components\DatePicker::make('date_of_application')
                                    ->label('Tanggal Pengajuan')
                                    ->required(),

                                Forms\Components\DatePicker::make('date_of_lending')
                                    ->label('Tanggal Peminjaman')
                                    ->required(),
                                Forms\Components\DatePicker::make('lending_until')
                                    ->label('Tanggal Berakhir Peminjaman')
                                    ->required(),
                                Forms\Components\Textarea::make('applicant_note')
                                    ->label('Catatan Pemohon')
                                    ->columnSpanFull(),
                            ]),
                    ]),

                Forms\Components\Section::make()
                    ->hidden(fn()=> !Auth::user()->can('create', Archivetype::class))
                    ->schema([
                        Forms\Components\Fieldset::make()
                        ->schema([
                            Forms\Components\Select::make('lending_approval')
                                ->options([
                                    1 => 'Disetujui',
                                    0 => 'Ditolak',
                                ])
                                ->label('Persetujuan Peminjaman'),
                            Forms\Components\DatePicker::make('date_of_approval')
                                ->label('Tanggal Persetujuan'),

                            Forms\Components\Select::make('officer_name')
                                ->options(User::all()->pluck('name', 'name'))
                                ->searchable()
                                ->required()
                                ->preload()
                                ->label('Nama Petugas'),

                            Forms\Components\TextInput::make('officer_position')
                                ->required()
                                ->label('Jabatan Petugas')
                                ->maxLength(255),

                            Forms\Components\Textarea::make('officer_note')
                                ->label('Catatan Petugas')
                                ->columnSpanFull(),
                        ])
                    ]),
                Forms\Components\Section::make()
                    ->hidden(fn()=> !Auth::user()->can('create', Archivetype::class))
                    ->schema([
                        Forms\Components\Fieldset::make()
                        ->schema([
                            Forms\Components\TextInput::make('returner_name')
                                ->label('Nama Pengembali')
                                ->maxLength(255),

                            Forms\Components\TextInput::make('returner_phone')
                                ->label('Nomor Telepon Pengembali')
                                ->tel()
                                ->maxLength(255),

                            Forms\Components\DatePicker::make('return_date')
                                ->label('Tanggal Pengembalian'),

                            Forms\Components\Select::make('return_officer_name')
                                ->options(User::all()->pluck('name', 'name'))
                                ->searchable()
                                ->preload()
                                ->label('Nama Petugas Pengembalian'),

                            Forms\Components\TextArea::make('return_note')
                                ->label('Catatan Pengembalian')
                                ->columnSpanFull(),
                        ])
                    ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->paginated([10, 25, 50, 75])
            ->extremePaginationLinks()
            ->striped()
            ->deferLoading()
            ->columns([
                Tables\Columns\TextColumn::make('index')
                    ->label('#')
                    ->alignment(Alignment::Center)
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('archive.archive_name')
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
                Tables\Columns\TextColumn::make('applicant_name')
                    ->label('Nama Pemohon')
                    ->searchable(),
                Tables\Columns\TextColumn::make('applicant_position')
                    ->label('Jabatan Pemohon')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('applicant_organization')
                    ->label('Organisasi Pemohon')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),

                Tables\Columns\TextColumn::make('applicant_phone')
                    ->label('Kontak Pemohon')
                    ->alignment(Alignment::Center)
                    ->copyable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('date_of_lending')
                    ->label('Tanggal Peminjaman')
                    ->alignment(Alignment::Center)
                    ->date('d-m-Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('lending_until')
                    ->label('Tanggal Berakhir Peminjaman')
                    ->date('d-m-Y')
                    ->alignment(Alignment::Center)
                    ->sortable(),
                Tables\Columns\TextColumn::make('lending_approval')
                    ->label('Persetujuan Peminjaman')
                    ->default('Dipertimbangkan')
                    ->alignment(Alignment::Center)
                    ->formatStateUsing(function ($record) {
                        if (is_null($record->lending_approval)) {
                            return 'Dipertimbangkan';
                        }
                        else {
                            return $record->lending_approval == 1 ? 'Disetujui' : 'Ditolak';
                        }
                    })
                    ->badge()
                    ->color(fn ($record) => $record->lending_approval === null
                        ? 'warning'
                        : ($record->lending_approval == 1
                            ? 'success'
                            : 'danger'
                        ))
                    ->sortable(),
                Tables\Columns\TextColumn::make('return_date')
                    ->label('Status Pengembalian')
                    ->alignment(Alignment::Center)
                    ->default(fn($record) => is_null($record->lending_approval) ? 'Belum Dikembalikan' : 'Belum Dipinjam')
                    ->formatStateUsing(function ($record) {
                        if (is_null($record->return_date)) {
                            return $record->lending_approval == 1 ? 'Belum Dikembalikan' : 'Belum Dipinjam';
                        }
                        else {
                            return $record->lending_approval == 1 ? 'Dikembalikan' : 'Belum Dipinjam';
                        }
                    })
                    ->badge()
                    ->color(function ($record) {
                        if (is_null($record->return_date)) {
                            return $record->lending_approval == 1 ? 'danger' : 'primary';
                        }
                        else {
                            return $record->lending_approval == 1 ? 'success' : 'primary';
                        }
                    })
                    ->sortable(),
            ])
            ->filters([
                Filter::make('date_of_lending')
                    ->label('Tanggal Pinjam')
                    ->form([
                        DatePicker::make('date_make_from')
                            ->label('Rentang Tanggal Pinjam Dari'),
                        DatePicker::make('date_make_until')
                            ->label('Rentang Tanggal Pinjam Sampai'),
                    ])
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['date_make_from'] ?? null) {
                            $indicators[] = Indicator::make('Rentang Tanggal Pinjam Dari ' . Carbon::parse($data['date_make_from'])->toFormattedDateString())
                                ->removeField('from');
                        }

                        if ($data['date_make_until'] ?? null) {
                            $indicators[] = Indicator::make('Rentang Tanggal Pinjam Sampai ' . Carbon::parse($data['date_make_until'])->toFormattedDateString())
                                ->removeField('until');
                        }

                        return $indicators;
                    })
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['date_make_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date_of_lending', '>=', $date),
                            )
                            ->when(
                                $data['date_make_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date_of_lending', '<=', $date),
                            );
                    }),

                Filter::make('lending_until')
                    ->label('Tanggal Batas Pinjam')
                    ->form([
                        DatePicker::make('date_make_from')
                            ->label('Tanggal Batas Pinjam Dari'),
                        DatePicker::make('date_make_until')
                            ->label('Tanggal Batas Pinjam Sampai'),
                    ])
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['date_make_from'] ?? null) {
                            $indicators[] = Indicator::make('Tanggal Batas Pinjam Dari ' . Carbon::parse($data['date_make_from'])->toFormattedDateString())
                                ->removeField('from');
                        }

                        if ($data['date_make_until'] ?? null) {
                            $indicators[] = Indicator::make('Tanggal Batas Pinjam Sampai ' . Carbon::parse($data['date_make_until'])->toFormattedDateString())
                                ->removeField('until');
                        }

                        return $indicators;
                    })
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['date_make_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('lending_until', '>=', $date),
                            )
                            ->when(
                                $data['date_make_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('lending_until', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ])
                    ->tooltip('Aksi'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListArchivelendings::route('/'),
            'create' => Pages\CreateArchivelending::route('/create'),
            'view' => Pages\ViewArchivelending::route('/{record}'),
            'edit' => Pages\EditArchivelending::route('/{record}/edit'),
        ];
    }

    public static function getLabel(): ?string
    {
        $locale = app()->getLocale();
        if ($locale === 'id') {
            return "Peminjaman";
        }
        else
        {
            return "Archive Lending";
        }
    }

}
