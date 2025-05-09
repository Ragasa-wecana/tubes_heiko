<?php

namespace App\Filament\Resources;

use Filament\Forms\Components\TextInput; // kita menggunakan textinput
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\DatePicker; // Menambahkan DatePicker untuk tanggal
use Filament\Tables\Columns\TextColumn;
use App\Filament\Resources\KaryawanResource\Pages;
use App\Filament\Resources\KaryawanResource\RelationManagers;
use App\Models\Karyawan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder; 
use Illuminate\Database\Eloquent\SoftDeletingScope; 

use Filament\Forms\Components\FileUpload;

class KaryawanResource extends Resource
{
    protected static ?string $model = Karyawan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // tambahan untuk grooping
    protected static ?string $navigationGroup = 'Masterdata';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(1) // Membuat hanya 1 kolom
                    ->schema([
                        TextInput::make('id_karyawan')
                        ->default(fn () => karyawan::getIdKaryawan())
                        ->label('Id karyawan')
                        ->required()
                        ->readonly(),

                        TextInput::make('nama_karyawan')
                            ->autocapitalize('words')
                            ->label('Nama karyawan')
                            ->required()
                            ->placeholder('Masukkan nama karyawan'),

                        TextInput::make('jabatan')
                            ->autocapitalize('words')
                            ->label('Jabatan karyawan')
                            ->required()
                            ->placeholder('Masukkan jabatan karyawan'),

                        TextInput::make('alamat_karyawan')
                            ->autocapitalize('words')
                            ->label('Alamat karyawan')
                            ->required()
                            ->placeholder('Masukkan alamat karyawan'),

                        TextInput::make('nomor_telepon')
                            ->autocapitalize('words')
                            ->label('No telepon')
                            ->required()
                            ->placeholder('Masukkan no telepon'),

                         DatePicker::make('tgl_bergabung')
                            ->label('Tanggal Bergabung')
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id_karyawan')
                    ->label('Id karyawan')
                    ->searchable(), // Menggunakan 'id' sebagai primary key

                TextColumn::make('nama_karyawan'),
                TextColumn::make('jabatan'),
                TextColumn::make('alamat_karyawan'),
                TextColumn::make('nomor_telepon'),
                TextColumn::make('tgl_bergabung')
                    ->iconcolor('warning')
                    ->icon('heroicon-o-calendar-days')
                    ->label('Tanggal Bergabung')
                    ->sortable(),

            ])
            ->filters([
                //
            ])
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