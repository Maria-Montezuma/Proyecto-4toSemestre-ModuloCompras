<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ProveedoresHasSuministro
 * 
 * @property int $idProveedores_has_Suministro
 * @property int $Proveedores_idProveedores
 * @property int $Suministro_idSuministro
 * 
 * @property Proveedore $proveedore
 * @property Suministro $suministro
 *
 * @package App\Models
 */
class ProveedoresHasSuministro extends Model
{
	protected $table = 'proveedores_has_suministro';
	protected $primaryKey = 'idProveedores_has_Suministro';
	public $timestamps = false;

	protected $casts = [
		'Proveedores_idProveedores' => 'int',
		'Suministro_idSuministro' => 'int'
	];

	protected $fillable = [
		'Proveedores_idProveedores',
		'Suministro_idSuministro'
	];

	public function proveedore()
	{
		return $this->belongsTo(Proveedore::class, 'Proveedores_idProveedores');
	}

	public function suministro()
	{
		return $this->belongsTo(Suministro::class, 'Suministro_idSuministro');
	}
}
