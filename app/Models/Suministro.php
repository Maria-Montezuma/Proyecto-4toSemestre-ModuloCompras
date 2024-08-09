<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Suministro
 * 
 * @property int $idSuministro
 * @property string $nombre_suministro
 * @property float $precio_unitario
 * @property int $categorias_idcategorias
 * 
 * @property Categoria $categoria
 * @property Collection|DetallesOrdenesCompra[] $detalles_ordenes_compras
 * @property Collection|Proveedore[] $proveedores
 *
 * @package App\Models
 */
class Suministro extends Model
{
	protected $table = 'suministros';
	protected $primaryKey = 'idSuministro';
	public $timestamps = false;

	protected $casts = [
		'precio_unitario' => 'float',
		'categorias_idcategorias' => 'int'
	];

	protected $fillable = [
		'nombre_suministro',
		'precio_unitario',
		'categorias_idcategorias'
	];

	public function categoria()
	{
		return $this->belongsTo(Categoria::class, 'categorias_idcategorias');
	}

	public function detalles_ordenes_compras()
	{
		return $this->hasMany(DetallesOrdenesCompra::class, 'Suministro_idSuministro');
	}

	public function proveedores()
	{
		return $this->belongsToMany(Proveedore::class, 'proveedores_has_suministro', 'Suministro_idSuministro', 'Proveedores_idProveedores')
					->withPivot('idProveedores_has_Suministro');
	}
}
