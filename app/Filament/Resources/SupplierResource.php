<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupplierResource\Pages;
use App\Models\Supplier;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;

class SupplierResource extends Resource
{
    protected static ?string $model = Supplier::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('nama_supplier')
                ->label('Nama Supplier')
                ->required()
                ->maxLength(255)
                ->placeholder('Masukkan Nama Supplier'),

            Textarea::make('alamat')
                ->label('Alamat')
                ->required()
                ->rows(3)
                ->maxLength(255)
                ->placeholder('Masukkan Alamat Supplier'),

            TextInput::make('no_telepon')
                ->label('No. Telepon')
                ->required()
                ->maxLength(20)
                ->placeholder('Masukkan Nomor Telepon Supplier'),

            TextInput::make('email')
                ->label('Email')
                ->required()
                ->email()
                ->maxLength(255)
                ->placeholder('Masukkan Email Supplier'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('nama_supplier')
                ->label('Nama')
                ->sortable()
                ->searchable(),

            TextColumn::make('alamat')
                ->label('Alamat')
                ->limit(50),

            TextColumn::make('no_telepon')
                ->label('Telepon'),

            TextColumn::make('email')
                ->label('Email'),

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
            EditAction::make(),
            DeleteAction::make(), // Tombol delete individual ditambahkan di sini
        ])
        ->bulkActions([
            DeleteBulkAction::make(),
        ]);
    }

    public static function getRelations(): array
    {
        return [];
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