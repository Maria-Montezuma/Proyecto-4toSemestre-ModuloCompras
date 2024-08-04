<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Cargo
 * 
 * @property int $idCargos
 * @property string $nombre_cargo
 * 
 * @property Collection|Empleado[] $empleados
 *
 * @package App\Models
 */
class Cargo extends Model
{
	protected $table = 'cargos';
	protected $primaryKey = 'idCargos';
	public $timestamps = false;

	protected $fillable = [
		'nombre_cargo'
	];

	public function empleados()
	{
		return $this->belongsToMany(Empleado::class, 'empleados_has_cargos', 'Cargos_idCargos', 'Empleados_idEmpleados')
					->withPivot('idEmpleados_has_Cargos');
	}
}
