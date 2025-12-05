# 🛒 Laravel Mart

**Laravel Mart** adalah aplikasi E-Commerce sederhana namun robust yang dibangun menggunakan **Laravel 11** dan **Bootstrap 5**. Project ini mensimulasikan alur transaksi belanja online mulai dari pemilihan produk, pengelolaan keranjang, hingga proses checkout dengan validasi alamat.

## ✨ Fitur Utama

* **Authentication:** Login & Register pengguna (Customer & Admin).
* **Product Catalog:** Menampilkan produk dengan filter pencarian (Search).
* **Shopping Cart:**
    * Menambah barang ke keranjang.
    * Update quantity otomatis jika barang sudah ada.
    * Hapus barang dari keranjang.
    * Badge notifikasi jumlah barang di navbar.
* **Checkout System:**
    * Form input alamat pengiriman.
    * Ringkasan pesanan sebelum bayar.
    * **Database Transaction** (Rollback otomatis jika gagal).
* **Invoice & Order History:** Halaman bukti transaksi sukses.
* **User Profile:** Edit nama, email, dan password.

## 🛠️ Tech Stack

* **Backend:** Laravel 11 (PHP 8.2+)
* **Frontend:** Blade Templates, Bootstrap 5.3, Custom CSS
* **Database:** MySQL
* **Icons:** Bootstrap Icons
* **Styling:** CSS Variables, Responsive Layout

## 🚀 Cara Install (Localhost)

Ikuti langkah ini untuk menjalankan project di komputer kamu:

1.  **Clone Repository**
    ```bash
    git clone [https://github.com/username-kamu/laravel-mart.git](https://github.com/username-kamu/laravel-mart.git)
    cd laravel-mart
    ```

2.  **Install Dependencies**
    ```bash
    composer install
    npm install && npm run build
    ```

3.  **Setup Environment**
    * Copy file `.env.example` menjadi `.env`.
    * Buka file `.env` dan atur database:
    ```ini
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=laramart
    DB_USERNAME=root
    DB_PASSWORD=
    ```

4.  **Generate Key & Migrate**
    ```bash
    php artisan key:generate
    php artisan migrate --seed
    ```
    *(Command `--seed` akan otomatis mengisi data User Admin, Customer, dan Produk Dummy).*

5.  **Jalankan Server**
    ```bash
    php artisan serve
    ```

6.  **Akses Website**
    Buka browser dan kunjungi: `http://127.0.0.1:8000`

## 👤 Akun Demo (Seeder)

Gunakan akun ini untuk login testing:

admin: 
* **Email:** `admin@toko.com`
* **Password:** `password`

user: 
* **Email:** `user@toko.com`
* **Password:** `password`

## 📂 Struktur Database

* `users`: Data pengguna & role.
* `products`: Data barang (Nama, Harga, Stok, Gambar).
* `carts`: Keranjang belanja sementara.
* `orders`: Data header transaksi (Total harga, Alamat, Status).
* `order_items`: Detail barang per transaksi (Snapshot harga saat beli).

---
Dibuat dengan ❤️ oleh **Sierly Putri Anjani**