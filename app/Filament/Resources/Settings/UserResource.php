<?php

namespace App\Filament\Resources\Settings;

use App\Filament\Resources\Settings;
use App\Filament\Resources\Settings\UserResource\Pages;
use App\Models\User;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $slug = 'users';

    public static function getNavigationGroup(): ?string
    {
        return __('filament.navigation_group.settings');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('filament.user.user_details'))
                    ->description(__('filament.user.admin_warning'))
                    ->columns()
                    ->schema([
                        TextInput::make('first_name')
                            ->columnSpan(1)
                            ->label(__('filament.user.first_name'))
                            ->required(),
                        TextInput::make('last_name')
                            ->columnSpan(1)
                            ->label(__('filament.user.last_name'))
                            ->required(),
                        TextInput::make('email')
                            ->columnSpan(2)
                            ->label(__('filament.user.email'))
                            ->unique(ignoreRecord: true)
                            ->required(),
                        TextInput::make('password')
                            ->label(__('filament.user.password'))
                            ->required()
                            ->columnSpan(1)
                            ->confirmed(),
                        TextInput::make('password_confirmation')
                            ->label(__('filament.user.password_confirmation'))
                            ->required()
                            ->columnSpan(1)
                            ->confirmed()
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $query->whereHas('roles', function ($query) {
                    $query->where('role', 'admin');
                });
            })
            ->columns([
                TextColumn::make('name')
                    ->label(__('filament.user.name'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label(__('filament.user.email'))
                    ->searchable()
                    ->sortable(),

                IconColumn::make('email_verified_at')
                    ->label(__('filament.user.email_verified_at'))
                    ->default(false)
                    ->boolean()
                    ->tooltip(fn(?User $record): string => $record?->email_verified_at?->diffForHumans() ?? __('filament.user.email_verified_placeholder')),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
                RestoreAction::make(),
                ForceDeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'email'];
    }
}
