<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProyectoResource\Pages;
use App\Filament\Resources\ProyectoResource\RelationManagers;
use App\Filament\Support\MarcaLogoUpload;
use App\Models\Marca;
use App\Models\Proyecto;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class ProyectoResource extends Resource
{
    protected static ?string $model = Proyecto::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Operaciones';
    protected static ?string $modelLabel = 'Proyecto';
    protected static ?string $pluralModelLabel = 'Proyectos';
    protected static ?int $navigationSort = 1;

    public static function manualesParaVista(Proyecto $proyecto): array
    {
        return collect((array) $proyecto->manual_pdf)
            ->filter()
            ->map(function (string $file): array {
                $base = basename($file);
                $displayName = strlen($base) > 37 ? substr($base, 37) : $base;

                return [
                    'url'    => Storage::disk('public')->url($file),
                    'nombre' => $displayName,
                ];
            })
            ->values()
            ->all();
    }

    public static function verManualesPageAction(\Closure $getRecord): \Filament\Actions\Action
    {
        return \Filament\Actions\Action::make('ver_manuales')
            ->label('Ver manuales')
            ->icon('heroicon-o-document-text')
            ->color('info')
            ->visible(fn (): bool => $getRecord()->tieneManuales())
            ->modalHeading(fn (): string => 'Manuales de marca — ' . ($getRecord()->marca?->nombre ?? $getRecord()->codigo_interno))
            ->modalContent(fn (): HtmlString => new HtmlString(
                view('filament.proyecto.manuales-list', [
                    'manuales' => static::manualesParaVista($getRecord()),
                ])->render()
            ))
            ->modalSubmitAction(false)
            ->modalCancelActionLabel('Cerrar');
    }

    public static function verManualesTableAction(): Tables\Actions\Action
    {
        return Tables\Actions\Action::make('ver_manuales')
            ->label('Ver manuales')
            ->icon('heroicon-o-document-text')
            ->color('info')
            ->visible(fn (Proyecto $record): bool => $record->tieneManuales())
            ->modalHeading(fn (Proyecto $record): string => 'Manuales de marca — ' . ($record->marca?->nombre ?? $record->codigo_interno))
            ->modalContent(fn (Proyecto $record): HtmlString => new HtmlString(
                view('filament.proyecto.manuales-list', [
                    'manuales' => static::manualesParaVista($record),
                ])->render()
            ))
            ->modalSubmitAction(false)
            ->modalCancelActionLabel('Cerrar');
    }

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
                        ->afterStateUpdated(function ($state, Forms\Set $set, $livewire): void {
                            if (! ($livewire instanceof \App\Filament\Resources\ProyectoResource\Pages\CreateProyecto)) {
                                return;
                            }
                            if (! $state) {
                                $set('mobiliariosPivot', []);
                                return;
                            }
                            $mobiliarios = \App\Models\Mobiliario::whereHas(
                                'marcas',
                                fn (\Illuminate\Database\Eloquent\Builder $query) => $query->where('marcas.id', $state),
                            )
                                ->orderBy('nombre')
                                ->get();
                            $set('mobiliariosPivot', $mobiliarios->map(fn ($m) => [
                                'mobiliario_id' => $m->id,
                                'cantidad'      => 1,
                                'observaciones' => null,
                            ])->values()->toArray());
                        })
                        ->createOptionForm([
                            Forms\Components\TextInput::make('nombre')
                                ->label('Nombre de la marca')
                                ->required()
                                ->maxLength(255),
                            MarcaLogoUpload::make(),
                            Forms\Components\Toggle::make('activo')
                                ->label('Activa')
                                ->default(true),
                        ])
                        ->createOptionUsing(fn (array $data) => Marca::create($data)->getKey())
                        ->editOptionForm([
                            Forms\Components\TextInput::make('nombre')
                                ->label('Nombre de la marca')
                                ->required()
                                ->maxLength(255),
                            MarcaLogoUpload::make(),
                            Forms\Components\Toggle::make('activo')
                                ->label('Activa')
                                ->default(true),
                        ])
                        ->getSelectedRecordUsing(fn ($state): ?Marca => Marca::find($state))
                        ->fillEditOptionActionFormUsing(fn ($component): array =>
                            $component->getSelectedRecord()?->attributesToArray() ?? []
                        )
                        ->updateOptionUsing(function (array $data, $form): void {
                            $form->getRecord()?->update($data);
                        })
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
                        ->label('Manuales de marca (PDF)')
                        ->helperText('Puede subir varios archivos. Pueden variar según el proyecto aunque sea la misma marca.')
                        ->directory('proyectos/manuales')
                        ->disk('public')
                        ->multiple()
                        ->reorderable()
                        ->getUploadedFileNameForStorageUsing(
                            fn ($file): string => Str::uuid() . '_' . $file->getClientOriginalName()
                        )
                        ->rules(['mimes:pdf', 'max:30720'])
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
                                ->options(function (Forms\Get $get) {
                                    $marcaId = $get('../../marca_id');
                                    $query = \App\Models\Mobiliario::query()->orderBy('nombre');
                                    if ($marcaId) {
                                        $query->whereHas(
                                            'marcas',
                                            fn (\Illuminate\Database\Eloquent\Builder $marcasQuery) => $marcasQuery->where('marcas.id', $marcaId),
                                        );
                                    }
                                    return $query->get()->mapWithKeys(
                                        fn ($m) => [$m->id => "[{$m->codigo_interno}] {$m->nombre}"]
                                    );
                                })
                                ->getOptionLabelUsing(function ($value): ?string {
                                    $m = \App\Models\Mobiliario::find($value);
                                    return $m ? "[{$m->codigo_interno}] {$m->nombre}" : null;
                                })
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

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Infolists\Components\Section::make('Datos del proyecto')->schema([
                Infolists\Components\TextEntry::make('codigo_interno')
                    ->label('Código interno')
                    ->badge()
                    ->color('primary'),

                Infolists\Components\TextEntry::make('marca.nombre')
                    ->label('Marca')
                    ->placeholder('—'),

                Infolists\Components\ImageEntry::make('marca.logo')
                    ->label('Logo de la marca')
                    ->disk('public')
                    ->height(80)
                    ->visible(fn (Proyecto $record): bool => filled($record->marca?->logo)),

                Infolists\Components\TextEntry::make('manuales_resumen')
                    ->label('Manuales de marca')
                    ->getStateUsing(function (Proyecto $record): string {
                        $manuales = static::manualesParaVista($record);

                        if (empty($manuales)) {
                            return 'Sin manuales cargados';
                        }

                        return count($manuales) . ' archivo(s): ' . collect($manuales)
                            ->pluck('nombre')
                            ->implode(', ');
                    })
                    ->placeholder('Sin manuales cargados'),

                Infolists\Components\TextEntry::make('mobiliarios_count')
                    ->label('Mobiliarios asignados')
                    ->getStateUsing(fn (Proyecto $record): string => (string) $record->mobiliariosPivot()->count()),

                Infolists\Components\TextEntry::make('agencias_count')
                    ->label('Agencias')
                    ->getStateUsing(fn (Proyecto $record): string => (string) $record->agencias()->count()),

                Infolists\Components\TextEntry::make('created_at')
                    ->label('Creado')
                    ->date('d/m/Y'),

                Infolists\Components\TextEntry::make('observaciones')
                    ->placeholder('—')
                    ->columnSpanFull(),
            ])->columns(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('marca.logo')
                    ->label('Logo')
                    ->disk('public')
                    ->height(56)
                    ->extraImgAttributes(['style' => 'width: auto; max-width: 140px; object-fit: contain;'])
                    ->defaultImageUrl(fn () => 'https://ui-avatars.com/api/?name=M&background=e5e7eb&color=6b7280&size=64'),
                Tables\Columns\TextColumn::make('codigo_interno')
                    ->label('Código interno')
                    ->searchable()
                    ->sortable()
                    ->badge(),
                Tables\Columns\TextColumn::make('marca.nombre')
                    ->label('Marca')
                    ->searchable()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('marca_id')
                    ->label('Marca')
                    ->relationship('marca', 'nombre')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),

                static::verManualesTableAction(),

                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListProyectos::route('/'),
            'create' => Pages\CreateProyecto::route('/create'),
            'view'   => Pages\ViewProyecto::route('/{record}'),
            'edit'   => Pages\EditProyecto::route('/{record}/edit'),
        ];
    }
}
