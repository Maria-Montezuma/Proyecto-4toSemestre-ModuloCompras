<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Empleado
 * 
 * @property int $idEmpleados
 * @property string $nombre_empleado
 * @property string $apellido_empleado
 * @property string $cedula
 * 
 * @property Collection|Devolucione[] $devoluciones
 * @property Collection|OrdenesCompra[] $ordenes_compras
 * @property Collection|RecepcionesMercancia[] $recepciones_mercancias
 * @property Collection|Solicitude[] $solicitudes
 *
 * @package App\Models
 */
class Empleado extends Model
{
	protected $table = 'empleados';
	protected $primaryKey = 'idEmpleados';
	public $timestamps = false;

	protected $fillable = [
		'nombre_empleado',
		'apellido_empleado',
		'cedula'
	];

	public function devoluciones()
	{
		return $this->hasMany(Devolucione::class, 'Emplados_idEmplados');
	}

	public function ordenes_compras()
	{
		return $this->hasMany(OrdenesCompra::class, 'Empleados_idEmpleados');
	}

	public function recepciones_mercancias()
	{
		return $this->hasMany(RecepcionesMercancia::class, 'Empleados_idEmpleados');
	}

	public function solicitudes()
	{
		return $this->hasMany(Solicitude::class, 'Empleados_idEmpleados');
	}
}
