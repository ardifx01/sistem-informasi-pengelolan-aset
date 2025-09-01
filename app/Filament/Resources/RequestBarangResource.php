<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RequestBarangResource\Pages;
use App\Filament\Resources\RequestBarangResource\RelationManagers;
use App\Models\RequestBarang;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use GuzzleHttp\Psr7\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\Filter;

class RequestBarangResource extends Resource
{
    protected static ?string $model = RequestBarang::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    public static function getNavigationLabel(): string
    {
        return 'Request Barang';
    }
    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('barang_id')
                ->relationship('barang', 'name')
                ->required()
                ->columnSpan(2)
                ->reactive() // agar bisa memicu event ketika dipilih
                ->afterStateUpdated(function ($state, callable $set) {
                    $barang = \App\Models\Barang::with('category')->find($state);
                    if ($barang) {
                        $set('merk', $barang->merk);
                        $set('model', $barang->model);
                        $set('serial_number', $barang->serial_number);
                        $set('category_name', $barang->category?->name); // kategori
                    } else {
                        $set('merk', null);
                        $set('model', null);
                        $set('serial_number', null);
                        $set('category_name', null);
                    }
                }),

            Forms\Components\TextInput::make('merk')
                ->label('Merk')
                ->disabled(),

            Forms\Components\TextInput::make('model')
                ->label('Model')
                ->disabled(),

            Forms\Components\TextInput::make('serial_number')
                ->label('Serial Number')
                ->disabled(),

            Forms\Components\TextInput::make('category_name')
                ->label('Kategori')
                ->disabled(),
            Forms\Components\Hidden::make('user_id')
                ->default(fn() => auth()->id()),
            Forms\Components\DatePicker::make('date_request')
                ->required()
                ->disabled()
                ->default(now()),
            Forms\Components\Textarea::make('description')
                ->label('Keterangan')
                ->columnSpan(2),
            Forms\Components\Hidden::make('cabang_id')
                ->default(fn() => auth()->user()->cabang_id),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('barang.name'),
            Tables\Columns\TextColumn::make('user.name'),
            Tables\Columns\TextColumn::make('cabang.name'),
            Tables\Columns\TextColumn::make('date_request'),
            Tables\Columns\BadgeColumn::make('status_approve')
                ->label('Pengajuan')
                ->colors([
                    'warning' => 'pending',
                    'success' => 'approved',
                    'danger' => 'rejected',
                ]),

        ])
            ->filters([
                Tables\Filters\Filter::make('status_pending')
                    ->label('Pengajuan Pending')
                    ->query(fn($query) => $query->where('status_approve', '=', 'pending')),
                Tables\Filters\Filter::make('status_approved')
                    ->label('Pengajuan  Approved')
                    ->query(fn($query) => $query->where('status_approve', '=', 'approved')),
                Tables\Filters\Filter::make('status_rejected')
                    ->label('Pengajuan Rejected')
                    ->query(fn($query) => $query->where('status_approve', '=', 'rejected')),
                Filter::make('date_request')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('until')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'], fn($q) => $q->whereDate('date_request', '>=', $data['from']))
                            ->when($data['until'], fn($q) => $q->whereDate('date_request', '<=', $data['until']));
                    }),

            ])
              ->defaultSort('id', 'asc')
            ->actions([

                Tables\Actions\EditAction::make()
                    ->visible(fn(RequestBarang $record) => $record->status_approve === 'pending'),
                Tables\Actions\ViewAction::make()
                    ->form([
                        Forms\Components\TextInput::make('date_request')
                            ->label('Tanggal Request')
                            ->disabled(),

                        Forms\Components\Textarea::make('description')
                            ->label('Keterangan')
                            ->disabled(),
                    ]),

                Tables\Actions\Action::make('Approve')
                    ->visible(fn(RequestBarang $record) => auth()->user()->hasRole('GA') && $record->status_approve !== 'approved')
                    ->action(fn(RequestBarang $record) => $record->update(['status_approve' => 'approved'])),
                Tables\Actions\Action::make('Reject')
                    ->visible(fn(RequestBarang $record) => auth()->user()->hasRole('GA') && $record->status_approve !== 'approved')
                    ->action(fn(RequestBarang $record) => $record->update(['status_approve' => 'rejected'])),
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
            'index' => Pages\ListRequestBarangs::route('/'),
            'create' => Pages\CreateRequestBarang::route('/create'),
            'edit' => Pages\EditRequestBarang::route('/{record}/edit'),
        ];
    }
    public static function canCreate(): bool
    {
        return !auth()->user()->hasRole('GA');
    }

    public static function getEloquentQuery(): Builder
    {
        // Jika PIC, hanya lihat request miliknya & sesuai cabang
        if (auth()->check() && !auth()->user()->hasRole('GA')) {
            return parent::getEloquentQuery()
                ->where('user_id', auth()->id());
        }

        return parent::getEloquentQuery();
    }
}
