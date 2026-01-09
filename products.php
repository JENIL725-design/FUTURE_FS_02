<?php
session_start();
include 'header.php';

// 1. PRODUCT DATA
$products = [
    1 => ["name" => "Mechanical Keyboard","price" => 49.99,  "img" => "img/keyboard.png"],
    2 => ["name" => "Logitech g29","price" => 129.99, "img" => "img/g29.png"],
    3 => ["name" => "hyperX gaming headphone","price" => 89.99, "img" => "img/headphone.png"],
    4 => ["name" => "ROG Swift 4K HDR Gaming Monitor PG27UQ","price" => 650.99, "img" => "img/monitor.png"],
    5 => ["name" => "ASUS ROG 4090","price" => 999.99, "img" => "img/asusrog.png"],
    6 => ["name" => "Razer Mouse Pad","price" => 29.99,  "img" => "img/mousepad.png"]
];

// 2. SORTING LOGIC
$sort_option = isset($_GET['sort']) ? $_GET['sort'] : 'default';
$sort_label = "Featured"; 

if($sort_option == 'price_low') {
    uasort($products, function($a, $b) { return $a['price'] - $b['price']; });
    $sort_label = "Price: Low to High";
} 
elseif($sort_option == 'price_high') {
    uasort($products, function($a, $b) { return $b['price'] - $a['price']; });
    $sort_label = "Price: High to Low";
} 
elseif($sort_option == 'name_asc') {
    uasort($products, function($a, $b) { return strcmp($a['name'], $b['name']); });
    $sort_label = "Name: A-Z";
}
?>

<style>
    /* Hero Banner */
    .hero-section {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        border-radius: 20px;
        padding: 60px 40px;
        color: white;
        margin-bottom: 50px;
        position: relative;
        overflow: hidden;
    }
    .hero-section::after {
        content: ''; position: absolute; top: -50%; right: -10%;
        width: 400px; height: 400px;
        background: radial-gradient(circle, rgba(99,102,241,0.4) 0%, rgba(0,0,0,0) 70%);
        border-radius: 50%; z-index: 0;
    }
    /* Card Styles */
    .product-card {
        background: white; border: 1px solid #f1f5f9; border-radius: 20px;
        overflow: hidden; transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        height: 100%; display: flex; flex-direction: column;
    }
    .product-card:hover {
        transform: translateY(-8px); box-shadow: 0 20px 40px rgba(0,0,0,0.08);
        border-color: transparent;
    }
    .img-container {
        position: relative; height: 240px; background: #f8fafc; overflow: hidden;
        display: flex; align-items: center; justify-content: center;
    }
    .img-container img { max-width: 80%; transition: transform 0.5s ease; }
    .product-card:hover .img-container img { transform: scale(1.1) rotate(-2deg); }
    .card-details { padding: 25px; flex-grow: 1; display: flex; flex-direction: column; }
    .product-cat { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8; font-weight: 600; margin-bottom: 8px; }
    .product-title { font-weight: 700; font-size: 1.15rem; margin-bottom: 10px; color: #1e293b; }
    .product-price { font-size: 1.25rem; font-weight: 600; color: #6366f1; margin-bottom: 20px; }
    
    /* Button */
    .btn-add {
        width: 100%; padding: 12px; border: none; background: #f1f5f9;
        color: #1e293b; border-radius: 12px; font-weight: 600;
        display: flex; align-items: center; justify-content: center; gap: 10px;
        transition: all 0.3s ease; margin-top: auto;
    }
    .btn-add:hover { background: #6366f1; color: white; box-shadow: 0 10px 20px rgba(99, 102, 241, 0.25); }
    .btn-add:active { transform: scale(0.98); }

    /* Animations */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .product-wrapper {
        opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
    }
    .product-wrapper:nth-child(1) { animation-delay: 0.1s; }
    .product-wrapper:nth-child(2) { animation-delay: 0.2s; }
    .product-wrapper:nth-child(3) { animation-delay: 0.3s; }
    .product-wrapper:nth-child(4) { animation-delay: 0.4s; }
    .product-wrapper:nth-child(5) { animation-delay: 0.5s; }
    .product-wrapper:nth-child(6) { animation-delay: 0.6s; }

    .fade-out-grid {
        opacity: 0 !important; transform: translateY(-10px); transition: all 0.3s ease-out;
    }
</style>

<div class="container">
    
    <div class="hero-section text-center text-md-start d-flex align-items-center justify-content-between">
        <div style="z-index: 1;">
            <h1 class="display-5 fw-bold mb-3">Upgrade Your Setup.</h1>
            <p class="lead text-white-50 mb-4">Premium gaming gear curated for performance.</p>
            <a href="#shop-grid" class="btn btn-light rounded-pill px-4 py-2 fw-bold text-primary">Browse Collection</a>
        </div>
        <div class="d-none d-md-block" style="z-index: 1;">
            <i class="fa-solid fa-gamepad display-1 text-white-50 opacity-25"></i>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4" id="shop-grid">
        <h3 class="fw-bold m-0">Latest Arrivals</h3>
        <div class="dropdown">
            <button class="btn btn-outline-secondary btn-sm dropdown-toggle rounded-pill px-3" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                Sort by: <strong><?php echo $sort_label; ?></strong>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                <li><a class="dropdown-item" href="?sort=default">Featured (Default)</a></li>
                <li><a class="dropdown-item" href="?sort=price_low">Price: Low to High</a></li>
                <li><a class="dropdown-item" href="?sort=price_high">Price: High to Low</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="?sort=name_asc">Name: A-Z</a></li>
            </ul>
        </div>
    </div>

    <div class="row g-4" id="product-grid">
        <?php foreach ($products as $id => $product): ?>
            <div class="col-sm-6 col-lg-4 product-wrapper">
                <div class="product-card">
                    <div class="img-container">
                        <img src="<?php echo $product['img']; ?>" alt="<?php echo $product['name']; ?>">
                    </div>

                    <div class="card-details">
                        <div class="product-cat">Gear</div>
                        <div class="product-title"><?php echo $product['name']; ?></div>
                        <div class="product-price">$<?php echo $product['price']; ?></div>
                        
                        <button type="button" 
                                class="btn-add add-btn" 
                                data-id="<?php echo $id; ?>" 
                                data-name="<?php echo $product['name']; ?>" 
                                data-price="<?php echo $product['price']; ?>">
                            Add to Cart <i class="fa-solid fa-plus"></i>
                        </button>
                    </div>

                </div>
            </div>
        <?php endforeach; ?>
    </div>

</div>

<script src="store.js"></script>
<script src="main.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const sortLinks = document.querySelectorAll('.dropdown-item');
        const productGrid = document.getElementById('product-grid');

        sortLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault(); 
                const targetUrl = this.href;
                productGrid.classList.add('fade-out-grid');
                setTimeout(() => {
                    window.location.href = targetUrl;
                }, 300);
            });
        });
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>