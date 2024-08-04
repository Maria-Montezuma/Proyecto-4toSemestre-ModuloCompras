<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SolicitudesHasProveedore
 * 
 * @property int $idSolicitudes_has_Proveedores
 * @property int $Solicitudes_idSolicitudes
 * @property int $Proveedores_idProveedores
 * 
 * @property Proveedore $proveedore
 * @property Solicitude $solicitude
 *
 * @package App\Models
 */
class SolicitudesHasProveedore extends Model
{
	protected $table = 'solicitudes_has_proveedores';
	protected $primaryKey = 'idSolicitudes_has_Proveedores';
	public $timestamps = false;

	protected $casts = [
		'Solicitudes_idSolicitudes' => 'int',
		'Proveedores_idProveedores' => 'int'
	];

	protected $fillable = [
		'Solicitudes_idSolicitudes',
		'Proveedores_idProveedores'
	];

	public function proveedore()
	{
		return $this->belongsTo(Proveedore::class, 'Proveedores_idProveedores');
	}

	public function solicitude()
	{
		return $this->belongsTo(Solicitude::class, 'Solicitudes_idSolicitudes');
	}
}
