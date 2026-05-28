<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProveedorResource\Pages;
use App\Models\Proveedor;
use App\Models\Rubro;
use App\Models\Provincia;
use App\Models\Ciudad;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProveedorResource extends Resource
{
    protected static ?string $model = Proveedor::class;
    protected static ?string $navigationIcon  = 'heroicon-o-truck';
    protected static ?string $navigationGroup = 'Configuración';
    protected static ?string $modelLabel      = 'Proveedor';
    protected static ?string $pluralModelLabel = 'Proveedores';
    protected static ?int    $navigationSort  = 10;

    public static function form(Form $form): Form
    {
        return $form->schema([

            Forms\Components\Section::make('Identificación')->schema([
                Forms\Components\TextInput::make('codigo')
                    ->label('Código')
                    ->disabled()
                    ->dehydrated(false)
                    ->placeholder('Se generará automáticamente (ej: PRV-01)')
                    ->hiddenOn('create'),
                Forms\Components\TextInput::make('razon_social')
                    ->label('Razón Social')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('nombre_comercial')
                    ->label('Nombre Comercial')
                    ->maxLength(255)
                    ->nullable(),
                Forms\Components\TextInput::make('cuit')
                    ->label('CUIT / Identificación Fiscal')
                    ->maxLength(50)
                    ->nullable(),
                Forms\Components\Select::make('condicion_iva')
                    ->label('Condición IVA')
                    ->options(Proveedor::CONDICIONES_IVA)
                    ->searchable()
                    ->nullable(),
                Forms\Components\Toggle::make('activo')
                    ->label('Activo')
                    ->default(true)
                    ->inline(false),
            ])->columns(2),

            Forms\Components\Section::make('Contacto y Ubicación')->schema([
                Forms\Components\TextInput::make('direccion')
                    ->label('Dirección')
                    ->maxLength(255)
                    ->nullable()
                    ->columnSpanFull(),
                Forms\Components\Select::make('provincia_id')
                    ->label('Provincia')
                    ->options(Provincia::orderBy('nombre')->pluck('nombre', 'id'))
                    ->searchable()
                    ->live()
                    ->nullable(),
                Forms\Components\Select::make('ciudad_id')
                    ->label('Ciudad')
                    ->options(function (callable $get) {
                        $provinciaId = $get('provincia_id');
                        if ($provinciaId) {
                            return Ciudad::where('provincia_id', $provinciaId)
                                ->orderBy('nombre')
                                ->pluck('nombre', 'id');
                        }
                        return [];
                    })
                    ->searchable()
                    ->disabled(fn (callable $get) => !$get('provincia_id'))
                    ->nullable(),
                Forms\Components\TextInput::make('telefono')
                    ->label('Teléfono')
                    ->tel()
                    ->maxLength(50)
                    ->nullable(),
                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->maxLength(255)
                    ->nullable(),
                Forms\Components\TextInput::make('persona_contacto')
                    ->label('Persona de Contacto')
                    ->maxLength(255)
                    ->nullable(),
            ])->columns(2),

            Forms\Components\Section::make('Comercial')->schema([
                Forms\Components\Select::make('rubro_id')
                    ->label('Rubro')
                    ->relationship('rubro', 'nombre', fn ($query) => $query->where('activo', true)->orderBy('nombre'))
                    ->searchable()
                    ->preload()
                    ->nullable()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('nombre')
                            ->label('Nombre')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Toggle::make('activo')
                            ->label('Activo')
                            ->default(true),
                    ])
                    ->createOptionUsing(fn (array $data) => Rubro::create($data)->getKey()),
                Forms\Components\TextInput::make('condicion_pago')
                    ->label('Condición de Pago')
                    ->maxLength(255)
                    ->nullable(),
                Forms\Components\FileUpload::make('lista_precio')
                    ->label('Lista de Precio')
                    ->disk('public')
                    ->directory('proveedores/listas-precio')
                    ->acceptedFileTypes([
                        'application/pdf',
                        'application/vnd.ms-excel',
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        'text/csv',
                    ])
                    ->maxSize(10240)
                    ->nullable()
                    ->columnSpanFull(),
            ])->columns(2),

            Forms\Components\Section::make('Observaciones')->schema([
                Forms\Components\Textarea::make('observaciones')
                    ->rows(4)
                    ->columnSpanFull()
                    ->nullable(),
            ]),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('codigo')
                    ->label('Código')
                    ->badge()
                    ->color('gray')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('razon_social')
                    ->label('Razón Social')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nombre_comercial')
                    ->label('Nombre Comercial')
                    ->placeholder('—')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cuit')
                    ->label('CUIT')
                    ->placeholder('—')
                    ->searchable(),
                Tables\Columns\TextColumn::make('condicion_iva')
                    ->label('Cond. IVA')
                    ->formatStateUsing(fn (?string $state): string => Proveedor::CONDICIONES_IVA[$state] ?? ($state ?? '—'))
                    ->badge()
                    ->color('info')
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('rubro.nombre')
                    ->label('Rubro')
                    ->placeholder('—')
                    ->searchable()
                    ->badge()
                    ->color('warning'),
                Tables\Columns\TextColumn::make('telefono')
                    ->label('Teléfono')
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->placeholder('—')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('persona_contacto')
                    ->label('Contacto')
                    ->placeholder('—')
                    ->searchable(),
                Tables\Columns\TextColumn::make('condicion_pago')
                    ->label('Cond. Pago')
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('lista_precio')
                    ->label('Lista Precio')
                    ->icon(fn ($state) => $state ? 'heroicon-o-document-text' : null)
                    ->color('success')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('activo')
                    ->label('Activo')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('rubro_id')
                    ->label('Rubro')
                    ->relationship('rubro', 'nombre'),
                Tables\Filters\SelectFilter::make('condicion_iva')
                    ->label('Condición IVA')
                    ->options(Proveedor::CONDICIONES_IVA),
                Tables\Filters\TernaryFilter::make('activo')
                    ->label('Estado')
                    ->trueLabel('Solo activos')
                    ->falseLabel('Solo inactivos')
                    ->native(false),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('razon_social');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListProveedores::route('/'),
            'create' => Pages\CreateProveedor::route('/create'),
            'edit'   => Pages\EditProveedor::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class]);
    }
}
