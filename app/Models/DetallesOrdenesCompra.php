<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DetallesOrdenesCompra
 * 
 * @property int $idDetalles_Ordenes_compra
 * @property int $Suministro_idSuministro
 * @property int $Ordenes_compra_idOrden_compra
 * @property int $cantidad_pedida
 * @property float $subtotal
 * @property float $precio_unitario
 * 
 * @property OrdenesCompra $ordenes_compra
 * @property Suministro $suministro
 * @property Collection|DetallesRecepcionesMercancia[] $detalles_recepciones_mercancias
 *
 * @package App\Models
 */
class DetallesOrdenesCompra extends Model
{
	protected $table = 'detalles_ordenes_compra';
	protected $primaryKey = 'idDetalles_Ordenes_compra';
	public $timestamps = false;

	protected $casts = [
		'Suministro_idSuministro' => 'int',
		'Ordenes_compra_idOrden_compra' => 'int',
		'cantidad_pedida' => 'int',
		'subtotal' => 'float',
		'precio_unitario' => 'float'
	];

	protected $fillable = [
		'Suministro_idSuministro',
		'Ordenes_compra_idOrden_compra',
		'cantidad_pedida',
		'subtotal',
		'precio_unitario'
	];

	public function ordenes_compra()
	{
		return $this->belongsTo(OrdenesCompra::class, 'Ordenes_compra_idOrden_compra');
	}

	public function suministro()
	{
		return $this->belongsTo(Suministro::class, 'Suministro_idSuministro');
	}

	public function detalles_recepciones_mercancias()
	{
		return $this->hasMany(DetallesRecepcionesMercancia::class, 'Detalles_Ordenes_compra_idDetalles_Ordenes_compra');
	}
}
