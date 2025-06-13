<?php

namespace App\Filament\Resources\ArchiveResource\RelationManagers;

use App\Models\Archivelending;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\Alignment;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Carbon;

class ArchivelendingRelationManager extends RelationManager
{
    protected static string $relationship = 'archivelending';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
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
                                    ->required()
                                    ->preload()
                                    ->label('Nama Petugas Pengembalian'),

                                Forms\Components\TextArea::make('return_note')
                                    ->label('Catatan Pengembalian')
                                    ->columnSpanFull(),
                            ])
                    ])
            ]);
    }

    public function table(Table $table): Table
    {
        $check = Archivelending::where('archive_id', $this->record->id)
            ->where('lending_approval', 1)
            ->where('return_date', null)
            ->count();

        return $table
            ->recordTitleAttribute('archive_name')
            ->columns([
                Tables\Columns\TextColumn::make('index')
                    ->label('#')
                    ->alignment(Alignment::Center)
                    ->rowIndex(),
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
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->hidden($check > 0)
                    ->label('Pinjam'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    public function isReadOnly(): bool
    {
        return false;
    }
}
