<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Pago
 * 
 * @property int $idpagos
 * @property Carbon $fecha_pago
 * @property float $monto_pago
 * @property string|null $Descripcion_pago
 * @property int $Ordenes_compras_idOrden_compra
 * 
 * @property OrdenesCompra $ordenes_compra
 *
 * @package App\Models
 */
class Pago extends Model
{
	protected $table = 'pagos';
	protected $primaryKey = 'idpagos';
	public $timestamps = false;

	protected $casts = [
		'fecha_pago' => 'datetime',
		'monto_pago' => 'float',
		'Ordenes_compras_idOrden_compra' => 'int'
	];

	protected $fillable = [
		'fecha_pago',
		'monto_pago',
		'Descripcion_pago',
		'Ordenes_compras_idOrden_compra'
	];

	public function ordenes_compra()
	{
		return $this->belongsTo(OrdenesCompra::class, 'Ordenes_compras_idOrden_compra');
	}
}
