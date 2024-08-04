<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Solicitude
 * 
 * @property int $idSolicitudes
 * @property string|null $condicion
 * @property string $cotizacion
 * @property Carbon $fecha_solicitud
 * @property int $Empleados_idEmpleados
 * 
 * @property Empleado $empleado
 * @property Collection|Proveedore[] $proveedores
 *
 * @package App\Models
 */
class Solicitude extends Model
{
	protected $table = 'solicitudes';
	protected $primaryKey = 'idSolicitudes';
	public $timestamps = false;

	protected $casts = [
		'fecha_solicitud' => 'datetime',
		'Empleados_idEmpleados' => 'int'
	];

	protected $fillable = [
		'condicion',
		'cotizacion',
		'fecha_solicitud',
		'Empleados_idEmpleados'
	];

	public function empleado()
	{
		return $this->belongsTo(Empleado::class, 'Empleados_idEmpleados');
	}

	public function proveedores()
	{
		return $this->belongsToMany(Proveedore::class, 'solicitudes_has_proveedores', 'Solicitudes_idSolicitudes', 'Proveedores_idProveedores')
					->withPivot('idSolicitudes_has_Proveedores');
	}
}
