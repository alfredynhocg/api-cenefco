<?php

namespace App\Application\Cursos\Handlers;

use App\Application\Cursos\Commands\UpdateCursoCommand;
use App\Application\Cursos\DTOs\CursoDTO;
use App\Domain\Cursos\Contracts\CursoRepositoryInterface;
use App\Domain\Honorarios\Contracts\ConfigHonorarioRepositoryInterface;
use App\Domain\Honorarios\Exceptions\ConfigHonorarioNotFoundException;

class UpdateCursoHandler
{
    public function __construct(
        private readonly CursoRepositoryInterface           $repository,
        private readonly ConfigHonorarioRepositoryInterface $configHonorarioRepository,
    ) {}

    public function handle(UpdateCursoCommand $c): CursoDTO
    {
        $data = array_filter([
            'nombre_programa'          => $c->nombre_programa,
            'slug'                     => $c->slug,
            'descripcion'              => $c->descripcion,
            'objetivo'                 => $c->objetivo,
            'dirigido'                 => $c->dirigido,
            'requisitos'               => $c->requisitos,
            'inversion'                => $c->inversion,
            'costo_monto'              => $c->costo_monto,
            'creditaje'                => $c->creditaje,
            'nota'                     => $c->nota,
            'foto'                     => $c->foto,
            'titulo_documento1'        => $c->titulo_documento1,
            'documento1'               => $c->documento1,
            'imagen_banner_url'        => $c->imagen_banner_url,
            'imagen_alt'               => $c->imagen_alt,
            'url_video'                => $c->url_video,
            'url_whatsapp'             => $c->url_whatsapp,
            'url_whatsapp2'            => $c->url_whatsapp2,
            'id_tipoprograma'          => $c->id_tipoprograma,
            'categoria_web_id'         => $c->categoria_web_id,
            'formulario_id'               => $c->formulario_id,
            'area_id'                  => $c->area_id,
            'estado_web'               => $c->estado_web,
            'destacado'                => $c->destacado,
            'orden'                    => $c->orden,
            'meta_titulo'              => $c->meta_titulo,
            'meta_descripcion'         => $c->meta_descripcion,
            'mensaje_exito'            => $c->mensaje_exito,
            'id_plan'                  => $c->id_plan,
            'id_plandoc'               => $c->id_plandoc,
            'id_imp'                   => $c->id_imp,
            'convenio_id'              => $c->convenio_id,
            'vendedor_id'              => $c->vendedor_id,
            'inicio_actividades'       => $c->inicio_actividades,
            'finalizacion_actividades' => $c->finalizacion_actividades,
            'inicio_inscripciones'     => $c->inicio_inscripciones,
            'mes_facturacion'          => $c->mes_facturacion,
        ], fn ($v) => $v !== null);

        if ($c->imagenes !== null) {
            $data['imagenes'] = $c->imagenes;
        }

        $dto = $this->repository->update($c->id, $data);

        if ($c->tipo_honorario !== null) {
            $montoFijo = null;
            $montoPorDia = null;
            try {
                $existente   = $this->configHonorarioRepository->findByPrograma($c->id);
                $montoFijo   = $existente->monto_fijo;
                $montoPorDia = $existente->monto_por_dia;
            } catch (ConfigHonorarioNotFoundException) {

            }

            $this->configHonorarioRepository->upsert($c->id, [
                'tipo_honorario' => $c->tipo_honorario,
                'monto_fijo'     => $montoFijo,
                'monto_por_dia'  => $montoPorDia,
            ]);

            $dto = $this->repository->findById($c->id);
        }

        return $dto;
    }
}
