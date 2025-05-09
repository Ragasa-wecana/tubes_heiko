<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KaryawanResource\Pages;
use App\Models\Karyawan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class KaryawanResource extends Resource
{
    protected static ?string $model = Karyawan::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Grid::make(1)->schema([
                TextInput::make('no_ktp')
                    ->label('No KTP')
                    ->autocapitalize('none')
                    ->required()
                    ->placeholder('Masukkan nomor KTP'),

                TextInput::make('nama_karyawan')
                    ->label('Nama Karyawan')
                    ->autocapitalize('words')
                    ->required()
                    ->placeholder('Masukkan nama karyawan'),

                TextInput::make('jabatan')
                    ->label('Jabatan')
                    ->autocapitalize('words')
                    ->required()
                    ->placeholder('Masukkan jabatan karyawan'),

                TextInput::make('nomor_telepon')
                    ->label('No Telepon')
                    ->required()
                    ->placeholder('Masukkan nomor telepon'),

                TextInput::make('email')
                    ->label('Email')
                    ->autocapitalize('none')
                    ->required()
                    ->placeholder('Masukkan email'),

                TextInput::make('alamat_karyawan')
                    ->label('Alamat Karyawan')
                    ->autocapitalize('words')
                    ->required()
                    ->placeholder('Masukkan alamat karyawan'),

                DatePicker::make('tgl_bergabung')
                    ->label('Tanggal Bergabung')
                    ->required(),

                TextInput::make('nama_bank')
                    ->label('Nama Bank')
                    ->autocapitalize('words')
                    ->required()
                    ->placeholder('Masukkan nama bank'),

                TextInput::make('no_rek')
                    ->label('No Rekening')
                    ->required()
                    ->placeholder('Masukkan nomor rekening'),

                TextInput::make('gaji_karyawan')
                    ->label('Gaji Karyawan')
                    ->numeric()
                    ->required()
                    ->placeholder('Masukkan gaji karyawan'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID'),
                TextColumn::make('no_ktp')->label('No KTP'),
                TextColumn::make('nama_karyawan')->label('Nama'),
                TextColumn::make('jabatan')->label('Jabatan'),
                TextColumn::make('nomor_telepon')->label('Telepon'),
                TextColumn::make('email')->label('Email'),
                TextColumn::make('alamat_karyawan')->label('Alamat'),
                TextColumn::make('tgl_bergabung')->label('Tanggal Bergabung'),
                TextColumn::make('nama_bank')->label('Bank'),
                TextColumn::make('no_rek')->label('Rekening'),
                TextColumn::make('gaji_karyawan')
                    ->label('Gaji')
                    ->money('Rp.', true),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKaryawans::route('/'),
            'create' => Pages\CreateKaryawan::route('/create'),
            'edit' => Pages\EditKaryawan::route('/{record}/edit'),
        ];
    }
}