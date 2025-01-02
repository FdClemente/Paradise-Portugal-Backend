<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
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

class CustomerResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $slug = 'customers';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required(),

                TextInput::make('email')
                    ->required(),

                DatePicker::make('email_verified_at')
                    ->label('Email Verified Date'),

                TextInput::make('password')
                    ->required(),

                Placeholder::make('created_at')
                    ->label('Created Date')
                    ->content(fn(?User $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                Placeholder::make('updated_at')
                    ->label('Last Modified Date')
                    ->content(fn(?User $record): string => $record?->updated_at?->diffForHumans() ?? '-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $query->whereHas('roles', function ($query) {
                    $query->where('name', 'customer');
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
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getPluralLabel(): ?string
    {
        return __('filament.user.customer_plural');
    }

    public static function getLabel(): ?string
    {
        return __('filament.user.customer_singular');
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'email'];
    }
}
