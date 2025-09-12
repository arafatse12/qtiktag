{{-- <!DOCTYPE html>
<html>
<head>
    <title>Product Details</title>
</head>
<body>
    <h1>Product Details</h1>
    <a href="{{ route('products.index') }}">Back</a>

    <p><strong>ID: </strong> {{ $product->id }}</p>
    <p><strong>Name: </strong> {{ $product->name }}</p>

    <p><strong>QR Code:</strong></p>
    {!! QrCode::size(200)->generate(route('products.show', $product->id)) !!}
@php
    $svg = QrCode::format('svg')->size(200)->generate(route('products.show', $product->id));
@endphp
    <div>{!! $svg !!}</div>

<p>
    <a href="data:image/svg+xml;base64,{{ base64_encode($svg) }}" download="product-{{ $product->id }}.svg">
        Download QR Code
    </a>
</p>
</body>
</html> --}}

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Product Details Page</title>
  <link rel="stylesheet" href="styles.css">
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
  <style>
    body {
  margin: 0;
  font-family: Arial, sans-serif;
  background: #fafafa;
  color: #333;
}

/* Hero Section */
.hero {
  position: relative;
  text-align: center;
  background: #f5f5f5;
}
.hero img {
  width: 100%;
  max-height: 400px;
  object-fit: cover;
}
.hero-info {
  padding: 20px;
}
.hero-info h1 {
  margin: 0;
  font-size: 28px;
  font-weight: 600;
}
.hero-info p {
  margin: 8px 0 0;
  font-size: 16px;
  color: #555;
}

/* Details Sections */
.details {
  padding: 20px;
  max-width: 900px;
  margin: auto;
}
.details section {
  margin-bottom: 30px;
  padding: 20px;
  background: white;
  border-radius: 8px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}
.details h2 {
  margin-top: 0;
  font-size: 20px;
  border-bottom: 2px solid #eee;
  padding-bottom: 6px;
}

/* FAB */
.fab {
  position: fixed;
  bottom: 20px;
  right: 20px;
  width: 56px;
  height: 56px;
  border-radius: 50%;
  border: none;
  background: #b71c1c;
  color: white;
  font-size: 28px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 4px 10px rgba(0,0,0,0.3);
  z-index: 30;
}

/* Overlay */
.overlay {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.6);
  z-index: 20;
}
.hidden { display: none; }

/* Menu Modal */
.menu-modal {
  position: fixed;
  bottom: 80px;
  left: 50%;
  transform: translateX(-50%);
  width: 90%;
  max-width: 400px;
  background: white;
  border-radius: 8px;
  padding: 16px;
  z-index: 25;
  box-shadow: 0 8px 20px rgba(0,0,0,0.3);
  animation: fadeInUp 0.3s ease;
}

.menu-title {
  margin: 0 0 10px;
  font-size: 18px;
  font-weight: 600;
  border-bottom: 1px solid #eee;
  padding-bottom: 6px;
}

.menu-list {
  list-style: none;
  padding: 0;
  margin: 0;
}
.menu-list li {
  padding: 10px 6px;
  display: flex;
  align-items: center;
  font-size: 15px;
  cursor: pointer;
  border-bottom: 1px solid #f2f2f2;
  transition: background 0.2s;
}
.menu-list li i {
  margin-right: 10px;
  font-size: 18px;
  color: #555;
}
.menu-list li:hover {
  background: #f9f9f9;
}

.cancel-btn {
  margin-top: 12px;
  width: 100%;
  padding: 10px;
  border: none;
  border-radius: 6px;
  background: #f5f5f5;
  cursor: pointer;
  font-size: 15px;
  transition: background 0.2s;
}
.cancel-btn:hover {
  background: #e0e0e0;
}

/* Animation */
@keyframes fadeInUp {
  from {
    transform: translate(-50%, 20px);
    opacity: 0;
  }
  to {
    transform: translate(-50%, 0);
    opacity: 1;
  }
}

  </style>
