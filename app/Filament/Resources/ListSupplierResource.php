<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ListSupplierResource\Pages;
use App\Filament\Resources\ListSupplierResource\RelationManagers;
use App\Models\ListSupplier;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;
use App\Models\Supplier;


class ListSupplierResource extends Resource
{
    protected static ?string $model = ListSupplier::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'ListSupplier';

     protected static ?string $navigationGroup = 'Masterdata';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //direlasikan ke tabel user
                Select::make('supplier_id')
                    ->label('supplier Id')
                    ->relationship('supplier', 'email')
                    ->searchable() // Menambahkan fitur pencarian
                    ->preload() // Memuat opsi lebih awal untuk pengalaman yang lebih cepat
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $supplier = Supplier::find($state);
                            $set('nama_supplier', $supplier->nama_supplier);
                            $set('alamat', $supplier->alamat);
                            $set('telepon', $supplier->no_telepon);
                        }
                    })
                ,
                TextInput::make('kode_supplier')
                    ->default(fn() => ListSupplier::getKodeSupplier()) // Ambil default dari method getKodePembeli
                    ->label('Kode Supplier')
                    ->required()
                    ->readonly() // Membuat field menjadi read-only
                ,
                TextInput::make('nama_supplier')
                    ->required()
                    ->placeholder('Masukkan nama supplier') // Placeholder untuk membantu pengguna
                    // ->live()
                    ->readonly() // Membuat field tidak bisa diketik manual
                ,
                TextInput::make('alamat')
                    ->required()
                    ->readonly(),

                TextInput::make('telepon')
                    ->required()
                    ->readonly() // Validasi dengan pattern regex
                ,
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_supplier'),
                TextColumn::make('nama_supplier'),
                TextColumn::make('alamat'),
                TextColumn::make('telepon'),
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
            'index' => Pages\ListListSuppliers::route('/'),
            'create' => Pages\CreateListSupplier::route('/create'),
            'edit' => Pages\EditListSupplier::route('/{record}/edit'),
        ];
    }
}