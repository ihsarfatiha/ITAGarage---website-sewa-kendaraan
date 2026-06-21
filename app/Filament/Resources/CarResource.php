<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CarResource\Pages;
use App\Models\Car;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Tables\Filters\SelectFilter;

class CarResource extends Resource
{
    protected static ?string $model = Car::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';
    
    protected static ?string $navigationLabel = 'Mobil';
    
    protected static ?string $navigationGroup = 'Manajemen Kendaraan';
    
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Dasar')
                    ->schema([
                        Forms\Components\TextInput::make('brand')
                            ->label('Brand')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Toyota, Honda, Mitsubishi'),
                        
                        Forms\Components\TextInput::make('model')
                            ->label('Model')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Avanza, Xpander, Civic'),
                        
                        Forms\Components\Select::make('body_type')
                            ->label('Tipe Body')
                            ->options([
                                'sedan' => 'Sedan',
                                'suv' => 'SUV',
                                'mpv' => 'MPV',
                                'hatchback' => 'Hatchback',
                                'pickup' => 'Pickup',
                            ])
                            ->required()
                            ->native(false),
                        
                        Forms\Components\Select::make('transmission')
                            ->label('Transmisi')
                            ->options([
                                'manual' => 'Manual',
                                'automatic' => 'Automatic',
                                'cvt' => 'CVT',
                            ])
                            ->required()
                            ->native(false),
                        
                        Forms\Components\TextInput::make('year')
                            ->label('Tahun')
                            ->required()
                            ->numeric()
                            ->minValue(2000)
                            ->maxValue(date('Y') + 1)
                            ->default(date('Y')),
                        
                        Forms\Components\TextInput::make('color')
                            ->label('Warna')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('license_plate')
                            ->label('Plat Nomor')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->placeholder('B 1234 XYZ'),
                    ])->columns(2),
                
                Section::make('Spesifikasi Mobil')
                    ->schema([
                        Forms\Components\TextInput::make('seats')
                            ->label('Jumlah Kursi')
                            ->required()
                            ->numeric()
                            ->minValue(2)
                            ->maxValue(20)
                            ->default(5),
                        
                        Forms\Components\Select::make('fuel_type')
                            ->label('Tipe Bahan Bakar')
                            ->options([
                                'bensin' => 'Bensin',
                                'diesel' => 'Diesel',
                                'electric' => 'Electric',
                                'hybrid' => 'Hybrid',
                            ])
                            ->required()
                            ->native(false),
                        
                        Forms\Components\TextInput::make('engine_capacity')
                            ->label('Kapasitas Mesin (CC)')
                            ->required()
                            ->numeric()
                            ->suffix('CC')
                            ->placeholder('1500'),
                    ])->columns(3),
                
                Section::make('Fitur Mobil')
                    ->schema([
                        Forms\Components\CheckboxList::make('features')
                            ->label('Fitur')
                            ->options([
                                'ac' => 'Air Conditioner',
                                'power_steering' => 'Power Steering',
                                'airbag' => 'Airbag',
                                'abs' => 'ABS',
                                'audio_system' => 'Audio System',
                                'bluetooth' => 'Bluetooth',
                                'parking_sensor' => 'Parking Sensor',
                                'camera' => 'Rear Camera',
                                'sunroof' => 'Sunroof',
                                'leather_seats' => 'Leather Seats',
                                'cruise_control' => 'Cruise Control',
                                'keyless_entry' => 'Keyless Entry',
                            ])
                            ->columns(3)
                            ->gridDirection('row')
                            ->columnSpanFull(),
                    ]),
                
                Section::make('Harga & Stok')
                    ->schema([
                        Forms\Components\TextInput::make('daily_rent_price')
                            ->label('Harga Sewa per Hari')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->minValue(0),
                        
                        Forms\Components\TextInput::make('purchase_price')
                            ->label('Harga Beli')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->minValue(0),
                        
                        Forms\Components\TextInput::make('stock')
                            ->label('Stok')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->default(1),
                        
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'available' => 'Tersedia',
                                'rented' => 'Disewa',
                                'sold' => 'Terjual',
                            ])
                            ->required()
                            ->default('available')
                            ->native(false),
                    ])->columns(2),
                
                Section::make('Detail & Gambar')
                    ->schema([
                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(4)
                            ->columnSpanFull(),
                        
                        Forms\Components\FileUpload::make('image')
                            ->label('Gambar Mobil')
                            ->image()
                            ->directory('cars')
                            ->imageEditor()
                            ->maxSize(2048)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Gambar')
                    ->circular(),
                
                Tables\Columns\TextColumn::make('brand')
                    ->label('Brand')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('model')
                    ->label('Model')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('body_type')
                    ->label('Tipe Body')
                    ->badge()
                    ->color('info')
                    ->formatStateUsing(fn (string $state): string => strtoupper($state)),
                
                Tables\Columns\TextColumn::make('transmission')
                    ->label('Transmisi')
                    ->badge()
                    ->color('warning')
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),
                
                Tables\Columns\TextColumn::make('seats')
                    ->label('Kursi')
                    ->suffix(' seat')
                    ->alignCenter(),
                
                Tables\Columns\TextColumn::make('year')
                    ->label('Tahun')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('license_plate')
                    ->label('Plat Nomor')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('daily_rent_price')
                    ->label('Sewa/Hari')
                    ->money('IDR')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('average_rating')
                    ->label('Rating')
                    ->formatStateUsing(fn ($record) => 
                        $record->average_rating > 0 
                            ? '⭐ ' . number_format($record->average_rating, 1) . ' (' . $record->total_reviews . ')'
                            : 'Belum ada review'
                    )
                    ->color('warning'),
                
                Tables\Columns\TextColumn::make('stock')
                    ->label('Stok')
                    ->sortable()
                    ->badge()
                    ->color(fn (int $state): string => $state > 0 ? 'success' : 'danger'),
                
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'available' => 'success',
                        'rented' => 'warning',
                        'sold' => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'available' => 'Tersedia',
                        'rented' => 'Disewa',
                        'sold' => 'Terjual',
                    }),
            ])
            ->filters([
                SelectFilter::make('body_type')
                    ->label('Tipe Body')
                    ->options([
                        'sedan' => 'Sedan',
                        'suv' => 'SUV',
                        'mpv' => 'MPV',
                        'hatchback' => 'Hatchback',
                        'pickup' => 'Pickup',
                    ]),
                
                SelectFilter::make('transmission')
                    ->label('Transmisi')
                    ->options([
                        'manual' => 'Manual',
                        'automatic' => 'Automatic',
                        'cvt' => 'CVT',
                    ]),
                
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'available' => 'Tersedia',
                        'rented' => 'Disewa',
                        'sold' => 'Terjual',
                    ]),
                
                SelectFilter::make('brand')
                    ->label('Brand')
                    ->options(function () {
                        return Car::distinct()->pluck('brand', 'brand');
                    }),
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
            'index' => Pages\ListCars::route('/'),
            'create' => Pages\CreateCar::route('/create'),
            'edit' => Pages\EditCar::route('/{record}/edit'),
        ];
    }
    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'available')->count();
    }
}