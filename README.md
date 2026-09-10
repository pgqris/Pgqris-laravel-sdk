# PGQRIS Laravel Integration Package

## ⚙️ Langkah Instalasi
1. Salin berkas `config/pgqris.php` ke folder `config/` proyek Laravel Anda.
2. Salin berkas `app/Services/PGQRISService.php` ke folder `app/Services/`.
3. Tambahkan ke `.env`:
```env
PGQRIS_STORE_KEY=masukkan_store_key_anda
PGQRIS_BASE_URL=https://rest.pgqris.com
```
4. Daftarkan route webhook di `routes/api.php`:
```php
use App\Http\Controllers\PGQRISController;

Route::post('/pgqris/payment', [PGQRISController::class, 'createPayment']);
Route::post('/pgqris/webhook', [PGQRISController::class, 'handleWebhook']);
```
