# Simple Order App
Aplikasi pemesanan ditempat, dengan sistem pembayaran cashless.

### Setting Xendit Payment
- Login Xendit.
- Pilih pengaturan.
- Pilih menu **[Api Keys](https://dashboard.xendit.co/settings/developers#api-keys)**.
- Klik "Buat secret key baru".
- Beri izin "Write" pada Produk menerima pembayaran.
- Klik "Buat key".
- Pilih menu **[Callbacks](https://dashboard.xendit.co/settings/developers#callbacks)**.
- Isi form "Invoices terbayarkan" dengan {url-anda}/api/payment, contoh: https://example.com/api/payment.
- Klik "Test dan simpan".

## Teknologi yang digunakan
- Framework Laravel v9
- AdminLTE
- Xendit Payment Gateway
- MySql Database
- QRCode Generator