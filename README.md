# 📦 The Quantum Stockroom: Warehouse Management System

Proyek Final Praktikum Pemrograman Web 2025

Sistem manajemen gudang futuristik (Warehouse Management System) yang dirancang untuk mengelola **inventaris**, **transaksi masuk/keluar**, dan **alur restock** secara digital dan berbasis peran (**Role-based**).

---

## 🎨 Konsep Desain: "The Quantum Stockroom"

Desain dan User Experience (UX) merupakan poin jual utama proyek ini. Kami menyajikan sistem logistik sebagai **pusat komando teknologi tinggi**, dengan fokus pada akurasi data dan visual yang bersih untuk meningkatkan pengalaman pengguna.

### 1. Palet dan Visual Kritis

| Elemen | Detail Implementasi | Tujuannya |
| :--- | :--- | :--- |
| **Tema Dasar** | **Dark Mode** (Dark Charcoal) | Profesional dan mengurangi ketegangan mata (eye strain). |
| **Aksen Kritis** | **Electric Cyan** & **Neon Green** | Melambangkan kecepatan digital dan notifikasi kritis. |
| **Quantum Card** | Efek **Glassmorphism** (transparansi) dengan Border dan Box Shadow Neon. | Menciptakan ilusi Panel Data Holografik yang melayang. |
| **Animated UI** | Penggunaan **CSS Typing Effect** (kursor berkedip) dan **AOS** pada elemen penting. | Meningkatkan *user experience* dan memberikan kesan Pusat Komando. |

---

## 🏗️ Struktur Aplikasi (4 Peran)

Sistem ini diproteksi ketat menggunakan **Role-based Middleware dan Policy** untuk memastikan setiap pengguna hanya memiliki akses sesuai dengan kebutuhan tugasnya.

| Peran | Tugas Kunci | Hak Akses Utama |
| :--- | :--- | :--- |
| **Admin** | Pengaturan sistem, monitoring penuh, CRUD penuh pada semua modul. | CRUD Penuh |
| **Manager** | Approve/Reject Transaksi, membuat Purchase Order (PO), kontrol data master. | CRUD Produk/Kategori, R/U Transaksi, R/U Restock |
| **Staff Gudang** | Mencatat Transaksi Barang Masuk/Keluar. | R/U Produk (View Only), C/R Transaksi |
| **Supplier** | Menerima PO, Confirm Ketersediaan, melihat Rating dari Manager. | R/U Restock (View PO, Update Status) |

---

## ⚙️ Fitur dan Logika Kritis

### A. Integritas Data dan Audit

* **Logic RESTRICT (Kategori):** Impelementasi **validation di Controller** yang secara ketat melarang penghapusan Kategori jika masih ada Produk yang terikat dengannya. *(Argumen Profesional: Mencegah hilangnya data inventaris secara tidak sengaja dan menjaga hubungan data).*
* **Approval Transaksi:** Menggunakan **alur 2-langkah** (Staff → Manager). Stok hanya akan diubah di database **setelah Manager Approve** pada transaksi yang diajukan oleh Staff.

### B. Modul Utama

* **Product Management:** CRUD Produk, penggunaan **SKU unik**, dan pelacakan **Low Stock Alert** (otomatis memicu notifikasi jika `current_stock <= min_stock`).
* **Transaction Management:** Pencatatan detail Barang Masuk dan Barang Keluar.
* **Restock Management:** Alur **Purchase Order (PO)** dengan pelacakan status yang jelas (**Pending → Sent → Received**).
* **Fitur Opsional (Rating):** Manager dapat memberikan **Rating (1-5)** dan Umpan Balik pada Supplier setelah PO berstatus *Received*. Rating ini dapat dilihat oleh Supplier.

---

## 💻 Teknologi dan Instalasi

| Kategori | Teknologi | Detail |
| :--- | :--- | :--- |
| **Framework** | **Laravel v11.x** | Backend Development (PHP) |
| **Database** | **MySQL** | Penyimpanan data relasional |
| **Frontend** | **Blade Template Engine, Tailwind CSS** | Desain responsif dan modern |
| **Middleware** | **Custom Role Middleware & Policy** | Otorisasi berbasis peran yang ketat |

### Panduan Instalasi Cepat

Ikuti langkah-langkah di bawah ini untuk menjalankan proyek secara lokal.

1.  **Clone Repositori:**
    ```bash
    git clone [Link Repositori Anda] quantum-stockroom
    cd quantum-stockroom
    ```

2.  **Setup Database:**
    * Buat database baru melalui *database manager* Anda.
    * Konfigurasi file `.env` (isi `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).

3.  **Instalasi Dependencies:**
    ```bash
    composer install
    npm install
    ```

4.  **Migrasi, Seeder, dan Link:**
    ```bash
    php artisan migrate --seed # Gunakan --seed untuk mengisi data awal
    php artisan storage:link
    ```

5.  **Jalankan Aplikasi:**
    ```bash
    npm run dev        # Untuk kompilasi frontend Tailwind CSS (atau npm run watch)
    php artisan serve  # Untuk menjalankan server backend Laravel
    ```

---

## 🔑 Akun Uji Coba Kritis

Anda dapat menggunakan akun-akun berikut untuk menguji sistem sesuai dengan peran masing-masing:

| Role | Email | Password |
| :--- | :--- | :--- |
| **Admin** | `admin@gudang.com` | `password` |
| **Manager** | `manager@gudang.com` | `password` |
| **Staff Gudang** | `staff@gudang.com` | `password` |
| **Supplier** | `supplier@gudang.com` | `password` |