<?php

/**
 * Oszágok
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

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

        public function scopeSearch(Builder $query, Request $request)
        {
            return $query->when($request->search, function ($query) use ($request) {
                $query->where(function ($query) use ($request) {
                    $query->where('name', 'like', "%{$request->search}%");
                });
            });
        }
        
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

