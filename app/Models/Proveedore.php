<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Proveedore
 * 
 * @property int $idProveedores
 * @property string $nombre_empresa
 * @property string $telefono_proveedor
 * @property string $direccion_empresa
 * @property string $correo_proveedor
 * @property string $rif
 * 
 * @property Collection|OrdenesCompra[] $ordenes_compras
 * @property Collection|Categoria[] $categorias
 * @property Collection|Suministro[] $suministros
 * @property Collection|Solicitude[] $solicitudes
 *
 * @package App\Models
 */
class Proveedore extends Model
{
	protected $table = 'proveedores';
	protected $primaryKey = 'idProveedores';
	public $timestamps = false;

	protected $fillable = [
		'nombre_empresa',
		'telefono_proveedor',
		'direccion_empresa',
		'correo_proveedor',
		'rif'
	];

	public function ordenes_compras()
	{
		return $this->hasMany(OrdenesCompra::class, 'Proveedores_idProveedores');
	}

	public function categorias()
	{
		return $this->belongsToMany(Categoria::class, 'proveedores_has_categorias', 'Proveedores_idProveedores', 'categorias_idcategorias')
					->withPivot('idProveedores_has_categoriascol');
	}

	public function suministros()
	{
		return $this->belongsToMany(Suministro::class, 'proveedores_has_suministro', 'Proveedores_idProveedores', 'Suministro_idSuministro')
					->withPivot('idProveedores_has_Suministro');
	}

	public function solicitudes()
	{
		return $this->belongsToMany(Solicitude::class, 'solicitudes_has_proveedores', 'Proveedores_idProveedores', 'Solicitudes_idSolicitudes')
					->withPivot('idSolicitudes_has_Proveedores');
	}
}
