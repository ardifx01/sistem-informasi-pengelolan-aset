<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function getNavigationLabel(): string
    {
        return 'Users';
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nip')
                    ->required()
                    ->placeholder('NIP'),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->placeholder('nama lengkap'),
                Forms\Components\TextInput::make('email')
                    ->required()
                    ->placeholder('example@gmail.com')
                    ->email()
                    ->columnSpan(2),
                Forms\Components\TextInput::make('password')
                    ->required()
                    ->password()
                    ->revealable()
                    ->placeholder('********')
                    ->columnSpan(2),
                Forms\Components\Select::make('role')
                    ->options([
                        'GA'  => 'GA',
                        'PIC' => 'PIC CABANG'
                    ])
                    ->reactive()
                    ->required(),
                Forms\Components\Select::make('cabang')
                    ->relationship('cabang', 'name')
                    ->requiredIf('role', 'PIC') // ✅ wajib kalau role = PIC
                    ->hidden(fn($get) => $get('role') !== 'PIC'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nip'),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('role'),
                Tables\Columns\TextColumn::make('cabang.name'),
            ])
            ->filters([
                //
            ])
            ->actions([
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
    public static function canViewAny(): bool
    {
        return auth()->user()->hasRole('GA');
    }
}
