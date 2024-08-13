<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class RecepcionesMercancia
 * 
 * @property int $idRecepcion_mercancia
 * @property Carbon $fecha_recepcion
 * @property int $Empleados_idEmpleados
 * @property int $Ordenes_compras_idOrden_compra
 * 
 * @property Empleado $empleado
 * @property OrdenesCompra $ordenes_compra
 * @property Collection|DetallesRecepcionesMercancia[] $detalles_recepciones_mercancias
 * @property Collection|Devolucione[] $devoluciones
 *
 * @package App\Models
 */
class RecepcionesMercancia extends Model
{
	protected $table = 'recepciones_mercancias';
	protected $primaryKey = 'idRecepcion_mercancia';
	public $timestamps = false;

	protected $casts = [
		'fecha_recepcion' => 'datetime',
		'Empleados_idEmpleados' => 'int',
		'Ordenes_compras_idOrden_compra' => 'int'
	];

	protected $fillable = [
		'fecha_recepcion',
		'Empleados_idEmpleados',
		'Ordenes_compras_idOrden_compra'
	];

	public function empleado()
	{
		return $this->belongsTo(Empleado::class, 'Empleados_idEmpleados');
	}

	public function ordenes_compra()
	{
		return $this->belongsTo(OrdenesCompra::class, 'Ordenes_compras_idOrden_compra');
	}

	public function detalles_recepciones_mercancias()
	{
		return $this->hasMany(DetallesRecepcionesMercancia::class, 'Recepciones_mercancias_idRecepcion_mercancia');
	}

	public function devoluciones()
	{
		return $this->hasMany(Devolucione::class, 'Recepciones_mercancias_idRecepcion_mercancia');
	}

	
	public function getStatus()
    {
        $aceptado = false;
        $rechazado = false;
        
        foreach ($this->detalles_recepciones_mercancias as $detalle) {
            if ($detalle->status_recepcion == 1) {
                $aceptado = true;
            } elseif ($detalle->status_recepcion == 0) {
                $rechazado = true;
            }

            if ($aceptado && $rechazado) {
                break;
            }
        }

        if ($aceptado && $rechazado) {
            return [
                'status' => 'Parcial',
                'badge' => 'bg-dark',
            ];
        } elseif ($aceptado) {
            return [
                'status' => 'Aceptado',
                'badge' => 'bg-success me-2',
                'statusNumero' => 1
            ];
        } elseif ($rechazado) {
            return [
                'status' => 'Rechazado',
                'badge' => 'bg-danger me-2',
                'statusNumero' => 0
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
