<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Devolucione
 * 
 * @property int $idDevoluciones
 * @property Carbon $fecha_devolucion
 * @property int $Emplados_idEmplados
 * @property int $Recepciones_mercancias_idRecepcion_mercancia
 * 
 * @property Empleado $empleado
 * @property RecepcionesMercancia $recepciones_mercancia
 * @property Collection|DetallesDevolucione[] $detalles_devoluciones
 *
 * @package App\Models
 */
class Devolucione extends Model
{
	protected $table = 'devoluciones';
	protected $primaryKey = 'idDevoluciones';
	public $timestamps = false;

	protected $casts = [
		'fecha_devolucion' => 'datetime',
		'Emplados_idEmplados' => 'int',
		'Recepciones_mercancias_idRecepcion_mercancia' => 'int'
	];

	protected $fillable = [
		'fecha_devolucion',
		'Emplados_idEmplados',
		'Recepciones_mercancias_idRecepcion_mercancia'
	];

	public function empleado()
	{
		return $this->belongsTo(Empleado::class, 'Emplados_idEmplados');
	}

	public function recepciones_mercancia()
	{
		return $this->belongsTo(RecepcionesMercancia::class, 'Recepciones_mercancias_idRecepcion_mercancia');
	}

	public function detalles_devoluciones()
	{
		return $this->hasMany(DetallesDevolucione::class, 'Devoluciones_idDevoluciones');
	}
	public function getDevolucionStatus()
{
    $sobrante = false;
    $faltante = false;
    $dañado = false;
    $otro = false;

    foreach ($this->detalles_devoluciones as $detalle) {
        switch ($detalle->estado_devolucion) {
            case 1:
                $sobrante = true;
                break;
            case 2:
                $faltante = true;
                break;
            case 3:
                $dañado = true;
                break;
            case 0:
                $otro = true;
                break;
        }

        // Si se encuentran todos los estados, podemos salir del bucle
        if ($sobrante && $faltante && $dañado && $otro) {
            break;
        }
    }

    // Determinar el estado basado en las condiciones encontradas
    if ($sobrante && $faltante && $dañado && $otro) {
        return [
            'status' => 'Sob. + Falt. + Dañ. + Otro',
            'badge' => 'bg-dark',
            'statusNumero' => 5
        ];
    } elseif ($sobrante && $faltante && $dañado) {
        return [
            'status' => 'Sob. + Falt. + Dañ.',
            'badge' => 'bg-dark',
            'statusNumero' => 6
        ];
    } elseif ($sobrante && $faltante) {
        return [
            'status' => 'Sob. + Falt.',
            'badge' => 'bg-warning',
            'statusNumero' => 7
        ];
    } elseif ($sobrante && $dañado ) {
        return [
            'status' => 'Sob. + Dañ.',
            'badge' => 'bg-warning',
            'statusNumero' => 8
        ];
    } elseif ($faltante && $dañado ) {
        return [
            'status' => 'Falt. + Dañ.',
            'badge' => 'bg-warning',
            'statusNumero' => 9
        ];
    } elseif ($otro) {
        return [
            'status' => 'Otro',
            'badge' => 'bg-info me-2',
            'statusNumero' => 0
        ];
    } elseif ($sobrante) {
        return [
            'status' => 'Sobrante',
            'badge' => 'bg-success me-2',
            'statusNumero' => 1
        ];
    } elseif ($faltante) {
        return [
            'status' => 'Faltante',
            'badge' => 'bg-danger me-2',
            'statusNumero' => 2
        ];
    } elseif ($dañado ) {
        return [
            'status' => 'Dañado',
            'badge' => 'bg-danger me-2',
            'statusNumero' => 3
        ];
    } else {
        return [
            'status' => 'Sin Estado',
            'badge' => 'bg-secondary me-2',
            'statusNumero' => null
        ];
    }
}

}
