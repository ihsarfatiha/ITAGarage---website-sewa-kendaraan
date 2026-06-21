<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MotorResource\Pages;
use App\Models\Motor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Tables\Filters\SelectFilter;

class MotorResource extends Resource
{
    protected static ?string $model = Motor::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';
    
    protected static ?string $navigationLabel = 'Motor';
    
    protected static ?string $navigationGroup = 'Manajemen Data';
    
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Motor')
                    ->schema([
                        Forms\Components\TextInput::make('brand')
                            ->label('Brand')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Honda, Yamaha, Suzuki, dll'),
                        
                        Forms\Components\TextInput::make('model')
                            ->label('Model')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Vario 125, NMAX, dll'),
                        
                        Forms\Components\Select::make('type')
                            ->label('Tipe Motor')
                            ->options([
                                'matic' => 'Matic',
                                'manual' => 'Manual',
                                'sport' => 'Sport',
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
                
                Section::make('Harga & Stok')
                    ->schema([
                        Forms\Components\TextInput::make('daily_rent_price')
                            ->label('Harga Sewa per Hari')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->minValue(0)
                            ->default(0),
                        
                        Forms\Components\TextInput::make('purchase_price')
                            ->label('Harga Beli')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->minValue(0)
                            ->default(0),
                        
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
                            ->label('Gambar Motor')
                            ->image()
                            ->disk('public')          
                            ->visibility('public')    
                            ->directory('motors')
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
                    ->circular()
                    ->disk('public')          // <--- Wajib: Simpan di disk public
                    ->visibility('public')    
                    ->defaultImageUrl(url('/images/motor-placeholder.png')),
                
                Tables\Columns\TextColumn::make('brand')
                    ->label('Brand')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('model')
                    ->label('Model')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'matic' => 'success',
                        'manual' => 'warning',
                        'sport' => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),
                
                Tables\Columns\TextColumn::make('year')
                    ->label('Tahun')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('license_plate')
                    ->label('Plat Nomor')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('daily_rent_price')
                    ->label('Harga Sewa/Hari')
                    ->money('IDR')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('purchase_price')
                    ->label('Harga Beli')
                    ->money('IDR')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
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
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                // Tambahkan di bagian columns
                Tables\Columns\TextColumn::make('average_rating')
                    ->label('Rating')
                    ->formatStateUsing(fn ($record) => 
                                $record->average_rating > 0 
                     ? '⭐ ' . number_format($record->average_rating, 1) . ' (' . $record->total_reviews . ')'
                        : 'Belum ada review'
                    )
    ->color('warning'),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('Tipe')
                    ->options([
                        'matic' => 'Matic',
                        'manual' => 'Manual',
                        'sport' => 'Sport',
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
                    ->options([
                        'Honda' => 'Honda',
                        'Yamaha' => 'Yamaha',
                        'Suzuki' => 'Suzuki',
                        'Kawasaki' => 'Kawasaki',
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
            'index' => Pages\ListMotors::route('/'),
            'create' => Pages\CreateMotor::route('/create'),
            'edit' => Pages\EditMotor::route('/{record}/edit'),
        ];
    }
    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'available')->count();
    }
}