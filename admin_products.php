<?php
include 'admin_header.php';
require 'db_connect.php';

// 1. HANDLE ADD PRODUCT
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $target_dir = "img/";
    if (!is_dir($target_dir)) { mkdir($target_dir, 0777, true); }

    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        $stmt = $pdo->prepare("INSERT INTO products (name, price, image) VALUES (?, ?, ?)");
        $stmt->execute([$name, $price, $target_file]);
        echo "<script>Swal.fire('Success', 'Product added!', 'success');</script>";
    } else {
        echo "<script>Swal.fire('Error', 'Image upload failed.', 'error');</script>";
    }
}

// ✨ 2. NEW: HANDLE EDIT PRODUCT
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_product'])) {
    $id = $_POST['product_id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $image_path = $_POST['current_image'];

    // If a new image is uploaded, process it
    if (!empty($_FILES["image"]["name"])) {
        $target_dir = "img/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image_path = $target_file;
        }
    }

    $stmt = $pdo->prepare("UPDATE products SET name = ?, price = ?, image = ? WHERE id = ?");
    $stmt->execute([$name, $price, $image_path, $id]);
    echo "<script>Swal.fire('Updated!', 'Product information has been saved.', 'success').then(() => window.location.href='admin_products.php');</script>";
}

// 3. HANDLE DELETE
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $pdo->prepare("DELETE FROM products WHERE id = ?")->execute([$id]);
    echo "<script>window.location.href='admin_products.php';</script>";
}

$products = $pdo->query("SELECT * FROM products ORDER BY id DESC")->fetchAll();
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">Product Management</h3>
        <button class="btn btn-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#addProductModal">
            <i class="fa-solid fa-plus me-2"></i> Add New Product
        </button>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body">
            <table class="table align-middle">
                <thead><tr><th>Image</th><th>Name</th><th>Price</th><th>Action</th></tr></thead>
                <tbody>
                    <?php foreach ($products as $p): ?>
                    <tr>
                        <td><img src="<?php echo $p['image']; ?>" width="50" class="rounded"></td>
                        <td class="fw-bold"><?php echo htmlspecialchars($p['name']); ?></td>
                        <td>$<?php echo $p['price']; ?></td>
                        <td>
                            <button class="btn btn-sm btn-info rounded-pill text-white edit-btn" 
                                    data-id="<?php echo $p['id']; ?>" 
                                    data-name="<?php echo htmlspecialchars($p['name']); ?>" 
                                    data-price="<?php echo $p['price']; ?>"
                                    data-image="<?php echo $p['image']; ?>"
                                    data-bs-toggle="modal" data-bs-target="#editProductModal">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <a href="?delete=<?php echo $p['id']; ?>" class="btn btn-danger btn-sm rounded-pill" onclick="return confirm('Delete?')"><i class="fa-solid fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="addProductModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Add New Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3"><label>Name</label><input type="text" name="name" class="form-control" required></div>
                    <div class="mb-3"><label>Price</label><input type="number" step="0.01" name="price" class="form-control" required></div>
                    <div class="mb-3"><label>Image</label><input type="file" name="image" class="form-control" required></div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="add_product" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editProductModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Edit Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="product_id" id="edit_id">
                <input type="hidden" name="current_image" id="edit_current_image">
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Name</label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Price</label>
                        <input type="number" step="0.01" name="price" id="edit_price" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Image (Leave blank to keep current)</label>
                        <input type="file" name="image" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="edit_product" class="btn btn-info text-white">Update Product</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Script to fill the Edit Modal with current data
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function() {
            document.getElementById('edit_id').value = this.dataset.id;
            document.getElementById('edit_name').value = this.dataset.name;
            document.getElementById('edit_price').value = this.dataset.price;
            document.getElementById('edit_current_image').value = this.dataset.image;
        });
    });
</script>

</div> 
</div> 
</body>
</html>