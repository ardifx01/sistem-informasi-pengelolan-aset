<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransferBarangResource\Pages;
use App\Filament\Resources\TransferBarangResource\RelationManagers;
use App\Models\TransferBarang;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransferBarangResource extends Resource
{
    protected static ?string $model = TransferBarang::class;

    protected static ?string $navigationIcon = 'heroicon-o-paper-airplane';
    protected static ?string $pluralModelLabel = 'Transfer Barang';
    public static function getNavigationLabel(): string
    {
        return 'Transfer Barang';
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('request_id')
                    ->relationship(
                        name: 'request',
                        titleAttribute: 'id',
                        modifyQueryUsing: fn(\Illuminate\Database\Eloquent\Builder $query) =>
                        $query->where('user_id', auth()->id())
                            ->where('status_approve', 'approved')
                    )
                    ->required()
                    ->label('Request ID')
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $request = \App\Models\RequestBarang::with('barang')->with('user')->find($state);
                            if ($request) {
                                $set('barang_id', $request->barang_id);
                                $set('barang_name', $request->barang?->name);
                                $set('user_name', $request->user?->name);
                                $set('cabang_penerima_id', $request->cabang_id);
                            }
                        } else {
                            $set('barang_id', null);
                            $set('barang_name', null);
                            $set('user_name', null);
                            $set('cabang_penerima_id', null);
                        }
                    }),
                Forms\Components\TextInput::make('barang_id')
                    ->label('Barang'),
                Forms\Components\TextInput::make('barang_name')
                    ->label('Nama Barang')
                    ->disabled(),
                Forms\Components\TextInput::make('cabang_penerima_id')
                    ->label('Penerima'),
                Forms\Components\TextInput::make('user_name')
                    ->label('Nama Penerima'),
                Forms\Components\TextInput::make('cabang_pengirim_id')
                    ->label('Pengirim')
                    ->default(fn() => auth()->id()),
                Forms\Components\TextArea::make('description')
                    ->label('keterangan')
                    ->placeholder('optional')
                    ->columnSpan(2),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('request.id')
                    ->label('ID Request')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('pengirim.name')
                    ->label('Pengirim')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('penerima.name')
                    ->label('Penerima')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('barang.name')
                    ->label('Nama Barang')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('status_approve')
                    ->label('Pengajuan')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'primary' => 'diterima',
                    ]),

            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_approve')
                ->label('Status')
                ->options([
                    'pending' => 'Pending',
                    'approved' => 'Approved',
                    'diterima' => 'Diterima'
                ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('Approve')
                    ->visible(fn(TransferBarang $record) => auth()->user()->hasRole('GA') && $record->status_approve === 'pending')
                    ->action(fn(TransferBarang $record) => $record->update(['status_approve' => 'approved'])),
                Tables\Actions\Action::make('Reject')
                    ->visible(fn(TransferBarang $record) => auth()->user()->hasRole('GA') && $record->status_approve === 'pending')
                    ->action(fn(TransferBarang $record) => $record->update(['status_approve' => 'rejected'])),
                Tables\Actions\Action::make('diterima')
                    ->visible(
                        fn(TransferBarang $record) =>
                        $record->cabang_penerima_id === auth()->user()->id
                            && $record->status_approve === 'approved'
                    )
                    ->action(function (TransferBarang $record) {
                        // Update status transfer barang
                        $record->update([
                            'status_approve' => 'diterima',
                        ]);

                        // Update cabang_id di tabel barang
                        $record->barang->update([
                            'cabang_id' => auth()->user()->cabang_id,
                        ]);
                    }),

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
            'index' => Pages\ListTransferBarangs::route('/'),
            'create' => Pages\CreateTransferBarang::route('/create'),
            'edit' => Pages\EditTransferBarang::route('/{record}/edit'),
        ];
    }

    public static function canEdit($record): bool
    {
        return !auth()->user()->hasRole('GA')
            && $record->status_approve === 'pending' &&
            $record->cabang_pengirim_id === auth()->user()->id;
    }
    public static function canDelete($record): bool
    {
        return  $record->status_approve === 'pending' &&
            $record->cabang_pengirim_id === auth()->user()->id;
    }
}
