<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;

class MenuItem extends Model
{
    use HasFactory,
        LogsActivity;

    protected $table = 'menu_items';
    protected $fillable = ['title', 'icon', 'url', 'default_weight', 'parent_id'];

    /*
     * ==============================================================
     * LOGOLÁS
     * ==============================================================
     */

    // Ha szeretnéd, hogy minden mezőt automatikusan naplózzon:
    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true; // Csak a változásokat naplózza
    protected static $logName = 'menu_item';

    protected static $recordEvents = [
        'created',
        'updated',
        'deleted',
    ];

    /*
     * ==============================================================
     */

    /**
     * Az aktuális menüpont gyermekmenüpontjainak lekérése.
     *
     * Ezzel visszaadja a menüelemek gyűjteményét, amelyek az aktuális menüpont alárendelt elemei.
     * A gyűjteményben szerepelni fognak a gyerekek gyermekei is, stb.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children(): HasMany
    {
        //return $this->hasMany(MenuItem::class, 'parent_id');
        return $this->hasMany(MenuItem::class, 'parent_id')->with('children');
    }

    /**
     * Lekéri az aktuális menüpont szülőmenüjét.
     *
     * Ezzel létrejön egy kapcsolat, ahová az aktuális menüpont tartozik
     * egy szülő menüelem, amelyet a „parent_id” azonosít.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    /**
     * A menüpont használati példányainak lekérése.
     *
     * Ezzel visszaadja a menüpont használati példányainak gyűjteményét.
     * Minden elem a gyűjteményben egy-egy menüpont használati példánya lesz.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function usages(): HasMany
    {
        return $this->hasMany(MenuItemUsage::class);
    }
}
