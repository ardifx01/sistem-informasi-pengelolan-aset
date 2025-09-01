
# sistem-informasi-pengelolan-aset
  Aplikasi ini dirancang untuk mempermudah proses pencatatan, pelacakan, dan pengelolaan aset perusahaan .
=======
<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Sistem Informasi Pengelolaan Aset

Aplikasi ini merupakan sistem informasi untuk mengelola aset perusahaan berbasis web, dibangun menggunakan **Laravel** dengan dukungan **Filament Admin Panel** serta **MySQL** sebagai database.

## 🚀 Fitur Utama
- **Kelola Aset (Barang)**  
  CRUD data barang/aset perusahaan.
- **Kelola Cabang**  
  Mengelola data cabang perusahaan.
- **Kelola User**  
  - **GA (General Affair)**: Admin pusat yang dapat mengelola aset lintas cabang.  
  - **PIC (Person In Charge)**: User per cabang untuk mengelola aset di cabangnya.
- **Request Barang**  
  Proses pengajuan barang dari cabang ke pusat.
- **Transfer Barang**  
  Proses perpindahan barang antar cabang.

## 🛠️ Teknologi yang Digunakan
- [Laravel 11](https://laravel.com/)
- [Filament Admin Panel](https://filamentphp.com/)
- [MySQL](https://www.mysql.com/)
- PHP 8+
- Composer

## 📂 Struktur Folder (Utama)
- `app/Filament/Resources` → Resource 
- `database/migrations` → Struktur tabel database
- `routes/web.php` → Routing aplikasi

## ⚙️ Instalasi
Clone repository ini:
   ```bash
   git clone https://github.com/nmrhan/sistem-informasi-pengelolaan-aset.git
   cd sistem-informasi-pengelolaan-aset


## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
>>>>>>> cfdb00f (first commit)
