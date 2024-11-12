<?php
include 'connect.php';  // Kết nối cơ sở dữ liệu

// Kiểm tra xem mã sản phẩm có được truyền vào hay không
if (isset($_GET['masp'])) {
    $masp = $_GET['masp'];

    // Truy vấn để lấy thông tin sản phẩm dựa trên mã sản phẩm
    $sql = "SELECT masp, tensp, dongia, anh, mota FROM sanpham WHERE masp = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $masp);
    $stmt->execute();
    $result = $stmt->get_result();

    // Kiểm tra nếu tìm thấy sản phẩm
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        echo "Không tìm thấy sản phẩm.";
        exit();
    }
} else {
    echo "Mã sản phẩm không hợp lệ.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết sản phẩm</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center mb-4">Chi tiết sản phẩm</h2>
    <div class="card">
        <div class="card-body d-flex flex-row">
            <div class="card-items mr-5">
                <?php
                $images = json_decode($product['anh']);
                if (is_array($images)) {
                    foreach ($images as $image) {
                        echo "<img src='" . htmlspecialchars($image) . "' alt='Ảnh sản phẩm' class='img-thumbnail' style='width: 400px; height: 400px; margin-right: 10px;'>";
                    }
                }
                ?>
            </div>
            <div>
                <h3 class="pb-3"><?php echo htmlspecialchars($product['tensp']); ?></h3>
                <ul>
                    <li>
                    <p><strong>Mã sản phẩm:</strong> <?php echo htmlspecialchars($product['masp']); ?></p>
                    </li>
                    <li>
                    <p><strong>Đơn giá:</strong> <?php echo number_format($product['dongia'], 0, ',', '.') . " VND"; ?></p>
                    </li>
                    <li>
                    <p><strong>Mô tả:</strong> <?php echo htmlspecialchars($product['mota']); ?></p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <a href="index.php" class="btn btn-secondary mt-4">Quay lại trang danh sách sản phẩm</a>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>

<?php
$conn->close();
?>
