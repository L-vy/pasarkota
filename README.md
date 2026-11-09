# PasarKota

<p align="center">
  <img src="public/images/logo.png" alt="Logo PasarKota" width="200">
</p>

>_(formerly Perfect Farm)_ PasarKota adalah Marketplace untuk buah dan sayur lokal berdiskon yang bentuknya kurang sempurna.

Proyek ini berangkat dari keinginan untuk membangun solusi yang selaras dengan SDG 12 dan berdampak langsung pada masyarakat lokal, khususnya dalam mengurangi food waste. Selain itu, proyek ini menjadi sarana latihan membangun aplikasi web multi-tier dan mengemasnya dengan Docker, sebagai bekal karier sebagai _Application Developer_.

## Overview

Banyak buah dan sayur lokal dibuang hanya karena bentuknya tidak sempurna (bengkok, kecil, atau cacat kosmetik), padahal rasa dan gizinya sama. Hal ini menambah limbah makanan kota dan merugikan petani serta pedagang kecil.

>PasarKota memajang buah dan sayur lokal dengan harga diskon, sehingga pembeli hemat dan makanan terselamatkan.

| | |
|---|---|
| **Kategori** | Web |
| **Framework** | Laravel (Blade) + Tailwind CSS (CDN) |
| **Database** | MySQL 8.0 (container, named volume) |
| **Orkestrasi** | Docker Compose |

---

## Architecture
```
pasarkota/
├── Dockerfile
├── .dockerignore
├── docker-compose.yml
├── .env.example
├── routes/web.php                 # /, /produk, /health
├── resources/views/welcome.blade.php
└── database/migrations/           # tabel products
```
Aplikasi terhubung ke database lewat **nama service** (`DB_HOST=db`), bukan IP atau `localhost`. Service `app` baru berjalan setelah `db` berstatus *healthy* (`depends_on` dengan `condition: service_healthy`).


### Requirements

- [Docker](https://docs.docker.com/get-docker/) 24+ dengan Docker Compose v2 (perintah `docker compose`)
- Git
- Port **8000** (aplikasi), **3306** (MySQL), dan **8080** (Adminer, opsional) tidak sedang dipakai
- Koneksi internet stabil saat build pertama 

PHP, Composer, dan MySQL **tidak perlu dipasang** di komputer Anda, karena semuanya berjalan di dalam container.

Cek instalasi:

```bash
docker --version
docker compose version
git --version
```

---

## Running the Web
| Service | Image | Port host:container | Keterangan |
|---|---|---|---|
| `app` | build dari `Dockerfile` | 8000:8000 | Laravel (frontend Blade + backend) |
| `db` | `mysql:8.0` | 3306:3306 | Data di named volume `db_data`, punya healthcheck |
| `adminer` | `adminer` | 8080:8080 | UI database, hanya aktif dengan profile `tools` |

### Steps
#### 1. Clone Repository 
```bash
git clone L-vy/pasar-kota
cd pasarkota
```

#### 2. Buat file `.env`
```bash
cp .env.example .env
```
File `.env` menyimpan konfigurasi (nama database, password, dll.). `.env.example` hanya contoh dengan nilai dummy. Ubah setidaknya `DB_PASSWORD`.

#### 3. Build dan jalankan semua container
Pastikan Docker engine sudah running.

```bash
docker compose up -d --build
```
- `--build` membangun image aplikasi dari `Dockerfile`.
- `-d` menjalankan container di latar belakang.
- Container `db` (MySQL) dijalankan lebih dulu. Container `app` baru menyala setelah database berstatus *healthy*.

#### 4. Cek semua service berjalan
```bash
docker compose ps
```
Pastikan `app` dan `db` berstatus *running*, dan `db` berstatus *healthy*. Jika `db` masih *starting*, tunggu beberapa detik lalu cek lagi.

### 5. Buka aplikasi di browser

Kunjungi <http://localhost:8000>. Untuk memastikan backend hidup, buka juga <http://localhost:8000/health>, yang akan menampilkan `{"status":"healthy"}`.

### 6. (Opsional) Buka Adminer untuk melihat database

```bash
docker compose --profile tools up -d
```

Buka <http://localhost:8080>, lalu login dengan:

| Kolom | Isi |
|---|---|
| System | MySQL |
| Server | `db` |
| Username | `root` |
| Password | nilai `DB_PASSWORD` di `.env` |
| Database | nilai `DB_DATABASE` di `.env` |

Adminer memakai *profile* `tools`, jadi tidak ikut menyala pada langkah 3.

### 7. Hentikan aplikasi

```bash
docker compose down        # data database tetap aman
docker compose down -v     # data database ikut dihapus
```

`down` menghapus container dan network, tetapi data MySQL tetap tersimpan di volume `db_data`. `down -v` ikut menghapus volume, sehingga semua data hilang.

## Screenshots
### Features
#### 1. Main Page
#### 2. Catalog
#### 3. Basket
#### 4. Add Product

### Testing
#### 1. Docker
#### 2. Endpoint Testing   
| Method | Path | Fungsi | Status |
|---|---|---|---|
| GET | `/health` | Cek status aplikasi (`{"status":"healthy"}`) | 200 |
| GET | `/` | Menampilkan halaman dan katalog produk dari database | 200 |
| POST | `/produk` | Menyimpan produk baru, lalu redirect ke `/` | 302 |

#### 3. Data Persistence

## Links

- Repository: [github.com/L-vy/pasar-kota](https://github.com/L-vy/pasar-kota)
- Article: `[isi tautan artikel]`
- YouTube Video: `[isi tautan video]`


## Reference dan Atributtion

- [Laravel](https://laravel.com) (dibuat dengan Laravel installer), lisensi MIT
- [Tailwind CSS](https://tailwindcss.com) via CDN
- [IBM Plex Sans](https://fonts.google.com/specimen/IBM+Plex+Sans) via Google Fonts
- [MySQL](https://hub.docker.com/_/mysql) dan [Adminer](https://hub.docker.com/_/adminer) (Docker Official Images)


## AI Usage Declaration

AI telah digunakan untuk membantu merapihkan design website dan penulisan pada README.