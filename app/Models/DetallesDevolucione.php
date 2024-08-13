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
 * @property int $Suministros_idSuministro
 * 
 * @property Devolucione $devolucione
 * @property Suministro $suministro
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
		'Devoluciones_idDevoluciones' => 'int',
		'Suministros_idSuministro' => 'int'
	];

	protected $fillable = [
		'cantidad_devuelta',
		'motivo',
		'Devoluciones_idDevoluciones',
		'status_devolucion',
		'Suministros_idSuministro'
	];

	public function devolucione()
	{
		return $this->belongsTo(Devolucione::class, 'Devoluciones_idDevoluciones');
	}

	public function suministro()
	{
		return $this->belongsTo(Suministro::class, 'Suministros_idSuministro');
	}
}
