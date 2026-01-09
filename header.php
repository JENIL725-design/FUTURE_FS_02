<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NexGen Shop</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        :root {
            --primary-color: #6366f1; /* Modern Indigo */
            --secondary-color: #1e293b; /* Slate Dark */
            --accent-color: #06b6d4; /* Cyan */
            --bg-color: #f8fafc;
            --text-dark: #0f172a;
        }

        body {
            background-color: var(--bg-color);
            font-family: 'Poppins', sans-serif;
            color: var(--text-dark);
            -webkit-font-smoothing: antialiased;
        }

        /* Modern Glass Navbar */
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.03);
            padding: 15px 0;
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--secondary-color) !important;
            font-size: 1.5rem;
            letter-spacing: -0.5px;
        }

        .nav-link {
            color: #64748b !important;
            font-weight: 500;
            margin: 0 10px;
            transition: color 0.3s;
        }

        .nav-link:hover {
            color: var(--primary-color) !important;
        }

        .btn-nav-cart {
            background: var(--secondary-color);
            color: white !important;
            border-radius: 50px;
            padding: 8px 25px;
            font-size: 0.9rem;
            transition: all 0.3s;
        }

        .btn-nav-cart:hover {
            background: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(99, 102, 241, 0.3);
        }

        /* Container adjustment */
        .main-content {
            padding-top: 40px;
            padding-bottom: 80px;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand" href="products.php">
            <i class="fa-solid fa-layer-group text-primary me-2"></i>NexGenStore<span class="text-primary">.</span>
        </a>
        
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="fa-solid fa-bars"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item"><a class="nav-link" href="products.php">Discover</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Deals</a></li>
                <li class="nav-item">
                    <a class="nav-link btn-nav-cart ms-2" href="cart_view.php">
                        <i class="fa-solid fa-bag-shopping me-1"></i> Cart
                        <span class="badge bg-white text-dark rounded-pill ms-1">
                            <?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>
                        </span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="main-content">