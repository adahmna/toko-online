<?php

return [
    // 1 USD = berapa Rupiah. Ubah lewat .env: KURS_USD_IDR=16000
    'usd_idr' => (float) env('KURS_USD_IDR', 16000),
];