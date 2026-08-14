<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use App\Support\FilamentResourceVisibility;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserResource extends BaseResource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Administración';
    protected static ?string $modelLabel = 'Usuario';
    protected static ?string $pluralModelLabel = 'Usuarios';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Datos del usuario')->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nombre')->required()->maxLength(255),
                Forms\Components\TextInput::make('username')
                    ->label('Usuario (login)')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->regex('/^\S+$/')
                    ->helperText('Sin espacios. Ej: juan.perez'),
                Forms\Components\TextInput::make('email')
                    ->email()->required()->maxLength(255)->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $context) => $context === 'create')
                    ->label('Contraseña')
                    ->maxLength(255),
                Forms\Components\Toggle::make('activo')
                    ->label('Activo')->default(true),
            ])->columns(2),

            Forms\Components\Section::make('Roles')->schema([
                Forms\Components\CheckboxList::make('roles')
                    ->relationship('roles', 'name')
                    ->columns(2)
                    ->live()
                    ->label('Asignar roles'),
            ]),

            Forms\Components\Section::make('Resources visibles')
                ->description('Controla qué módulos del panel puede ver el usuario en el menú y por URL directa.')
                ->schema([
                    Forms\Components\Placeholder::make('admin_resources_info')
                        ->label('')
                        ->content('El Administrador siempre tiene acceso a todos los resources.')
                        ->visible(fn (Get $get, ?User $record): bool => static::formIncludesAdministradorRole($get, $record)),

                    Forms\Components\CheckboxList::make('visible_resources')
                        ->label('Módulos habilitados')
                        ->options(fn (): array => FilamentResourceVisibility::options())
                        ->columns(3)
                        ->helperText('La selección define exactamente qué módulos puede ver (resources y páginas). No otorga permisos de crear, editar ni eliminar.')
                        ->visible(fn (Get $get, ?User $record): bool => ! static::formIncludesAdministradorRole($get, $record)),
                ]),
        ]);
    }

    public static function formIncludesAdministradorRole(Get $get, ?User $record): bool
    {
        $roleIds = $get('roles');

        if (is_array($roleIds) && $roleIds !== []) {
            return Role::query()
                ->whereIn('id', $roleIds)
                ->where('name', 'Administrador')
                ->exists();
        }

        return $record?->hasRole('Administrador') ?? false;
    }

    public static function normalizeVisibleResourcesData(array $data): array
    {
        $roleIds = $data['roles'] ?? [];

        $isAdmin = Role::query()
            ->whereIn('id', (array) $roleIds)
            ->where('name', 'Administrador')
            ->exists();

        if ($isAdmin) {
            $data['visible_resources'] = null;

            return $data;
        }

        $data['visible_resources'] = array_values(array_unique($data['visible_resources'] ?? []));

        return $data;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('username')
                    ->label('Usuario')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()->sortable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->label('Roles')->badge()->separator(','),
                Tables\Columns\IconColumn::make('activo')
                    ->boolean()->label('Activo'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d/m/Y')->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('activo')->label('Estado'),
                Tables\Filters\SelectFilter::make('roles')
                    ->relationship('roles', 'name')
                    ->label('Rol'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit'   => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
