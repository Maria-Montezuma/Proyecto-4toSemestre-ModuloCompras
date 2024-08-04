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
 * @property string $status
 * @property int $Empleados_idEmpleados
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
		'Empleados_idEmpleados' => 'int',
		'Recepciones_mercancias_idRecepcion_mercancia' => 'int'
	];

	protected $fillable = [
		'fecha_devolucion',
		'status',
		'Empleados_idEmpleados',
		'Recepciones_mercancias_idRecepcion_mercancia'
	];

	public function empleado()
	{
		return $this->belongsTo(Empleado::class, 'Empleados_idEmpleados');
	}

	public function recepciones_mercancia()
	{
		return $this->belongsTo(RecepcionesMercancia::class, 'Recepciones_mercancias_idRecepcion_mercancia');
	}

	public function detalles_devoluciones()
	{
		return $this->hasMany(DetallesDevolucione::class, 'Devoluciones_idDevoluciones');
	}
}
