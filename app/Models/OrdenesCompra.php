<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class OrdenesCompra
 * 
 * @property int $idOrden_compra
 * @property Carbon $fecha_emision
 * @property Carbon $fecha_entraga
 * @property int $status
 * @property float $subtotal_pagar
 * @property float $total_pagar
 * @property Carbon|null $enviado_at
 * @property int $Empleados_idEmpleados
 * @property int $Proveedores_idProveedores
 * 
 * @property Empleado $empleado
 * @property Proveedore $proveedore
 * @property Collection|DetallesOrdenesCompra[] $detalles_ordenes_compras
 * @property Collection|RecepcionesMercancia[] $recepciones_mercancias
 *
 * @package App\Models
 */
class OrdenesCompra extends Model
{
	protected $table = 'ordenes_compras';
	protected $primaryKey = 'idOrden_compra';
	public $timestamps = false;

	protected $casts = [
		'fecha_emision' => 'datetime',
		'fecha_entraga' => 'datetime',
		'status' => 'int',
		'subtotal_pagar' => 'float',
		'total_pagar' => 'float',
		'enviado_at' => 'datetime',
		'Empleados_idEmpleados' => 'int',
		'Proveedores_idProveedores' => 'int'
	];

	protected $fillable = [
		'fecha_emision',
		'fecha_entraga',
		'status',
		'subtotal_pagar',
		'total_pagar',
		'enviado_at',
		'Empleados_idEmpleados',
		'Proveedores_idProveedores'
	];

	public function empleado()
	{
		return $this->belongsTo(Empleado::class, 'Empleados_idEmpleados');
	}

	public function proveedore()
	{
		return $this->belongsTo(Proveedore::class, 'Proveedores_idProveedores');
	}

	public function detalles_ordenes_compras()
	{
		return $this->hasMany(DetallesOrdenesCompra::class, 'Ordenes_compra_idOrden_compra');
	}

	public function recepciones_mercancias()
	{
		return $this->hasMany(RecepcionesMercancia::class, 'Ordenes_compras_idOrden_compra');
	}

	public function getEnviadoAtAttribute($value)
    {
        return $value ? Carbon::parse($value) : null;
    }

    public function esCancelable()
    {
        if (!$this->enviado_at) {
            return $this->status == 1;
        }
        return $this->status == 1 && Carbon::parse($this->enviado_at)->addMinutes(15)->isFuture();
    }

	public function actualizarEstadoSiNecesario()
    {
        if ($this->status == 1 && $this->enviado_at && Carbon::parse($this->enviado_at)->addMinutes(2)->isPast()) {
            $this->status = 2; // Asumiendo que 2 es el estado "recibido"
            $this->save();
        }
    }
}
