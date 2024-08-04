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
 * @property int $Suministro_idSuministro
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
		'Suministro_idSuministro' => 'int'
	];

	protected $fillable = [
		'cantidad_devuelta',
		'motivo',
		'Devoluciones_idDevoluciones',
		'Suministro_idSuministro'
	];

	public function devolucione()
	{
		return $this->belongsTo(Devolucione::class, 'Devoluciones_idDevoluciones');
	}

	public function suministro()
	{
		return $this->belongsTo(Suministro::class, 'Suministro_idSuministro');
	}
}
