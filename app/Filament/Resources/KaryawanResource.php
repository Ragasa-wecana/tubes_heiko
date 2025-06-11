<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KaryawanResource\Pages;
use App\Filament\Resources\KaryawanResource\RelationManagers;
use App\Models\Karyawan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;


class KaryawanResource extends Resource
{
    protected static ?string $model = Karyawan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                 Grid::make(1) // Membuat hanya 1 kolom
                ->schema([
                    TextInput::make('id_karyawan')
                        ->required()
                        ->placeholder('Masukkan kode karyawan'),
                    TextInput::make('nama_karyawan')
                        ->required()
                        ->placeholder('Masukkan nama karyawan'),
                    DatePicker::make('tanggal_lahir')
                        ->label('tanggal_lahir')
                        ->required()
                        ->placeholder('Masukkan Jenis Kelamin'),
                    TextInput::make('status')
                        ->required()
                        ->placeholder('Masukkan status karyawan'),
                    TextInput::make('jabatan')
                        ->required()
                        ->placeholder('Masukkan jabatan'),       
                    TextInput::make('no_telp')
                        ->required()
                        ->placeholder('Masukkan nomor telepon'),
                    TextInput::make('alamat')
                        ->required()
                        ->placeholder('Masukkan alamat'),
                //
                ]),
        ]);   
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id_karyawan')
                    ->searchable(),
                TextColumn::make('nama_karyawan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('tanggal_lahir')
                    ->label('tanggal_lahir'),
                TextColumn::make('status'),  
                TextColumn::make('jabatan'),  
                TextColumn::make('no_telp'),
                TextColumn::make('alamat'),
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListKaryawans::route('/'),
            'create' => Pages\CreateKaryawan::route('/create'),
            'edit' => Pages\EditKaryawan::route('/{record}/edit'),
        ];
    }
}