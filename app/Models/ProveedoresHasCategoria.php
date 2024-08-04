<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ProveedoresHasCategoria
 * 
 * @property int $idProveedores_has_categoriascol
 * @property int $Proveedores_idProveedores
 * @property int $categorias_idcategorias
 * 
 * @property Proveedore $proveedore
 * @property Categoria $categoria
 *
 * @package App\Models
 */
class ProveedoresHasCategoria extends Model
{
	protected $table = 'proveedores_has_categorias';
	protected $primaryKey = 'idProveedores_has_categoriascol';
	public $timestamps = false;

	protected $casts = [
		'Proveedores_idProveedores' => 'int',
		'categorias_idcategorias' => 'int'
	];

	protected $fillable = [
		'Proveedores_idProveedores',
		'categorias_idcategorias'
	];

	public function proveedore()
	{
		return $this->belongsTo(Proveedore::class, 'Proveedores_idProveedores');
	}

	public function categoria()
	{
		return $this->belongsTo(Categoria::class, 'categorias_idcategorias');
	}
}
