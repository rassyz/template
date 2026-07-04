<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\BannerAdsResource\Pages;
use App\Filament\Admin\Resources\BannerAdsResource\RelationManagers;
use App\Models\BannerAds;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BannerAdsResource extends Resource
{
    protected static ?string $model = BannerAds::class;

    protected static ?string $navigationIcon = 'heroicon-o-link';
    protected static ?string $navigationGroup = 'Ads Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('link')
                    ->activeUrl()
                    ->maxLength(255)
                    ->required(),
                Forms\Components\FileUpload::make('thumbnail')
                    ->image()
                    ->directory('ads')
                    ->required(),
                Forms\Components\Select::make('type')
                    ->options([
                        'banner' => 'Banner',
                        'square' => 'Square',
                    ])
                    ->required(),
                Forms\Components\Select::make('is_active')
                    ->options([
                        'active' => 'Active',
                        'not_active' => 'Not Active',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('link')
                    ->searchable(),
                Tables\Columns\TextColumn::make('is_active')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'not_active' => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active' => 'Active',
                        'not_active' => 'Not Active',
                    }),
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\ImageColumn::make('thumbnail'),
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
            'index' => Pages\ListBannerAds::route('/'),
            'create' => Pages\CreateBannerAds::route('/create'),
            'edit' => Pages\EditBannerAds::route('/{record}/edit'),
        ];
    }
}
