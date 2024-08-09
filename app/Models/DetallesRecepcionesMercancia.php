<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class DetallesRecepcionesMercancia
 * 
 * @property int $idDetalle_Recepcion_Mercancia
 * @property int $cantidad_recibida
 * @property int $status_recepcion
 * @property int $Recepciones_mercancias_idRecepcion_mercancia
 * 
 * @property RecepcionesMercancia $recepciones_mercancia
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
		'Recepciones_mercancias_idRecepcion_mercancia' => 'int'
	];

	protected $fillable = [
		'cantidad_recibida',
		'status_recepcion',
		'Recepciones_mercancias_idRecepcion_mercancia'
	];

	public function recepciones_mercancia()
	{
		return $this->belongsTo(RecepcionesMercancia::class, 'Recepciones_mercancias_idRecepcion_mercancia');
	}
}
