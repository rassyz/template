<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ArticleNewsResource\Pages;
use App\Filament\Admin\Resources\ArticleNewsResource\RelationManagers;
use App\Models\ArticleNews;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ArticleNewsResource extends Resource
{
    protected static ?string $model = ArticleNews::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';
    protected static ?string $navigationGroup = 'Content Management';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\FileUpload::make('thumbnail')
                    ->image()
                    ->directory('news')
                    ->required(),
                Forms\Components\RichEditor::make('content')
                    ->columnSpan(2)
                    ->required(),
                Forms\Components\Select::make('category_id')
                    ->relationship('category', 'name')
                    ->required(),
                Forms\Components\Select::make('author_id')
                    ->relationship('author', 'name')
                    ->required(),
                Forms\Components\Select::make('is_featured')
                    ->options([
                        'featured' => 'Featured',
                        'not_featured' => 'Not Featured',
                    ])
                    ->required()
                    ->columnSpan(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail'),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('author.name'),
                Tables\Columns\TextColumn::make('is_featured')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'featured' => 'success',
                        'not_featured' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date Time') // Opsional: ubah label di tabel
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('Category')
                    ->relationship('category', 'name'),
                Tables\Filters\SelectFilter::make('Author')
                    ->relationship('author', 'name'),
                Tables\Filters\SelectFilter::make('Featured Status')
                    ->options([
                        'featured' => 'Featured',
                        'not_featured' => 'Not Featured',
                    ]),
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
            'index' => Pages\ListArticleNews::route('/'),
            'create' => Pages\CreateArticleNews::route('/create'),
            'edit' => Pages\EditArticleNews::route('/{record}/edit'),
        ];
    }
}
