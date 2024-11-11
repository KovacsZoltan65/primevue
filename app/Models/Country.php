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

	protected $fillable = ['name', 'code', 'active'];

    protected $casts = [
        'active' => 'integer',
    ];

    /**
     * Határozza meg a lekérdezést, hogy csak olyan országokat tartalmazzon,
     * amelyek neve megegyezik a keresési kifejezéssel.
     *
     * @param Builder $query A lekérdezéskészítő példány.
     * @param Request $request Az aktuális HTTP-kérelem objektum,
     *        amely keresési paramétereket tartalmaz.
     * @return Builder A módosított lekérdezéskészítő példány.
     */
    public function scopeSearch(Builder $query, Request $request)
    {
        return $query->when($request->search, function ($query) use ($request) {
            $query->where(function ($query) use ($request) {
                $query->where('name', 'like', "%{$request->search}%");
            });
        })->active();
    }

    /**
     * Határozza meg a lekérdezést, hogy csak az aktív városokat tartalmazza.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', APP_ACTIVE);
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
        return $this->hasMany(Region::class, 'region_id');
	}
}

