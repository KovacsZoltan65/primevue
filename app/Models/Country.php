<?php

/**
 * Oszágok
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Country
 * 
 * @property int $id
 * @property string $name
 * @property string $code
 *
 * @package App\Models
 */
class Country extends Model
{
	use HasFactory;

	protected $table = 'countries';
	public $timestamps = false;

	protected $fillable = ['name', 'code'];

	/**
	 * A kapcsolódó városok listája.
	 *
	 * Egy országnak több városa is lehet.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function cities()
	{
		return $this->hasMany(City::class, 'country_id');
	}

	/**
	 * A kapcsolódó régiók listája.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function regions()
	{
		return $this->hasMany(Region::class, 'country_id');
	}
}

