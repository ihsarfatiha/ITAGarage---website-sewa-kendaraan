<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReviewResource\Pages;
use App\Models\Review;
use App\Models\Motor;
use App\Models\Car;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Get;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';
    
    protected static ?string $navigationLabel = 'Review';
    
    protected static ?string $navigationGroup = 'Manajemen Transaksi';
    
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Review')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Customer')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        
                        Forms\Components\ToggleButtons::make('reviewable_type')
                            ->label('Tipe Kendaraan')
                            ->options([
                                'App\Models\Motor' => 'Motor',
                                'App\Models\Car' => 'Mobil',
                            ])
                            ->icons([
                                'App\Models\Motor' => 'heroicon-o-bolt',
                                'App\Models\Car' => 'heroicon-o-truck',
                            ])
                            ->required()
                            ->reactive()
                            ->inline()
                            ->afterStateUpdated(fn (callable $set) => $set('reviewable_id', null)),
                        
                        Forms\Components\Select::make('reviewable_id')
                            ->label('Kendaraan')
                            ->options(function (Get $get) {
                                $type = $get('reviewable_type');
                                if ($type === 'App\Models\Motor') {
                                    return Motor::all()->pluck('full_name', 'id');
                                } elseif ($type === 'App\Models\Car') {
                                    return Car::all()->pluck('full_name', 'id');
                                }
                                return [];
                            })
                            ->searchable()
                            ->required()
                            ->reactive(),
                        
                        Forms\Components\Select::make('transaction_id')
                            ->label('Transaksi (Optional)')
                            ->relationship('transaction', 'id')
                            ->searchable()
                            ->preload()
                            ->placeholder('Pilih transaksi (opsional)'),
                    ])->columns(2),
                
                Section::make('Rating & Komentar')
                    ->schema([
                        Forms\Components\Radio::make('rating')
                            ->label('Rating')
                            ->options([
                                1 => '⭐ 1 - Sangat Buruk',
                                2 => '⭐⭐ 2 - Buruk',
                                3 => '⭐⭐⭐ 3 - Cukup',
                                4 => '⭐⭐⭐⭐ 4 - Baik',
                                5 => '⭐⭐⭐⭐⭐ 5 - Sangat Baik',
                            ])
                            ->required()
                            ->inline(false),
                        
                        Forms\Components\Textarea::make('comment')
                            ->label('Komentar')
                            ->rows(5)
                            ->placeholder('Tulis pengalaman Anda...')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('reviewable_type')
                    ->label('Tipe')
                    ->formatStateUsing(fn (string $state): string => 
                        $state === 'App\\Models\\Motor' ? 'Motor' : 'Mobil'
                    )
                    ->badge()
                    ->color(fn (string $state): string => 
                        $state === 'App\\Models\\Motor' ? 'info' : 'success'
                    ),
                
                Tables\Columns\TextColumn::make('vehicle_name')
                    ->label('Kendaraan')
                    ->searchable(['reviewable.brand', 'reviewable.model']),
                
                Tables\Columns\TextColumn::make('rating')
                    ->label('Rating')
                    ->formatStateUsing(fn (int $state): string => str_repeat('⭐', $state))
                    ->sortable()
                    ->badge()
                    ->color(fn (int $state): string => match (true) {
                        $state >= 4 => 'success',
                        $state === 3 => 'warning',
                        default => 'danger',
                    }),
                
                Tables\Columns\TextColumn::make('comment')
                    ->label('Komentar')
                    ->limit(50)
                    ->wrap()
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('transaction_id')
                    ->label('ID Transaksi')
                    ->sortable()
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('reviewable_type')
                    ->label('Tipe Kendaraan')
                    ->options([
                        'App\Models\Motor' => 'Motor',
                        'App\Models\Car' => 'Mobil',
                    ]),
                
                SelectFilter::make('rating')
                    ->label('Rating')
                    ->options([
                        5 => '⭐⭐⭐⭐⭐ 5 Bintang',
                        4 => '⭐⭐⭐⭐ 4 Bintang',
                        3 => '⭐⭐⭐ 3 Bintang',
                        2 => '⭐⭐ 2 Bintang',
                        1 => '⭐ 1 Bintang',
                    ]),
                
                Tables\Filters\Filter::make('high_rated')
                    ->label('Rating Tinggi (≥4)')
                    ->query(fn ($query) => $query->where('rating', '>=', 4)),
                
                Tables\Filters\Filter::make('low_rated')
                    ->label('Rating Rendah (≤2)')
                    ->query(fn ($query) => $query->where('rating', '<=', 2)),
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
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListReviews::route('/'),
            'create' => Pages\CreateReview::route('/create'),
            'edit' => Pages\EditReview::route('/{record}/edit'),
        ];
    }
    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    
    public static function getNavigationBadgeColor(): ?string
    {
        $avgRating = static::getModel()::avg('rating');
        return $avgRating >= 4 ? 'success' : ($avgRating >= 3 ? 'warning' : 'danger');
    }
}