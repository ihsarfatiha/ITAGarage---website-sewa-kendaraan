<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Models\Transaction;
use App\Models\Motor;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    
    protected static ?string $navigationLabel = 'Transaksi';
    
    protected static ?string $navigationGroup = 'Manajemen Transaksi';
    
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Transaksi')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Customer')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->required(),
                                Forms\Components\TextInput::make('email')
                                    ->email()
                                    ->required(),
                                Forms\Components\TextInput::make('phone'),
                                Forms\Components\Textarea::make('address'),
                            ]),
                        
                        Forms\Components\Select::make('motor_id')
                            ->label('Motor')
                            ->relationship('motor', 'model', function (Builder $query) {
                                $query->where('status', 'available');
                            })
                            ->searchable()
                            ->preload()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $motor = Motor::find($state);
                                    if ($motor) {
                                        $set('daily_rent_price_display', 'Rp ' . number_format($motor->daily_rent_price, 0, ',', '.'));
                                        $set('purchase_price_display', 'Rp ' . number_format($motor->purchase_price, 0, ',', '.'));
                                    }
                                }
                            })
                            ->getOptionLabelFromRecordUsing(fn (Motor $record) => "{$record->brand} {$record->model} ({$record->license_plate})"),
                        
                        Forms\Components\Placeholder::make('daily_rent_price_display')
                            ->label('Harga Sewa/Hari')
                            ->content(fn ($get) => $get('daily_rent_price_display') ?? '-'),
                        
                        Forms\Components\Placeholder::make('purchase_price_display')
                            ->label('Harga Beli')
                            ->content(fn ($get) => $get('purchase_price_display') ?? '-'),
                        
                        Forms\Components\Select::make('transaction_type')
                            ->label('Tipe Transaksi')
                            ->options([
                                'rent' => 'Peminjaman',
                                'purchase' => 'Pembelian',
                            ])
                            ->required()
                            ->reactive()
                            ->native(false),
                    ])->columns(2),
                
                Section::make('Detail Transaksi')
                    ->schema([
                        Forms\Components\DatePicker::make('start_date')
                            ->label('Tanggal Mulai')
                            ->required()
                            ->default(now())
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $endDate = $get('end_date');
                                if ($state && $endDate && $get('transaction_type') === 'rent') {
                                    $start = \Carbon\Carbon::parse($state);
                                    $end = \Carbon\Carbon::parse($endDate);
                                    $duration = $start->diffInDays($end) + 1;
                                    $set('duration_days', $duration);
                                    
                                    $motorId = $get('motor_id');
                                    if ($motorId) {
                                        $motor = Motor::find($motorId);
                                        $set('total_price', $motor->daily_rent_price * $duration);
                                    }
                                }
                            }),
                        
                        Forms\Components\DatePicker::make('end_date')
                            ->label('Tanggal Selesai')
                            ->visible(fn ($get) => $get('transaction_type') === 'rent')
                            ->required(fn ($get) => $get('transaction_type') === 'rent')
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $startDate = $get('start_date');
                                if ($state && $startDate && $get('transaction_type') === 'rent') {
                                    $start = \Carbon\Carbon::parse($startDate);
                                    $end = \Carbon\Carbon::parse($state);
                                    $duration = $start->diffInDays($end) + 1;
                                    $set('duration_days', $duration);
                                    
                                    $motorId = $get('motor_id');
                                    if ($motorId) {
                                        $motor = Motor::find($motorId);
                                        $set('total_price', $motor->daily_rent_price * $duration);
                                    }
                                }
                            }),
                        
                        Forms\Components\TextInput::make('duration_days')
                            ->label('Durasi (Hari)')
                            ->numeric()
                            ->visible(fn ($get) => $get('transaction_type') === 'rent')
                            ->disabled()
                            ->dehydrated(),
                        
                        Forms\Components\TextInput::make('total_price')
                            ->label('Total Harga')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->reactive()
                            ->afterStateHydrated(function ($state, callable $set, callable $get) {
                                if (!$state && $get('motor_id') && $get('transaction_type')) {
                                    $motor = Motor::find($get('motor_id'));
                                    if ($motor) {
                                        if ($get('transaction_type') === 'purchase') {
                                            $set('total_price', $motor->purchase_price);
                                        }
                                    }
                                }
                            }),
                        
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'pending' => 'Pending',
                                'approved' => 'Disetujui',
                                'ongoing' => 'Berlangsung',
                                'completed' => 'Selesai',
                                'cancelled' => 'Dibatalkan',
                            ])
                            ->required()
                            ->default('pending')
                            ->native(false),
                        
                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('motor.model')
                    ->label('Motor')
                    ->formatStateUsing(fn ($record) => "{$record->motor->brand} {$record->motor->model}")
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('transaction_type')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'rent' => 'info',
                        'purchase' => 'success',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'rent' => 'Peminjaman',
                        'purchase' => 'Pembelian',
                    }),
                
                Tables\Columns\TextColumn::make('start_date')
                    ->label('Tanggal Mulai')
                    ->date('d M Y')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('end_date')
                    ->label('Tanggal Selesai')
                    ->date('d M Y')
                    ->sortable()
                    ->placeholder('-'),
                
                Tables\Columns\TextColumn::make('duration_days')
                    ->label('Durasi')
                    ->suffix(' hari')
                    ->placeholder('-'),
                
                Tables\Columns\TextColumn::make('total_price')
                    ->label('Total Harga')
                    ->money('IDR')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'info',
                        'ongoing' => 'primary',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Pending',
                        'approved' => 'Disetujui',
                        'ongoing' => 'Berlangsung',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                    }),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('transaction_type')
                    ->label('Tipe Transaksi')
                    ->options([
                        'rent' => 'Peminjaman',
                        'purchase' => 'Pembelian',
                    ]),
                
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Disetujui',
                        'ongoing' => 'Berlangsung',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                    ]),
                
                Tables\Filters\Filter::make('start_date')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('until')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('start_date', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('start_date', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('approve')
                    ->label('Setujui')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->status === 'pending')
                    ->action(fn ($record) => $record->update(['status' => 'approved'])),
                
                Tables\Actions\Action::make('cancel')
                    ->label('Batalkan')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => in_array($record->status, ['pending', 'approved']))
                    ->action(fn ($record) => $record->update(['status' => 'cancelled'])),
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
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'pending')->count();
    }
    
    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::where('status', 'pending')->count() > 0 ? 'warning' : 'success';
    }
}