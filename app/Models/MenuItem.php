<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MenuItem extends Model
{
    use HasFactory;

    protected $table = 'menu_items';
    protected $fillable = ['label', 'icon', 'url', 'default_weight', 'parent_id'];

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

    /**
     * Lekéri a menüpontot címke alapján.
     *
     * Ezzel visszaadja a menüpontot, amelynek a címke (label) megegyezik a megadotttal.
     * Ha nincs ilyen menüpont, akkor null értéket ad vissza.
     *
     * @param string $label A keresett menüpont címke.
     * @return static|null A megtalált menüpont, vagy null, ha nincs ilyen.
     */
    public static function findByLabel(string $label): ?self {
        return self::where('label', '=', $label)->first();
    }

    /**
     * Lekéri a menüpontok default_weight oszlopának maximumát.
     *
     * Ezzel visszaadja a menüpontok default_weight oszlopának maximumát.
     * A visszatérési érték egész szám lesz.
     *
     * @return int A maximum default_weight érték.
     */
    public static function getMaxDefaultWeight(): int
    {
        return (int) self::max('default_weight') ?? 1;
    }

}
