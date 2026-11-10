# PasarKota

<p align="center">
  <img src="public/images/logo.png" alt="Logo PasarKota" width="200">
</p>

> PasarKota adalah Marketplace untuk buah dan sayur lokal berdiskon yang bentuknya kurang sempurna.

Banyak buah dan sayur lokal dibuang hanya karena bentuknya tidak sempurna (bengkok, kecil, atau cacat kosmetik), padahal rasa dan gizinya sama. Hal ini menambah limbah makanan kota dan merugikan petani serta pedagang kecil. 

PasarKota memajang buah dan sayur lokal dengan harga diskon, sehingga pembeli hemat dan makanan terselamatkan.

Proyek ini berangkat dari keinginan untuk membangun solusi yang selaras dengan SDG 12 dan berdampak langsung pada masyarakat lokal, khususnya dalam mengurangi food waste. Selain itu, proyek ini menjadi sarana latihan membangun aplikasi web multi-tier dan mengemasnya dengan Docker, sebagai bekal karier sebagai _Application Developer_.

## Overview

Website marketplace untuk membeli buah dan sayur lokal dengan harga diskon yang masih sangat layak dikonsumsi meskipun memiliki cacat visual atau tidak memenuhi standar estetika visual supermarket. Aplikasi PasarKota dibangun menggunakan framework Laravel dengan Tailwind CSS, database MySQL, dan di dalam container menggunakan Docker Compose.

| | |
|---|---|
| **Kategori** | Web |
| **Framework** | Laravel (Blade) + Tailwind CSS (CDN) |
| **Database** | MySQL 8.0 (container, named volume) |
| **Orkestrasi** | Docker Compose |

---

## Structure
```
pasarkota/
├── app/
├── bootstrap/
├── config/
├── database/
├── docs/
│   └── screenshots/
├── public/
│   └── images/
├── resources/
│   └── views/
│       └── welcome.blade.php
├── routes/
│   └── web.php
├── storage/
├── .dockerignore
├── .env.example
├── .gitignore
├── composer.json
├── composer.lock
├── docker-compose.yml
├── Dockerfile
└── README.md
```
Aplikasi terhubung ke database lewat **nama service** (`DB_HOST=db`), bukan IP atau `localhost`. Service `app` baru berjalan setelah `db` berstatus *healthy*.


### Requirements

