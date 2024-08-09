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
 * @property int $status_general_devolucion
 * 
 * @property Empleado $empleado
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
		'status_general_devolucion' => 'int'
	];

	protected $fillable = [
		'fecha_devolucion',
		'Emplados_idEmplados',
		'status_general_devolucion'
	];

	public function empleado()
	{
		return $this->belongsTo(Empleado::class, 'Emplados_idEmplados');
	}

	public function detalles_devoluciones()
	{
		return $this->hasMany(DetallesDevolucione::class, 'Devoluciones_idDevoluciones');
	}
}
