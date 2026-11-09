<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title>PasarKota</title>
<link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com/3.4.17"></script>
<script>
tailwind.config = {
  theme: {
    borderRadius: { none: '0', DEFAULT: '0' },
    extend: {
      fontFamily: { sans: ['"IBM Plex Sans"', 'Helvetica Neue', 'Arial', 'sans-serif'] },
      colors: { brand: { DEFAULT: '#198038', dark: '#0e6027', light: '#defbe6' }, diskon: '#E3651D' }
    }
  }
}
</script>
<style>
  :root { color-scheme: light; box-sizing: border-box; padding-top: env(safe-area-inset-top, 0px); padding-bottom: env(safe-area-inset-bottom, 0px); }
  html { scroll-padding-top: env(safe-area-inset-top, 0px); scroll-behavior: smooth; }
  body { background: #FDFAF6; color: #161616; }
  body .bg-white { background-color: #FDFAF6; }
  body .bg-gray-100, body .bg-gray-200, body .hover\:bg-gray-100:hover { background-color: #EEEFE0; }
  *, *::before, *::after { border-radius: 0 !important; }
  input:focus { outline: 2px solid #198038; outline-offset: -2px; }
</style>
</head>
<body class="font-sans antialiased">

<!-- NAVBAR -->
<header class="bg-white border-b border-gray-300">
  <nav class="max-w-6xl mx-auto px-6 h-14 flex items-center">
    <a href="#home" class="text-xl font-bold tracking-tight">Pasar<span class="text-brand">Kota</span></a>
    <div class="ml-6 md:ml-10 flex h-full">
      <a href="#home" class="h-full flex items-center px-3 md:px-4 text-sm font-medium border-b-2 border-transparent hover:bg-gray-100 hover:border-brand">Home</a>
      <a href="#keranjang" class="h-full flex items-center px-3 md:px-4 text-sm font-medium border-b-2 border-transparent hover:bg-gray-100 hover:border-brand">Keranjang <span id="badge" class="ml-2 bg-brand text-white text-xs px-1.5 py-0.5 hidden">0</span></a>
      <a href="#tambah" class="h-full flex items-center px-3 md:px-4 text-sm font-medium border-b-2 border-transparent hover:bg-gray-100 hover:border-brand">Tambah Produk</a>
    </div>
  </nav>
</header>

<!-- HERO -->
<section id="home" class="bg-white border-b border-gray-300">
  <div class="max-w-6xl mx-auto px-6 py-20 md:py-28">
    <p class="text-sm font-medium text-brand uppercase tracking-widest mb-4">The City Marketplace</p>
    <h1 class="text-4xl md:text-6xl font-light leading-tight max-w-3xl">
      Bentuknya tak sempurna, <span class="font-semibold">gizinya dapat beli.</span>
    </h1>
    <p class="mt-6 text-lg text-gray-600 max-w-xl">
      Selamatkan buah dan sayur lokal berdiskon dari tempat sampah. Hemat di dompet, baik untuk kota kita.
    </p>
    <a href="#katalog" class="inline-block mt-10 bg-brand hover:bg-brand-dark text-white font-medium px-8 py-4 transition-colors">
      Mulai Belanja
    </a>
  </div>
</section>

@if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
        {{ session('success') }}
    </div>
@endif

<!-- KATALOG -->
<main>
<section id="katalog" class="max-w-6xl mx-auto px-6 py-16">
  <div class="mb-10">
    <h2 class="text-3xl font-semibold">Katalog Produk</h2>
  </div>

  <div id="grid" class="grid grid-cols-1 md:grid-cols-3 border-t border-l border-gray-300">

    @foreach ($products as $product)
    <article class="bg-white flex flex-col border-r border-b border-gray-300">
      <div class="relative aspect-[4/3] w-full overflow-hidden bg-gray-200">
        @if ($product->gambar_url)
            <img src="{{ $product->gambar_url }}" alt="{{ $product->nama_produk }}"
                class="absolute inset-0 w-full h-full object-cover">
        @else
            <div class="absolute inset-0 flex items-center justify-center">
            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="16"/><circle cx="9" cy="10" r="1.5"/><path d="M3 18l6-6 4 4 3-3 5 5"/></svg>
            </div>
        @endif
        </div>
      <div class="p-6 flex flex-col flex-1">
        <h3 class="text-lg font-semibold">{{ $product->nama_produk }}</h3>
        <div class="mt-4">
          <p class="text-sm text-gray-500 line-through">Rp {{ number_format($product->harga_asli, 0, ',', '.')}}</p>
          <p class="text-2xl font-semibold text-diskon">Rp {{ number_format($product->harga_diskon, 0, ',', '.')}}</p>
        </div>
        <button class="mt-6 bg-brand hover:bg-brand-dark text-white font-medium py-3 w-full transition-colors">Beli</button>

        <form action="/produk/{{ $product->id }}" method="POST" onsubmit="return confirm('Hapus produk ini?')">
          @csrf
          @method('DELETE')
          <button type="submit" class="mt-2 w-full border border-gray-400 text-gray-700 hover:bg-gray-100 font-medium py-3 transition-colors">Hapus</button>
        </form>
      </div>
    </article>
    @endforeach

  </div>
</section>

<!-- KERANJANG -->
<section id="keranjang" class="max-w-6xl mx-auto px-6 pb-16">
  <div class="mb-10">
    <h2 class="text-3xl font-semibold">Keranjang</h2>
  </div>
  <div class="bg-white border border-gray-300">
    <p id="kosong" class="p-8 text-gray-500">Keranjang masih kosong. Pilih produk dari katalog lalu tekan Beli.</p>
    <ul id="daftar" class="divide-y divide-gray-300"></ul>
    <div id="total-wrap" class="hidden">
      <div class="flex justify-between items-center p-6 border-t border-gray-300 bg-gray-100">
        <span class="text-sm font-medium">Total</span>
        <span id="total" class="text-2xl font-semibold text-brand">Rp 0</span>
      </div>
    </div>
  </div>
</section>

<div class="max-w-6xl mx-auto px-6"><hr class="border-t-4 border-gray-900"></div>

<!-- TAMBAH PRODUK -->
<section id="tambah" class="max-w-6xl mx-auto px-6 py-16">
  <div class="mb-10">
    <h2 class="text-3xl font-semibold">Tambah Produk</h2>
  </div>

  <form id="form-produk" action="/produk" method="POST" class="bg-white border border-gray-300 p-8 max-w-2xl">
    @csrf
    <div class="space-y-6">
      <div>
        <label for="nama" class="block text-sm font-medium mb-2">Nama Produk</label>
        <input id="nama" name="nama_produk" type="text" required placeholder="Contoh: Cabai Rawit 500 g" class="w-full bg-gray-100 border-0 border-b border-gray-500 px-4 py-3">
      </div>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div>
          <label for="harga_asli" class="block text-sm font-medium mb-2">Harga Asli (Rp)</label>
          <input id="harga_asli" name="harga_asli" type="number" min="0" required placeholder="25000" class="w-full bg-gray-100 border-0 border-b border-gray-500 px-4 py-3">
        </div>
        <div>
          <label for="harga_diskon" class="block text-sm font-medium mb-2">Harga Diskon (Rp)</label>
          <input id="harga_diskon" name="harga_diskon" type="number" min="0" required placeholder="12000" class="w-full bg-gray-100 border-0 border-b border-gray-500 px-4 py-3">
        </div>
      </div>
      <div>
        <label for="gambar" class="block text-sm font-medium mb-2">URL Gambar</label>
        <p class="mt-2 text-xs text-gray-500">*optional</p>
        <input id="gambar" name="gambar_url" type="url" placeholder="https://contoh.com/gambar.jpg" class="w-full bg-gray-100 border-0 border-b border-gray-500 px-4 py-3">
      </div>
    </div>
    <button type="submit" class="mt-8 bg-brand hover:bg-brand-dark text-white font-medium px-8 py-4 transition-colors">Simpan Produk</button>
    <p id="pesan" class="hidden mt-4 text-sm text-brand font-medium"></p>
  </form>
</section>
</main>

<!-- FOOTER -->
<footer class="bg-gray-900 text-gray-300">
  <div class="max-w-6xl mx-auto px-6 py-8 text-sm">
    © 2026 PasarKota.
  </div>
</footer>

<script>
  var cart = [];
  var rp = function (n) { return 'Rp ' + n.toLocaleString('id-ID'); };
  function renderCart() {
    var ul = document.getElementById('daftar');
    ul.innerHTML = '';
    var total = 0, count = 0;
    cart.forEach(function (it, i) {
      total += it.harga * it.qty; count += it.qty;
      var li = document.createElement('li');
      li.className = 'flex flex-wrap items-center justify-between gap-4 p-6';
      var info = document.createElement('div');
      var nm = document.createElement('p'); nm.className = 'font-semibold'; nm.textContent = it.nama;
      var sub = document.createElement('p'); sub.className = 'text-sm text-gray-500'; sub.textContent = rp(it.harga) + ' × ' + it.qty;
      info.appendChild(nm); info.appendChild(sub);
      var right = document.createElement('div'); right.className = 'flex items-center gap-6';
      var sum = document.createElement('span'); sum.className = 'font-semibold'; sum.textContent = rp(it.harga * it.qty);
      var del = document.createElement('button'); del.type = 'button';
      del.className = 'text-sm text-gray-600 border border-gray-400 px-3 py-1 hover:bg-gray-100'; del.textContent = 'Hapus';
      del.addEventListener('click', function () { cart.splice(i, 1); renderCart(); });
      var step = document.createElement('div'); step.className = 'flex items-center border border-gray-400';
      var mk = function (label, delta) {
        var b = document.createElement('button'); b.type = 'button';
        b.className = 'w-9 h-9 text-lg hover:bg-gray-100 disabled:opacity-30 disabled:hover:bg-transparent';
        b.textContent = label; b.setAttribute('aria-label', delta > 0 ? 'Tambah jumlah' : 'Kurangi jumlah');
        b.disabled = delta < 0 && it.qty <= 1;
        b.addEventListener('click', function () { it.qty += delta; renderCart(); });
        return b;
      };
      var q = document.createElement('span'); q.className = 'w-10 text-center text-sm font-medium'; q.textContent = it.qty;
      step.appendChild(mk('−', -1)); step.appendChild(q); step.appendChild(mk('+', 1));
      right.appendChild(step); right.appendChild(sum); right.appendChild(del);
      li.appendChild(info); li.appendChild(right); ul.appendChild(li);
    });
    document.getElementById('kosong').classList.toggle('hidden', cart.length > 0);
    document.getElementById('total-wrap').classList.toggle('hidden', cart.length === 0);
    document.getElementById('total').textContent = rp(total);
    var b = document.getElementById('badge');
    b.textContent = count; b.classList.toggle('hidden', count === 0);
  }
  document.querySelectorAll('#grid .btn-beli').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var card = btn.closest('article');
      var nama = card.querySelector('h3').textContent;
      var harga = parseInt(card.querySelector('.text-diskon').textContent.replace(/\D/g, ''), 10);
      var ada = cart.find(function (x) { return x.nama === nama; });
      if (ada) { ada.qty++; } else { cart.push({ nama: nama, harga: harga, qty: 1 }); }
      renderCart();
    });
  });
</script>
</body>
</html>
