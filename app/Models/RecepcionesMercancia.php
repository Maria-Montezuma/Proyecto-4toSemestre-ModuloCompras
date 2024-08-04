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
 * @property int $status
 * @property int $cantidad_recibida
 * @property int $Empleados_idEmpleados
 * @property int $Ordenes_compras_idOrden_compra
 * 
 * @property Empleado $empleado
 * @property OrdenesCompra $ordenes_compra
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
		'status' => 'int',
		'cantidad_recibida' => 'int',
		'Empleados_idEmpleados' => 'int',
		'Ordenes_compras_idOrden_compra' => 'int'
	];

	protected $fillable = [
		'fecha_recepcion',
		'status',
		'cantidad_recibida',
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

	public function devoluciones()
	{
		return $this->hasMany(Devolucione::class, 'Recepciones_mercancias_idRecepcion_mercancia');
	}
}
