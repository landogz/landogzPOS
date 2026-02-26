/**
 * Inventory module — stock levels by branch/product.
 * Uses GET /api/v1/inventory/stock-levels and GET /api/v1/inventory/products.
 * The inventory page UI is implemented in resources/views/super-admin/pages/inventory.blade.php (inline script).
 * This module can be used for shared helpers or when the page is loaded via a separate bundle.
 */
export function getStockLevels(apiBase, authHeaders) {
    return fetch(apiBase + '/inventory/stock-levels', {
        headers: authHeaders().headers,
    }).then((r) => r.json());
}

export function isLowStock(row) {
    const reorder = row.reorder_level != null ? parseFloat(row.reorder_level) : 0;
    const qty = parseFloat(row.total_quantity) || 0;
    return reorder > 0 && qty <= reorder;
}
