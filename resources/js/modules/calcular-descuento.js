/**
 * Calculate discount percentage based on total minutes
 */
export function calcularPorcentajeDescuento(minutos) {
    if (minutos < 300) {
        return 0;
    }

    if (minutos >= 2700) {
        return 45; // Máximo descuento
    }

    // 5% por cada tramo de 300 minutos
    const tramos = Math.floor(minutos / 300);
    const porcentaje = tramos * 5;

    return Math.min(porcentaje, 45);
}

/**
 * Calculate total price for credit purchase
 */
export function calcularPrecioCompra(minutos) {
    const PRECIO_BASE = 25; // Bs por minuto

    const subtotal = minutos * PRECIO_BASE;
    const porcentajeDescuento = calcularPorcentajeDescuento(minutos);
    const descuento = subtotal * (porcentajeDescuento / 100);
    const total = subtotal - descuento;

    return {
        minutos,
        precio_por_minuto: PRECIO_BASE,
        subtotal: subtotal.toFixed(2),
        porcentaje_descuento: porcentajeDescuento,
        descuento: descuento.toFixed(2),
        total: total.toFixed(2),
    };
}

/**
 * Format currency in Bolivianos
 */
export function formatearMoneda(monto) {
    return `Bs ${parseFloat(monto).toFixed(2)}`;
}
