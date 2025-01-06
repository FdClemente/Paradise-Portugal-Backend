<?php

namespace App\Filament\Resources\Settings;

use App\Filament\Components\Address\GoogleAutocomplete;
use App\Models\Settings\ExperiencePartner;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
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
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExperiencePartnerResource extends Resource
{
    protected static ?string $model = ExperiencePartner::class;

    protected static ?string $slug = 'experience-partners';

    public static function getNavigationGroup(): ?string
    {
        return __('filament.navigation_group.settings');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('filament.experience_partner.experience_partner_details'))
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('logo')
                            ->preserveFilenames(),
                        TextInput::make('name')
                            ->label(__('filament.experience_partner.name'))
                            ->required(),

                        TextInput::make('website')
                            ->label(__('filament.experience_partner.website')),

                        Select::make('languages')
                            ->label(__('filament.experience_partner.languages'))
                            ->multiple()
                            ->options(config('app.available_locales_with_labels')),

                        Fieldset::make(__('filament.user.contact_details'))
                            ->schema([
                                TextInput::make('phone_number')
                                    ->label(__('filament.experience_partner.phone_number')),
                                TextInput::make('email')
                                    ->helperText(__('filament.experience_partner.email_helper'))
                                    ->required(),
                            ]),
                        GoogleAutocomplete::make('address')
                            ->label('Google look-up')
                            ->withFields([
                                TextInput::make('street_name')
                                    ->extraInputAttributes([
                                        'data-google-field' => 'route',
                                    ])
                                    ->columnSpan(2)
                                    ->label(__('filament.house.street_name')),
                                TextInput::make('street_number')
                                    ->columnSpan(1)
                                    ->extraInputAttributes([
                                        'data-google-field' => 'street_number',
                                    ])
                                    ->label(__('filament.house.street_number')),
                                TextInput::make('zip')
                                    ->extraInputAttributes([
                                        'data-google-field' => 'postal_code_prefix',
                                    ])
                                    ->label(__('filament.house.zip_code')),
                                TextInput::make('city')
                                    ->label(__('filament.house.city'))
                                    ->extraInputAttributes([
                                        'data-google-field' => 'administrative_area_level_2',
                                    ])
                                    ->required(),
                                TextInput::make('state')
                                    ->label(__('filament.house.state'))
                                    ->extraInputAttributes([
                                        'data-google-field' => 'administrative_area_level_1',
                                    ])
                                    ->required(),
                                TextInput::make('latitude')
                                    ->label(__('filament.house.latitude'))
                                    ->required(),
                                TextInput::make('longitude')
                                    ->label(__('filament.house.longitude'))
                                    ->required(),
                                TextInput::make('country')
                                    ->label(__('filament.house.country'))
                                    ->columnSpanFull()
                                    ->required(),
                            ]),
                    ]),


                Placeholder::make('created_at')
                    ->label(__('filament.created_at'))
                    ->content(fn(?ExperiencePartner $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                Placeholder::make('updated_at')
                    ->label(__('filament.updated_at'))
                    ->content(fn(?ExperiencePartner $record): string => $record?->updated_at?->diffForHumans() ?? '-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('logo'),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('addressComplete'),

                TextColumn::make('phone_number'),

                TextColumn::make('email')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('website'),

                TextColumn::make('languages')->formatStateUsing(function ($record) {
                    return collect($record->languages)->map(fn($lang) => config('app.available_locales_with_labels')[$lang])->join(', ');
                }),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->actions([
                EditAction::make()->slideOver(),
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
            'index' => ExperiencePartnerResource\Pages\ListExperiencePartners::route('/'),
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
