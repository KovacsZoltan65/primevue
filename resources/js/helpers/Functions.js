export const Functions = {
    createId() {
        let id = '';

        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

        for (let i = 0; i < 5; i++) {
            const randomIndex = Math.floor(Math.random() * chars.length);
            id += chars.charAt(randomIndex);
        }

        return id;

        //return Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
    },

    /**
     * Egy számot valutakarakterláncként formáz.
     *
     * @param {number | undefined} value - A formázandó szám.
     * @return {string | undefined} A formázott pénznem-karakterlánc, vagy hamis az érték definiálatlan.
     */
    formatCurrency(value) {
        // Ha az érték false (undefined, null, 0 stb.), adja vissza az undefined értéket.
        if (!value) return;
    
        // Formázza a számot valutakarakterláncként az amerikai angol nyelvterület és az USD pénznem használatával.
        return value.toLocaleString('en-US', { style: 'currency', currency: 'USD' });
    }

}