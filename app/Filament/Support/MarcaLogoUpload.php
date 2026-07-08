<?php

namespace App\Filament\Support;

use Filament\Forms\Components\FileUpload;

class MarcaLogoUpload
{
    public static function make(string $name = 'logo'): FileUpload
    {
        return FileUpload::make($name)
            ->label('Logo')
            ->image()
            ->directory('marcas/logos')
            ->disk('public')
            ->imageEditor()
            ->imageEditorAspectRatios([
                null,
                '4:3',
                '3:4',
                '1:1',
                '16:9',
            ])
            ->helperText('Podés editar/recortar manualmente la imagen antes de guardar; no se recorta automáticamente.')
            ->getUploadedFileUsing(function (FileUpload $component, string $file): ?array {
                $storage = $component->getDisk();

                if (! $storage->exists($file)) {
                    return null;
                }

                $mimeType = $storage->mimeType($file);
                $content = $storage->get($file);

                if ($content === false || $content === null) {
                    return null;
                }

                return [
                    'name' => basename($file),
                    'size' => $storage->size($file),
                    'type' => $mimeType,
                    'url'  => 'data:' . $mimeType . ';base64,' . base64_encode($content),
                ];
            });
    }
}
