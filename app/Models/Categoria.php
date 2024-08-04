<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Categoria
 * 
 * @property int $idcategorias
 * @property string $nombre_categoria
 * 
 * @property Collection|Proveedore[] $proveedores
 * @property Collection|Suministro[] $suministros
 *
 * @package App\Models
 */
class Categoria extends Model
{
	protected $table = 'categorias';
	protected $primaryKey = 'idcategorias';
	public $timestamps = false;

	protected $fillable = [
		'nombre_categoria'
	];

	public function proveedores()
	{
		return $this->belongsToMany(Proveedore::class, 'proveedores_has_categorias', 'categorias_idcategorias', 'Proveedores_idProveedores')
					->withPivot('idProveedores_has_categoriascol');
	}

	public function suministros()
	{
		return $this->hasMany(Suministro::class, 'categorias_idcategorias');
	}
}
