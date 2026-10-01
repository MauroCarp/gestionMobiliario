<?php

namespace App\Services;

use App\Models\Presupuesto;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class PresupuestoPdfCache
{
    public function __construct(private ?string $directorio = null)
    {
        $this->directorio ??= storage_path('app/pdf-cache');
    }

    public function clave(Presupuesto $presupuesto, string $tipo, ?bool $conPrecios): string
    {
        $precios = match ($conPrecios) {
            true => 'con-precios',
            false => 'sin-precios',
            default => 'sin-flag',
        };

        return $presupuesto->id.'-'.$tipo.'-'.$precios;
    }

    public function huella(Presupuesto $presupuesto, string $tipo, ?bool $conPrecios, array $opciones = []): string
    {
        $agencia = $presupuesto->relationLoaded('agencia') ? $presupuesto->agencia : null;
        $proyecto = $agencia?->relationLoaded('proyecto') ? $agencia->proyecto : null;
        $marca = $proyecto?->relationLoaded('marca') ? $proyecto->marca : null;

        $payload = [
            'tipo' => $tipo,
            'precios' => $conPrecios,
            'opciones' => $opciones,
            'vista' => $this->mtime(resource_path($tipo === 'produccion' ? 'views/pdf/produccion.blade.php' : 'views/pdf/presupuesto.blade.php')),
            'logo_empresa' => $this->mtime(public_path('images/logo-empresa.png')),
            'logistica' => $this->mtime(storage_path('app/public/logistica_instalacion/logistica_instalacion.png')),
            'presupuesto' => $this->sello($presupuesto, [
                'codigo', 'estado', 'fecha_emision', 'fecha_vencimiento', 'metodo_pago',
                'logistica_instalacion_propia', 'logistica_leyenda', 'logistica_costo', 'observaciones', 'version',
            ]),
            'agencia' => $this->sello($agencia, ['nombre', 'direccion', 'responsable']),
            'ciudad' => $agencia?->relationLoaded('ciudad') ? $agencia->ciudad?->nombre : null,
            'provincia' => $agencia?->relationLoaded('provincia') ? $agencia->provincia?->nombre : null,
            'proyecto' => $this->sello($proyecto, ['nombre']),
            'marca' => $this->sello($marca, ['nombre', 'logo']),
            'logo_marca' => $marca?->logo ? $this->mtime(public_path('storage/'.ltrim($marca->logo, '/'))) : null,
            'items' => $presupuesto->relationLoaded('items')
                ? $presupuesto->items->map(fn ($item) => $this->selloItem($item))->values()->all()
                : [],
        ];

        return sha1((string) json_encode($payload));
    }

    public function rutaSiCoincide(string $clave, string $huella): ?string
    {
        $pdf = $this->rutaPdf($clave);
        $stamp = $this->rutaStamp($clave);

        if (! is_file($pdf) || ! is_file($stamp)) {
            return null;
        }

        $guardada = file_get_contents($stamp);

        return $guardada === $huella ? $pdf : null;
    }

    public function guardar(string $clave, string $huella, string $contenido): string
    {
        File::ensureDirectoryExists($this->directorio);

        $pdf = $this->rutaPdf($clave);
        file_put_contents($pdf, $contenido, LOCK_EX);
        file_put_contents($this->rutaStamp($clave), $huella, LOCK_EX);

        return $pdf;
    }

    public function rutaImagen(?Media $media): ?string
    {
        if ($media === null) {
            return null;
        }

        try {
            if ($media->hasGeneratedConversion('thumb')) {
                $miniatura = $media->getPath('thumb');
                if (is_file($miniatura)) {
                    return $miniatura;
                }
            }

            $original = $media->getPath();

            return is_file($original) ? $original : null;
        } catch (\Throwable) {
            return null;
        }
    }

    public function imagenItemBase64(Model $item): ?string
    {
        try {
            $media = $this->mediaDelItem($item);

            return $this->archivoABase64($this->rutaImagen($media), $media?->mime_type);
        } catch (\Throwable) {
            return null;
        }
    }

    public function archivoABase64(?string $path, ?string $mimeFallback = null): ?string
    {
        if ($path === null || ! is_file($path)) {
            return null;
        }

        $contenido = file_get_contents($path);
        if ($contenido === false) {
            return null;
        }

        $mime = mime_content_type($path) ?: $mimeFallback ?: 'application/octet-stream';

        return 'data:'.$mime.';base64,'.base64_encode($contenido);
    }

    public function marcaDeAguaBase64(float $opacidad): ?string
    {
        $origen = public_path('images/logo-empresa.png');
        if (! is_file($origen)) {
            return null;
        }

        if (! function_exists('imagecreatefromstring')) {
            return $this->archivoABase64($origen);
        }

        $sufijo = str_replace('.', '', (string) $opacidad);
        $destino = $this->directorio.'/watermark-'.$sufijo.'-'.$this->mtime($origen).'.png';

        if (! is_file($destino)) {
            File::ensureDirectoryExists($this->directorio);
            $this->generarMarcaDeAgua($origen, $destino, $opacidad);
        }

        foreach (glob($this->directorio.'/watermark-'.$sufijo.'-*.png') ?: [] as $viejo) {
            if ($viejo !== $destino && is_file($viejo)) {
                unlink($viejo);
            }
        }

        return is_file($destino) ? $this->archivoABase64($destino, 'image/png') : $this->archivoABase64($origen);
    }

    private function generarMarcaDeAgua(string $origen, string $destino, float $opacidad): void
    {
        $contenido = file_get_contents($origen);
        if ($contenido === false) {
            return;
        }

        $fuente = @imagecreatefromstring($contenido);
        if ($fuente === false) {
            return;
        }

        $ancho = imagesx($fuente);
        $alto = imagesy($fuente);
        $nuevoAncho = 280;
        $nuevoAlto = max(1, (int) round($alto * ($nuevoAncho / max(1, $ancho))));

        $escalada = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
        imagealphablending($escalada, false);
        imagesavealpha($escalada, true);
        $transparente = imagecolorallocatealpha($escalada, 0, 0, 0, 127);
        imagefilledrectangle($escalada, 0, 0, $nuevoAncho, $nuevoAlto, $transparente);
        imagecopyresampled($escalada, $fuente, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
        imagedestroy($fuente);

        $this->aplicarOpacidad($escalada, $opacidad);

        $rotada = imagerotate($escalada, 25, imagecolorallocatealpha($escalada, 0, 0, 0, 127));
        imagedestroy($escalada);

        if ($rotada === false) {
            return;
        }

        imagealphablending($rotada, false);
        imagesavealpha($rotada, true);
        imagepng($rotada, $destino);
        imagedestroy($rotada);
    }

    private function aplicarOpacidad(\GdImage $imagen, float $opacidad): void
    {
        $opacidad = max(0, min(1, $opacidad));
        $ancho = imagesx($imagen);
        $alto = imagesy($imagen);

        for ($y = 0; $y < $alto; $y++) {
            for ($x = 0; $x < $ancho; $x++) {
                $color = imagecolorat($imagen, $x, $y);
                $alfa = ($color >> 24) & 0x7F;
                $nuevoAlfa = (int) round(127 - ((127 - $alfa) * $opacidad));
                $nuevo = imagecolorallocatealpha(
                    $imagen,
                    ($color >> 16) & 0xFF,
                    ($color >> 8) & 0xFF,
                    $color & 0xFF,
                    max(0, min(127, $nuevoAlfa))
                );
                imagesetpixel($imagen, $x, $y, $nuevo);
            }
        }
    }

    private function mediaDelItem(Model $item): ?Media
    {
        try {
            return $item->mobiliario
                ? $item->mobiliario->getFirstMedia('imagenes')
                : $item->insumo?->getFirstMedia('imagen');
        } catch (\Throwable) {
            return null;
        }
    }

    private function selloItem(Model $item): array
    {
        $mobiliario = $item->relationLoaded('mobiliario') ? $item->mobiliario : null;
        $insumo = $item->relationLoaded('insumo') ? $item->insumo : null;

        return [
            'item' => $this->sello($item, [
                'cantidad', 'precio_unitario', 'descripcion_override', 'observaciones', 'notas_manuales', 'orden', 'sector_id',
            ]),
            'sector' => $item->relationLoaded('sector') ? $this->sello($item->sector, ['nombre']) : null,
            'mobiliario' => $this->selloMobiliario($mobiliario),
            'insumo' => $this->selloInsumo($insumo),
            'imagen' => $this->selloMedia($this->mediaDelItem($item)),
        ];
    }

    private function selloMobiliario(?Model $mobiliario): ?array
    {
        if ($mobiliario === null) {
            return null;
        }

        return [
            'datos' => $this->sello($mobiliario, ['nombre', 'codigo_interno', 'descripcion', 'precio']),
            'categoria' => $mobiliario->relationLoaded('categoria') ? $this->sello($mobiliario->categoria, ['nombre']) : null,
            'atributos' => $mobiliario->relationLoaded('atributos')
                ? $mobiliario->atributos->map(fn ($atributo) => $this->sello($atributo, ['clave', 'valor']))->values()->all()
                : [],
            'composicion' => $mobiliario->relationLoaded('composicionTecnica')
                ? $mobiliario->composicionTecnica->map(fn ($comp) => [
                    'datos' => $this->sello($comp, ['cantidad', 'observaciones', 'activo']),
                    'insumo' => $comp->relationLoaded('insumo') ? $this->selloInsumo($comp->insumo) : null,
                ])->values()->all()
                : [],
            'flujos' => $mobiliario->relationLoaded('plantillaFlujos')
                ? $mobiliario->plantillaFlujos->map(fn ($plantilla) => [
                    'datos' => $this->sello($plantilla, ['nombre', 'activo']),
                    'etapas' => $plantilla->relationLoaded('etapas')
                        ? $plantilla->etapas->map(fn ($etapa) => [
                            'datos' => $this->sello($etapa, ['orden', 'dias_estimados', 'observaciones']),
                            'proceso' => $etapa->relationLoaded('tipoProceso') ? $this->sello($etapa->tipoProceso, ['nombre']) : null,
                            'tercero' => $etapa->relationLoaded('tercero') ? $this->sello($etapa->tercero, ['nombre']) : null,
                        ])->values()->all()
                        : [],
                ])->values()->all()
                : [],
        ];
    }

    private function selloInsumo(?Model $insumo): ?array
    {
        if ($insumo === null) {
            return null;
        }

        return [
            'datos' => $this->sello($insumo, ['nombre', 'codigo', 'descripcion', 'precio']),
            'unidad' => $insumo->relationLoaded('unidadMedida') ? $this->sello($insumo->unidadMedida, ['nombre']) : null,
            'categorias' => $insumo->relationLoaded('categoriasInsumo')
                ? $insumo->categoriasInsumo->map(fn ($categoria) => $this->sello($categoria, ['nombre']))->values()->all()
                : [],
            'marcas' => $insumo->relationLoaded('marcasSilla')
                ? $insumo->marcasSilla->map(fn ($marca) => $this->sello($marca, ['marca_id', 'nombre_fantasia', 'precio']))->values()->all()
                : [],
        ];
    }

    private function selloMedia(?Media $media): ?array
    {
        if ($media === null) {
            return null;
        }

        $ruta = $this->rutaImagen($media);

        return [
            'id' => $media->id,
            'actualizado' => $media->updated_at?->getTimestamp(),
            'archivo' => $ruta !== null && is_file($ruta) ? $this->mtime($ruta).':'.filesize($ruta) : null,
        ];
    }

    private function sello(?Model $modelo, array $atributos = []): ?array
    {
        if ($modelo === null) {
            return null;
        }

        $datos = [
            'id' => $modelo->getKey(),
            'actualizado' => $modelo->updated_at?->getTimestamp(),
        ];

        foreach ($atributos as $atributo) {
            $valor = $modelo->getAttribute($atributo);
            $datos[$atributo] = $valor instanceof \DateTimeInterface ? $valor->format('Y-m-d') : $valor;
        }

        return $datos;
    }

    private function mtime(string $path): ?int
    {
        return is_file($path) ? filemtime($path) : null;
    }

    private function rutaPdf(string $clave): string
    {
        return $this->directorio.'/'.$clave.'.pdf';
    }

    private function rutaStamp(string $clave): string
    {
        return $this->directorio.'/'.$clave.'.stamp';
    }
}
