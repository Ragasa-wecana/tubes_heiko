<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransaksiPenjualanResource\Pages;
use App\Filament\Resources\TransaksiPenjualanResource\RelationManagers;
use App\Models\transaksipenjualan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\DatePicker;

class transaksipenjualanResource extends Resource
{
    protected static ?string $model = transaksipenjualan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('no_transaksi')
                    ->default(fn () => transaksipenjualan::getNoTransaksi())
                    ->label('No Transaksi')
                    ->required()
                    ->readonly(),
                
                DatePicker::make('tanggal_transaksi')
                    ->label('Tanggal Transaksi')
                    ->required(),
                
                TextInput::make('total')
                    ->label('Total Harga')
                    ->required()
                    ->minValue(0)
                    ->reactive()
                    ->placeholder('Masukkan Total Harga')
                    ->live()
                    ->afterStateUpdated(fn ($state, callable $set) => 
                        $set('total', number_format((int) str_replace('.', '', $state), 0, ',', '.'))
            ),
                TextInput::make('status')
                    ->label('Status Pembayaran')
                    ->required()
                    ->placeholder('Masukkan status pembayaran'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no_transaksi')
                    ->label('No Transaksi')
                    ->searchable(),
                
                TextColumn::make('tanggal_transaksi')
                    ->iconcolor('warning')
                    ->icon('heroicon-o-calendar-days')
                    ->label('Tanggal Transaksi')
                    ->sortable(),
                
                TextColumn::make('total')
                    ->label('Total Harga')
                    ->formatStateUsing(fn (string|int|null $state): string => rupiah($state))
                    ->extraAttributes(['class' => 'text-right'])
                    ->sortable(),
                
                TextColumn::make('status')
                    ->label('Status Pembayaran')
                    ->searchable(),
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
            'index' => Pages\ListTransaksiPenjualans::route('/'),
            'create' => Pages\CreateTransaksiPenjualan::route('/create'),
            'edit' => Pages\EditTransaksiPenjualan::route('/{record}/edit'),
        ];
    }
}