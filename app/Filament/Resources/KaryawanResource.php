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

                TextInput::make('alamat_karyawan')
                    ->label('Alamat Karyawan')
                    ->autocapitalize('words')
                    ->required()
                    ->placeholder('Masukkan alamat karyawan'),

            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID'),
                TextColumn::make('nama_karyawan')->label('Nama'),
                TextColumn::make('jabatan')->label('Jabatan'),
                TextColumn::make('nomor_telepon')->label('Telepon'),
                TextColumn::make('alamat_karyawan')->label('Alamat'),
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
