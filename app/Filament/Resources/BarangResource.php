<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BarangResource\Pages;
use App\Filament\Resources\BarangResource\RelationManagers;
use App\Models\Barang;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Placeholder;


class BarangResource extends Resource
{
    protected static ?string $model = Barang::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    public static function getNavigationLabel(): string
    {
        return 'Barang';
    }
    // public static function getGloballySearchableAttributes(): array
    // {
    //     return ['id','name', 'category.name'];
    // }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('category_id')
                    ->Relationship('category', 'name')
                    ->required()
                    ->columnSpan(2)
                    // hanya muncul saat create
                    ->disabledOn('edit'), // kalau mau tetap tampil tapi disable saat edit,
                Forms\Components\Hidden::make('id')
                    ->rule(fn($record) => [
                        'unique:barangs,id' . ($record ? ',' . $record->id : ''),
                    ]),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->placeholder('Masukan nama barang')
                    ->columnSpan(2),
                Forms\Components\TextInput::make('merk')
                    ->required()
                    ->placeholder('masukkan merk barang'),
                Forms\Components\TextInput::make('model')
                    ->placeholder('masukkan model barang'),
                Forms\Components\TextInput::make('serial_number')
                    ->placeholder('Masukkan nomor seri (optional)'),
                Forms\Components\TextInput::make('stock')
                    ->required()
                    ->numeric()
                    ->placeholder('Masukkan jumlah stok')
                    ->default(1)
                    ->maxValue(1)
                    ->minValue(0),
                Forms\Components\Select::make('kondisi')
                    ->required()
                    ->options([
                        'baik' => 'baik',
                        'rusak' => 'rusak'
                    ]),
                Forms\Components\Hidden::make('cabang_id')
                    ->default(fn() => auth()->user()->cabang_id),
                Forms\Components\Select::make('status')
                    ->required()
                    ->options([
                        'terpakai' => 'Terpakai',
                        'tidak_terpakai' => 'Tidak Terpakai'
                    ]),
                Forms\Components\Textarea::make('description')
                    ->label('Keterangan')
                    ->columnSpan(2)
                    ->placeholder('optional'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID Barang')->searchable(),
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('merk')->searchable(),
                Tables\Columns\TextColumn::make('serial_number')->searchable(),
                Tables\Columns\TextColumn::make('cabang.name')->searchable(),
                Tables\Columns\TextColumn::make('stock'),
                Tables\Columns\TextColumn::make('category.name')
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')
                    ->relationship('category', 'name')
                    ->label('Kategori'),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'terpakai' => 'Terpakai',
                        'tidak_terpakai' => 'Tidak Terpakai'
                    ])
                    ->label('Status pakai'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListBarangs::route('/'),
            'create' => Pages\CreateBarang::route('/create'),
            'edit' => Pages\EditBarang::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return !auth()->user()->hasRole('GA');
    }
    public static function canEdit($record): bool
    {
        return !auth()->user()->hasRole('GA');
    }
    public static function canDelete($record): bool
    {
        return auth()->user()->hasRole('GA');
    }
}
