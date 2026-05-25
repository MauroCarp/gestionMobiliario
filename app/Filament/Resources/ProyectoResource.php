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
                        ->createOptionForm([
                            Forms\Components\TextInput::make('nombre')
                                ->label('Nombre de la marca')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\FileUpload::make('logo')
                                ->label('Logo')
                                ->image()
                                ->directory('marcas/logos')
                                ->disk('public')
                                ->imageResizeMode('cover')
                                ->imageCropAspectRatio('16:9')
                                ->imageResizeTargetWidth(400)
                                ->imageResizeTargetHeight(225),
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
                            Forms\Components\FileUpload::make('logo')
                                ->label('Logo')
                                ->image()
                                ->directory('marcas/logos')
                                ->disk('public')
                                ->imageResizeMode('cover')
                                ->imageCropAspectRatio('16:9')
                                ->imageResizeTargetWidth(400)
                                ->imageResizeTargetHeight(225),
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
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\Action::make('ver_manuales')
                    ->label('Ver manuales')
                    ->icon('heroicon-o-document-text')
                    ->color('info')
                    ->visible(fn (Proyecto $record): bool => !empty($record->manual_pdf))
                    ->modalHeading(fn (Proyecto $record): string => 'Manuales — ' . ($record->marca?->nombre ?? $record->codigo_interno))
                    ->modalContent(fn (Proyecto $record): HtmlString => new HtmlString(
                        '<div class="space-y-2 py-2">' .
                        collect((array) $record->manual_pdf)
                            ->map(function (string $file): string {
                                $base = basename($file);
                                // El archivo se guarda como "{uuid}_{nombre_original}"
                                // UUID = 36 chars + 1 guión bajo = saltar 37 chars
                                $displayName = strlen($base) > 37 ? substr($base, 37) : $base;
                                return '<a href="' . e(Storage::disk('public')->url($file)) . '" '
                                    . 'target="_blank" rel="noopener noreferrer" '
                                    . 'class="flex items-center gap-2 p-3 rounded-lg border border-gray-200 '
                                    . 'hover:bg-gray-50 text-sm font-medium text-blue-600 hover:text-blue-800">'
                                    . '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>'
                                    . '<span>' . e($displayName) . '</span>'
                                    . '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-auto shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>'
                                    . '</a>';
                            })
                            ->implode('')
                        . '</div>'
                    ))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Cerrar'),
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
