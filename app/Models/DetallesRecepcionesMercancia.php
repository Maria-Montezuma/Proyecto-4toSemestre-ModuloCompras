<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DetallesRecepcionesMercancia
 * 
 * @property int $idDetalle_Recepcion_Mercancia
 * @property int $cantidad_recibida
 * @property int $status_recepcion
 * @property int $Detalles_Ordenes_compra_idDetalles_Ordenes_compra
 * @property int $Recepciones_mercancias_idRecepcion_mercancia
 * 
 * @property DetallesOrdenesCompra $detalles_ordenes_compra
 * @property RecepcionesMercancia $recepciones_mercancia
 * @property Collection|DetallesDevolucione[] $detalles_devoluciones
 *
 * @package App\Models
 */
class DetallesRecepcionesMercancia extends Model
{
	protected $table = 'detalles_recepciones_mercancias';
	protected $primaryKey = 'idDetalle_Recepcion_Mercancia';
	public $timestamps = false;

	protected $casts = [
		'cantidad_recibida' => 'int',
		'status_recepcion' => 'int',
		'Detalles_Ordenes_compra_idDetalles_Ordenes_compra' => 'int',
		'Recepciones_mercancias_idRecepcion_mercancia' => 'int'
	];

	protected $fillable = [
		'cantidad_recibida',
		'status_recepcion',
		'Detalles_Ordenes_compra_idDetalles_Ordenes_compra',
		'Recepciones_mercancias_idRecepcion_mercancia'
	];

	public function detalles_ordenes_compra()
	{
		return $this->belongsTo(DetallesOrdenesCompra::class, 'Detalles_Ordenes_compra_idDetalles_Ordenes_compra');
	}

	public function recepciones_mercancia()
	{
		return $this->belongsTo(RecepcionesMercancia::class, 'Recepciones_mercancias_idRecepcion_mercancia');
	}

	public function detalles_devoluciones()
	{
		return $this->hasMany(DetallesDevolucione::class, 'Detalles_Recepciones_Mercancias_idDetalle_Recepcion_Mercancia');
	}
}
