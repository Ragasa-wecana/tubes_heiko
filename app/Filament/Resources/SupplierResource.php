<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupplierResource\Pages;
use App\Filament\Resources\SupplierResource\RelationManagers;
use App\Models\Supplier;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;

class SupplierResource extends Resource
{
    protected static ?string $model = Supplier::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
               // Kode Supplier otomatis dan tidak bisa diubah
               TextInput::make('kode_supplier')
               ->label('Kode Supplier')
               ->default(fn () => Supplier::getKodeSupplier())
               ->disabled()
               ->required(),

            TextInput::make('nama')
                ->label('Nama Supplier')
                ->required()
                ->maxLength(255)
                ->placeholder('Masukkan Nama'),

            Textarea::make('alamat')
                ->label('Alamat')
                ->required()
                ->rows(3)
                ->maxLength(65535)
                ->placeholder('Masukkan Alamat'),

            TextInput::make('email')
                ->label('Email')
                ->required()
                ->email()
                ->maxLength(255)
                ->placeholder('Masukkan Email'),

            TextInput::make('no_telepon')
                ->label('No. Telepon')
                ->required()
                ->maxLength(100)
                ->placeholder('Masukkan Nomor telepon')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_supplier')
                    ->label('Kode')
                    ->sortable()
                    ->searchable(),

            TextColumn::make('nama')
                ->label('Nama')
                ->sortable()
                ->searchable(),

            TextColumn::make('alamat')
                ->label('Alamat')
                ->limit(50),

            TextColumn::make('email')
                ->label('Email'),

            TextColumn::make('no_telepon')
                ->label('Telepon'),

            TextColumn::make('created_at')
                ->label('Dibuat')
                ->dateTime('d M Y H:i'),

            TextColumn::make('updated_at')
                ->label('Diubah')
                ->dateTime('d M Y H:i'),
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
            'index' => Pages\ListSuppliers::route('/'),
            'create' => Pages\CreateSupplier::route('/create'),
            'edit' => Pages\EditSupplier::route('/{record}/edit'),
        ];
    }
}
