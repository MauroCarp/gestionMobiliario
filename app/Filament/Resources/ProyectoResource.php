<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProyectoResource\Pages;
use App\Filament\Resources\ProyectoResource\RelationManagers;
use App\Models\Marca;
use App\Models\Proyecto;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;

class ProyectoResource extends Resource
{
    protected static ?string $model = Proyecto::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Operaciones';
    protected static ?string $modelLabel = 'Proyecto';
    protected static ?string $pluralModelLabel = 'Proyectos';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([

            // ── Sección principal ──────────────────────────────────────────
            Forms\Components\Section::make('Datos del Proyecto')
                ->schema([
                    Forms\Components\TextInput::make('codigo_interno')
                        ->label('Código interno')
                        ->required()
                        ->maxLength(50)
                        ->unique(ignoreRecord: true)
                        ->placeholder('Ej: PROY-CHERY-001')
                        ->columnSpan(1),

                    Forms\Components\Select::make('marca_id')
                        ->label('Marca')
                        ->options(fn () => Marca::where('activo', true)->orderBy('nombre')->pluck('nombre', 'id'))
                        ->searchable()
                        ->preload()
                        ->required()
                        ->live()
                        ->columnSpan(1),

                    Forms\Components\Textarea::make('observaciones')
                        ->label('Observaciones')
                        ->rows(3)
                        ->columnSpanFull(),
                ])
                ->columns(2),

            // ── Info de la marca + manual del proyecto ──────────────────
            Forms\Components\Section::make('Marca y Manual')
                ->schema([
                    Forms\Components\Placeholder::make('_logo')
                        ->label('Logo de la marca')
                        ->content(function (Get $get): HtmlString {
                            $marca = Marca::find($get('marca_id'));
                            if (!$marca?->logo) {
                                return new HtmlString(
                                    '<span class="text-sm text-gray-400 italic">Sin logo cargado en la marca</span>'
                                );
                            }
                            $url = asset('storage/' . $marca->logo);
                            return new HtmlString(
                                '<img src="' . e($url) . '" alt="Logo ' . e($marca->nombre) . '" '
                                . 'class="h-24 max-w-xs object-contain rounded-lg border border-gray-200 p-1 bg-white shadow-sm" />'
                            );
                        })
                        ->columnSpan(1),

                    Forms\Components\FileUpload::make('manual_pdf')
                        ->label('Manual de marca del proyecto (PDF)')
                        ->helperText('Puede variar según el proyecto aunque sea la misma marca')
                        ->directory('proyectos/manuales')
                        ->disk('public')
                        ->acceptedFileTypes(['application/pdf'])
                        ->downloadable()
                        ->openable()
                        ->columnSpan(1),
                ])
                ->columns(2)
                ->visible(fn (Get $get): bool => filled($get('marca_id')))
                ->collapsible(),

            // ── Mobiliarios asignados al proyecto ──────────────────────────
            Forms\Components\Section::make('Mobiliarios del Proyecto')
                ->description('Defina los tipos de mobiliario que forman parte de este proyecto. Al crear un presupuesto, solo se mostrarán estos mobiliarios.')
                ->schema([
                    Forms\Components\Repeater::make('mobiliariosPivot')
                        ->relationship()
                        ->schema([
                            Forms\Components\Select::make('mobiliario_id')
                                ->label('Mobiliario')
                                ->relationship('mobiliario', 'nombre')
                                ->getOptionLabelFromRecordUsing(
                                    fn ($record) => "[{$record->codigo_interno}] {$record->nombre}"
                                )
                                ->searchable()
                                ->preload()
                                ->required()
                                ->columnSpanFull(),
                        ])
                        ->columns(1)
                        ->addActionLabel('+ Agregar mobiliario')
                        ->defaultItems(0)
                        ->reorderable(false)
                        ->itemLabel(fn (array $state): ?string =>
                            isset($state['mobiliario_id'])
                                ? \App\Models\Mobiliario::find($state['mobiliario_id'])?->nombre
                                : null
                        )
                        ->collapsible()
                        ->label(''),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('codigo_interno')
                    ->searchable()->sortable()->badge(),
                Tables\Columns\TextColumn::make('marca.nombre')
                    ->label('Marca')
                    ->badge()
                    ->color('primary')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('mobiliarios_count')
                    ->label('Mobiliarios')
                    ->counts('mobiliarios')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i')->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('marca_id')
                    ->label('Marca')
                    ->relationship('marca', 'nombre')
                    ->searchable()
                    ->preload(),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelationManagers(): array
    {
        return [
            RelationManagers\AgenciasRelationManager::class,
            RelationManagers\HistorialRelationManager::class,
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListProyectos::route('/'),
            'create' => Pages\CreateProyecto::route('/create'),
            'edit'   => Pages\EditProyecto::route('/{record}/edit'),
        ];
    }
}
