<?php
function formatMoney(float $amount): string {
    return '$' . number_format($amount, 2);
}

function invoiceNumber(int $orderId): string {
    return 'INV-' . str_pad((string) $orderId, 6, '0', STR_PAD_LEFT);
}
