# Elemes.id Test

## Fitur

-   Auth **done**

### Admin

-   CRUD Course **done**
-   Delete User **done**
-   Statistics **done**

### User

-   Register **done**
-   Category (Get, Get Popular) **done**
-   Course (Get, Get Detail, Search, Sort) **done**

## Cara menginstall dan menjalankan

```bash
# Clone Repository
$ git clone https://github.com/5h1ro/elemes.git
# Install Package
$ composer install
# Migrate Database
$ php artisan migrate:fresh --seed
# Menjalankan Aplikasi
$ php artisan serve
```

## Cara deploy di Heroku

-   Masuk ke halaman dashboard
-   Create new app
-   Masukkan nama app
-   Buat database postgresql dan catat credentialnya
-   Pada menu deploy pilih Github pada Deployment Method
-   Connect kan ke repository anda
-   Nyalakan Automatic Deploys
-   Tekan Deploy Branch
-   Pergi ke laman settings dan tambahkan config vars
-   Isi config vars sesuai dengan env
-   Tekan tombol more, dan pilih run console
-   Lalu ketikkan "php artisan migrate:fresh --seed"
-   Aplikasi siap di jalankan
