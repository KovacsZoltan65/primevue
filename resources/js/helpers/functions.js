/**
 * Egy számot valutakarakterláncként formáz.
 *
 * @param {number | undefined} value - A formázandó szám.
 * @return {string | undefined} A formázott pénznem-karakterlánc, vagy hamis az érték definiálatlan.
 */
export function formatCurrency(value) {
    if (!value) return;

    return value.toLocaleString("en-US", {
        style: "currency",
        currency: "USD",
    });
}

export function formatDate(date) {
    const options = { year: "numeric", month: "long", day: "numeric" };
    return new Date(date).toLocaleString(undefined, options);
}
