<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class EmpleadosHasCargo
 * 
 * @property int $idEmpleados_has_Cargos
 * @property int $Empleados_idEmpleados
 * @property int $Cargos_idCargos
 * 
 * @property Cargo $cargo
 * @property Empleado $empleado
 *
 * @package App\Models
 */
class EmpleadosHasCargo extends Model
{
	protected $table = 'empleados_has_cargos';
	protected $primaryKey = 'idEmpleados_has_Cargos';
	public $timestamps = false;

	protected $casts = [
		'Empleados_idEmpleados' => 'int',
		'Cargos_idCargos' => 'int'
	];

	protected $fillable = [
		'Empleados_idEmpleados',
		'Cargos_idCargos'
	];

	public function cargo()
	{
		return $this->belongsTo(Cargo::class, 'Cargos_idCargos');
	}

	public function empleado()
	{
		return $this->belongsTo(Empleado::class, 'Empleados_idEmpleados');
	}
}
