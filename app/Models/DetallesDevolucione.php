<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class DetallesDevolucione
 * 
 * @property int $iddetalles_devoluciones
 * @property int $cantidad_devuelta
 * @property string $motivo
 * @property int $Devoluciones_idDevoluciones
 * @property string $status_devolucion
 * 
 * @property Devolucione $devolucione
 *
 * @package App\Models
 */
class DetallesDevolucione extends Model
{
	protected $table = 'detalles_devoluciones';
	protected $primaryKey = 'iddetalles_devoluciones';
	public $timestamps = false;

	protected $casts = [
		'cantidad_devuelta' => 'int',
		'Devoluciones_idDevoluciones' => 'int'
	];

	protected $fillable = [
		'cantidad_devuelta',
		'motivo',
		'Devoluciones_idDevoluciones',
		'status_devolucion'
	];

	public function devolucione()
	{
		return $this->belongsTo(Devolucione::class, 'Devoluciones_idDevoluciones');
	}
}
