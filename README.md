# Rental Pees

Aplikasi Rental Pe'Es dengan Laravel Livewire.

## 📥 Instalasi

Ikuti langkah-langkah berikut untuk menginstall proyek ini di lingkungan lokal Anda.

### 1. Clone Repository
```sh
git clone https://github.com/RidwanPadillah/rental-pees.git
cd rental-pees
```

### 2. Instal Dependensi
```sh
composer install
npm install
```

### 3. Konfigurasi Lingkungan
Buat file `.env` dari contoh yang tersedia:
```sh
cp .env.example .env
```
Lalu, sesuaikan konfigurasi database di file `.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database_anda
DB_USERNAME=user_database
DB_PASSWORD=password_database
```

### 4. Generate Application Key
```sh
php artisan key:generate
```

### 5. Migrasi dan Seeder
```sh
php artisan migrate --seed
```

### 6. Jalankan Server
```sh
php artisan serve
```

## 🔑 Akun Default
Setelah menjalankan seeder, Anda dapat login dengan akun berikut:
- **Username:** admin@rental.com
- **Password:** password

## 🚀 Jalankan Frontend
Jika proyek menggunakan Vite untuk frontend:
```sh
npm run dev
```

## 🎯 Livewire
Proyek ini menggunakan **Livewire** untuk interaksi dinamis di Laravel.

## 🔗 Dokumentasi
- Laravel: [https://laravel.com/docs](https://laravel.com/docs)
- Livewire: [https://livewire.laravel.com/docs](https://livewire.laravel.com/docs)
