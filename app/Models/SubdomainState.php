<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Override;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 *
 * @property int $id
 * @property string $name
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 */

class SubdomainState extends Model
{
    use HasFactory,
        SoftDeletes,
        LogsActivity;

    protected $table = 'subdomain_states';

    protected $fillable = ['name', 'active'];

    //protected $casts = [
    //    'active' => 'integer',
    //];
    
    // Ha szeretnéd, hogy minden mezőt automatikusan naplózzon:
    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true; // Csak a változásokat naplózza
    protected static $logName = 'subdomain_state';
    
    protected static $recordEvents = [
        'created',
        'updated',
        'deleted',
    ];
    
    /**
     * Határozza meg a lekérdezést, hogy a keresési kifejezésnek megfelelő névvel rendelkező aldomain állapotokat is tartalmazzon.
     *
     * @param Builder $query A lekérdezéskészítő példány.
     * @param Request $request Az aktuális HTTP-kérelem objektum, amely keresési paramétereket tartalmaz.
     * @return Builder A módosított lekérdezéskészítő példány.
     */
    public function scopeSearch(Builder $query, Request $request): Builder
    {
        return $query->when($request->search, function ($query) use ($request) {
            $query->where(function ($query) use ($request) {
                $query->where('name', 'like', "%{$request->search}%");
            });
        });
    }
    
    /**
     * Szerezze be az ehhez az aldomain állapothoz társított aldomaineket.
     *
     * @return HasMany
     */
    public function subdomains(): HasMany
    {
        return $this->hasMany(Subdomain::class, 'state_id');
    }
    
    #[Override]
    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
            ->logFillable()
            ->logAll();
    }
}
