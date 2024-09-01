<script setup>
import { ref, watch, computed } from 'vue';
import { useLayout } from '../Layouts/composables/layout';

import AppTopBar from './AppTopBar.vue';
import AppSidebar from './AppSidebar.vue';
import AppFooter from './AppFooter.vue';


/**
 * Törölje a változókat a useLayout hook segítségével.
 *
 * @see useLayout - A horog, amely biztosítja az elrendezés konfigurációját, az elrendezés állapotát,
 * oldalsáv aktív állapot és reset menü funkció.
 */
const {
    // Az elrendezés konfigurációs objektuma.
    layoutConfig,

    // Az elrendezés állapot objektuma.
    layoutState,

    // Logikai érték, amely jelzi, hogy az oldalsáv aktív-e vagy sem.
    isSidebarActive,

    // A menü visszaállítására szolgáló funkció.
    resetMenu,
} = useLayout();


/**
 * Reaktív hivatkozás a kattintásesemény figyelő tartására külső kattintások esetén.
 *
 * @type {ref<Function|null>}
 */
const outsideClickListener = ref(null);


/**
 * Figyelő a `isSidebarActive` reaktív változóra, hogy elindítsa vagy leállítsa a külső kattintásokat feldolgozó függvényt.
 *
 * @see isSidebarActive - A reaktív változó, amely jelzi, hogy az oldalsáv aktív-e vagy sem.
 * @see bindOutsideClickListener - A függvény, amely kezeli a külső kattintásokat, amikor az oldalsáv aktív.
 * @see unbindOutsideClickListener - A függvény, amely leállítja a külső kattintásokat, amikor az oldalsáv inaktív.
 */
watch(isSidebarActive, (newVal) => {
    // Ha az oldalsáv aktív, akkor indítja el a külső kattintások feldolgozását.
    if (newVal) {
        bindOutsideClickListener();
    } else {
        // Ha az oldalsáv inaktív, akkor leállítja a külső kattintások feldolgozását.
        unbindOutsideClickListener();
    }
});


/**
 * Reaktív hivatkozás, amely tartalmazza a tároló osztályait az elrendezéshez.
 *
 * @type {ref<Object>}
 */
const containerClass = computed(() => {
    /**
     * Tároló osztályok objektum. Az osztályok alapján a következők lesznek hozzáadva vagy eltávolítva a tárolóhoz:
     * - 'layout-overlay': A menü mód 'overlay'.
     * - 'layout-static': A menü mód 'static'.
     * - 'layout-static-inactive': A menü mód 'static' és a statikus menü aktív állapota inaktív.
     * - 'layout-overlay-active': A menü mód 'overlay' és az átfedő menü aktív állapota aktív.
     * - 'layout-mobile-active': A menü mód 'static' és a mobil menü statikus aktív állapota aktív.
     */
    return {
        'layout-overlay': layoutConfig.menuMode === 'overlay', // Menü mód 'overlay'
        'layout-static': layoutConfig.menuMode === 'static', // Menü mód 'static'
        'layout-static-inactive': layoutState.staticMenuDesktopInactive && layoutConfig.menuMode === 'static', // Menü mód 'static' és a statikus menü aktív állapota inaktív
        'layout-overlay-active': layoutState.overlayMenuActive, // Menü mód 'overlay' és az átfedő menü aktív állapota aktív
        'layout-mobile-active': layoutState.staticMenuMobileActive // Menü mód 'static' és a mobil menü statikus aktív állapota aktív
    };
});

/**
 * Ez a függvény eseményfigyelőt köt a dokumentumobjektumhoz.
 * Figyeli az adott elemen kívüli kattintásokat, és visszahívási funkciót hajt végre, ha kattintás történik.
 * A visszahívási függvény ellenőrzi, hogy a kattintási cél kívül esik-e egy adott elemen, és ha igen, akkor meghívja a resetMenu() függvényt.
 */
function bindOutsideClickListener() {
    // Ellenőrizze, hogy az outsideClickListener eseményfigyelő 
    // nincs-e még kötve a dokumentumobjektumhoz
    if (!outsideClickListener.value) {

        // Határozza meg a visszahívási funkciót a kattintási eseményhez
        outsideClickListener.value = (event) => {
            /**
             * Ellenőrizze, hogy a kattintási cél kívül van-e az oldalsávon 
             * vagy a felső sáv elemein.
             * Ha igen, hívja meg a resetMenu() függvényt.
             */
            if (isOutsideClicked(event)) {
                resetMenu();
            }
        };

        // Adja hozzá az eseményfigyelőt a dokumentumobjektumhoz
        document.addEventListener('click', outsideClickListener.value);
    }
}

/**
 * Ez a funkció eltávolítja a külső kattintások eseményfigyelőjét.
 * Ellenőrzi, hogy az outsideClickListener már be van-e állítva, és ha igen,
 * eltávolítja az eseményfigyelőt és nullára állítja.
 */
function unbindOutsideClickListener() {
    // Ellenőrizze, hogy az outsideClickListener már be van-e állítva
    if (outsideClickListener.value) {
        // Távolítsa el az eseményfigyelőt a külső kattintásokhoz
        document.removeEventListener('click', outsideClickListener);

        // Állítsa az outsideClickListenert nullára
        outsideClickListener.value = null;
    }
}

/**
 * Ez a funkció ellenőrzi, hogy a kattintott cél az oldalsávon vagy a felső sávon kívül van-e.
 * 
 * @param {Event} event - A kattintási esemény.
 * @return {boolean} Igaz értéket ad vissza, ha a kattintott cél az oldalsávon vagy a felső sávon kívül van, ellenkező esetben hamis.
 */
function isOutsideClicked(event) {
    // Szerezze be az oldalsáv és a felső sáv elemeit a DOM-ból
    const sidebarEl = document.querySelector('.layout-sidebar');
    const topbarEl = document.querySelector('.layout-menu-button');

    // Ellenőrizze, hogy a kattintott cél ugyanaz-e, mint az oldalsáv vagy annak gyermeke, vagy ugyanaz, mint a felső sáv vagy annak gyermeke
    // Ha igen, adja vissza false értékét, jelezve, hogy nincs az oldalsávon vagy a felső sávon kívül
    // Ha nem, adja vissza a true értéket, jelezve, hogy az oldalsávon vagy a felső sávon kívül van
    return !(
        sidebarEl.isSameNode(event.target) ||
        sidebarEl.contains(event.target) ||
        topbarEl.isSameNode(event.target) ||
        topbarEl.contains(event.target)
    );
}
</script>

<template>
    <div class="layout-wrapper" :class="containerClass">
        <AppTopBar></AppTopBar>

        <AppSidebar></AppSidebar>

        <div class="layout-main-container">
            <div class="layout-main">
                <slot />
            </div>

            <AppFooter></AppFooter>

        </div>

        <div class="layout-mask animate-fadein"></div>
        
    </div>
</template>