- [Docker](https://docs.docker.com/get-docker/) 24+ dengan Docker Compose v2 (perintah `docker compose`)
- Git
- Port **8000** (aplikasi), **3306** (MySQL), dan **8080** (Adminer, opsional) tidak sedang dipakai
- Koneksi internet stabil saat build pertama 

Cek instalasi:

```bash
docker --version
docker compose version
git --version
```
>PHP, Composer, dan MySQL **tidak perlu dipasang** di komputer Anda, karena semuanya berjalan di dalam container.


---

## Running the Web
| Service | Image | Port host:container | Keterangan |
|---|---|---|---|
| `app` | build dari `Dockerfile` | 8000:8000 | Laravel (frontend Blade + backend) |
| `db` | `mysql:8.0` | 3306:3306 | Data di named volume `db_data`, healthcheck |
| `adminer` | `adminer` | 8080:8080 | UI database, hanya aktif dengan profile `tools` |

### Steps
#### 1. Clone Repository 
```bash
git clone https://github.com/L-vy/pasarkota.git
cd pasarkota
```

#### 2. Buat file `.env`
```bash
cp .env.example .env
```
File `.env` menyimpan konfigurasi nama database dan password. `.env.example` hanya contoh dengan nilai dummy. Ubah setidaknya `DB_PASSWORD`.

#### 3. Build dan jalankan semua container
Pastikan Docker engine sudah running.

```bash
docker compose up -d --build
```
- `--build` membangun image aplikasi dari `Dockerfile`.
- `-d` menjalankan container di latar belakang.

#### 4. Cek semua service berjalan
```bash
docker compose ps
```
Pastikan `app` dan `db` berstatus *running*, dan `db` berstatus *healthy*. Jika `db` masih *starting*, tunggu beberapa detik lalu cek lagi.

#### 5. Buka aplikasi di browser

Kunjungi <http://localhost:8000>. Untuk memastikan backend running, buka juga <http://localhost:8000/health>, yang akan menampilkan `{"status":"healthy"}`.

#### 6. (Opsional) Buka Adminer untuk melihat database

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

#### 7. Hentikan aplikasi

```bash
docker compose down        # named data database tetap aman
docker compose down -v     # named data database ikut dihapus
```

`down` menghapus container dan network, tetapi data MySQL tetap tersimpan di volume `db_data`. `down -v` ikut menghapus volume, sehingga semua data hilang.

### Screenshots

#### 1. Main Page
![Main Page](docs/screenshots/1%20-%20homepage.png)

#### 2. Catalog
![Catalog](docs/screenshots/2%20-%20product%20catalog.png)

#### 3. Basket
![Basket](docs/screenshots/3%20-%20shopping%20basket.png)

#### 4. Add Product
![Add Product](docs/screenshots/4%20-%20adding%20a%20product.png)

## Endpoints

| Method | Path | Fungsi | Status |
|---|---|---|---|
| GET | `/health` | Cek status aplikasi dan koneksi database (`{"status":"healthy","database":"connected"}`) | 200 (503 jika database tidak terhubung) |
| GET | `/` | Menampilkan halaman web dan katalog produk dari database | 200 |
| POST | `/produk` | Menyimpan produk dari form web, lalu redirect ke `/` | 302 |
| GET | `/api/products` | Mengambil semua produk dalam format JSON | 200 |
| POST | `/api/products` | Menyimpan produk baru (JSON), mengembalikan produk yang dibuat | 201 (422 jika validasi gagal) |

Contoh request dengan **Powershell**
```bash
# Healthcheck
Invoke-RestMethod http://localhost:8000/health
Invoke-WebRequest http://localhost:8000/health -UseBasicParsing #Invoke-WebRquest untuk melihat statuscode

# Baca katalog produk
Invoke-RestMethod http://localhost:8000/api/products
Invoke-WebRequest http://localhost:8000/api/products -UseBasicParsing

# Contoh menambah produk
Invoke-WebRequest -Uri http://localhost:8000/api/products -Method POST -ContentType "application/json" -Body '{"nama_produk":"Cabai Rawit 500 g","harga_asli":25000,"harga_diskon":12000}' -UseBasicParsing

# Contoh data tidak valid 
Invoke-WebRequest -Uri http://localhost:8000/api/products -Method POST -ContentType "application/json" -Body '{"nama_produk":""}' -UseBasicParsing
```

---

## Links

- Repository: [github.com/L-vy/pasar-kota](https://github.com/L-vy/pasar-kota)
- Article: [medium.com/@latasha.ivy.khoe.2006/pasarkota-reducing-local-foodwaste-with-containerization-7bcff8d8fd56](https://medium.com/@latasha.ivy.khoe.2006/pasarkota-reducing-local-foodwaste-with-containerization-7bcff8d8fd56)
- YouTube Video: [youtu.be/nSYrALvQMqM](https://youtu.be/nSYrALvQMqM)


## Reference dan Atributtion

- [Laravel](https://laravel.com) (dibuat dengan Laravel installer), lisensi MIT
- [Tailwind CSS](https://tailwindcss.com) via CDN
- [IBM Plex Sans](https://fonts.google.com/specimen/IBM+Plex+Sans) via Google Fonts
- [MySQL](https://hub.docker.com/_/mysql) dan [Adminer](https://hub.docker.com/_/adminer) (Docker Official Images)


## AI Usage Declaration

AI telah digunakan untuk membantu merapihkan design website dan penulisan pada README.