</head>
<body>

  <!-- Hero / Product Section -->
  <header class="hero">
    <img src="https://via.placeholder.com/600x400" alt="Product Image">
    <div class="hero-info">
      <h1>Wool Blend Coat</h1>
      <p>Stylish winter coat with premium fabric blend.</p>
    </div>
  </header>

  <!-- Details Sections -->
  <main class="details">
    <section id="product-id">
      <h2>Product ID</h2>
      <p>HM0004588CX000006</p>
    </section>

    <section id="manufacturing">
      <h2>Manufacturing Process</h2>
      <p>This product was manufactured in certified factories ensuring fair labor practices.</p>
    </section>

    <section id="materials">
      <h2>Key Materials & Components</h2>
      <p>70% Wool, 20% Polyester, 10% Acrylic.</p>
    </section>

    <section id="chain">
      <h2>Chain of Custody</h2>
      <p>Tracked through verified suppliers ensuring authenticity.</p>
    </section>

    <section id="recycling">
      <h2>Usage Safety Repair & Recycling</h2>
      <p>Machine washable. Recyclable through our return program.</p>
    </section>

    <section id="certifications">
      <h2>Certifications</h2>
      <p>OEKO-TEX® certified fabric, Fair Wear certified production.</p>
    </section>

    <section id="sustainability">
      <h2>Sustainability</h2>
      <p>Made with 30% recycled wool and low-impact dye process.</p>
    </section>

    <section id="impact">
      <h2>Environmental Impact & Footprint</h2>
      <p>Carbon footprint reduced by 15% compared to previous models.</p>
    </section>
  </main>

  <!-- Floating Action Button -->
  <button id="fab" class="fab">
    <i class='bx bx-dots-vertical-rounded'></i>
  </button>

  <!-- Overlay -->
  <div id="overlay" class="overlay hidden"></div>

  <!-- Modal Menu -->
  <div id="menuModal" class="menu-modal hidden">
    <h3 class="menu-title">Menu</h3>
    <ul class="menu-list">
      <li data-target="product-id"><i class='bx bx-barcode-reader'></i> Product ID</li>
      <li data-target="manufacturing"><i class='bx bx-cog'></i> Manufacturing Process</li>
      <li data-target="materials"><i class='bx bx-package'></i> Key Materials & Components</li>
      <li data-target="chain"><i class='bx bx-link-alt'></i> Chain of Custody</li>
      <li data-target="recycling"><i class='bx bx-recycle'></i> Usage Safety Repair & Recycling</li>
      <li data-target="certifications"><i class='bx bx-certification'></i> Certifications</li>
      <li data-target="sustainability"><i class='bx bx-leaf'></i> Sustainability</li>
      <li data-target="impact"><i class='bx bx-footprint'></i> Environmental Impact & Footprint</li>
    </ul>
    <button id="closeMenu" class="cancel-btn">Cancel</button>
  </div>

<script>
    const fab = document.getElementById("fab");
const modal = document.getElementById("menuModal");
const overlay = document.getElementById("overlay");
const closeMenu = document.getElementById("closeMenu");
const menuItems = document.querySelectorAll(".menu-list li");

// Open modal
fab.addEventListener("click", () => {
  modal.classList.remove("hidden");
  overlay.classList.remove("hidden");
});

// Close modal
closeMenu.addEventListener("click", () => {
  modal.classList.add("hidden");
  overlay.classList.add("hidden");
});
overlay.addEventListener("click", () => {
  modal.classList.add("hidden");
  overlay.classList.add("hidden");
});

// Scroll to section when clicking menu item
menuItems.forEach(item => {
  item.addEventListener("click", () => {
    const targetId = item.getAttribute("data-target");
    const targetSection = document.getElementById(targetId);

    if (targetSection) {
      targetSection.scrollIntoView({ behavior: "smooth" });
    }

    modal.classList.add("hidden");
    overlay.classList.add("hidden");
  });
});
</script>
</body>
</html>


