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
    <style>
        /* Đặt kích thước cố định cho ảnh trong carousel */
        .carousel-inner img {
            object-fit: cover; 
            border-radius: 10px; 
        }

        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            background-color: orange; 
        }

        .carousel-control-prev-icon:hover,
        .carousel-control-next-icon:hover {
            background-color: darkorange;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center mb-4">Chi tiết sản phẩm</h2>
    <div class="card">
        <div class="card-body d-flex flex-row">
<<<<<<< HEAD
            <div class="card-items mr-5">
                <?php
                $images = json_decode($product['anh']);
                if (is_array($images)) {
                    foreach ($images as $image) {
                        // Khi nhấn vào ảnh, hiển thị ảnh trong modal
                        echo "<a href='#' data-toggle='modal' data-target='#imageModal' onclick='changeModalImage(\"" . htmlspecialchars($image) . "\")'>
                                <img src='" . htmlspecialchars($image) . "' 
                                     srcset='" . htmlspecialchars($image) . " 1x, " . htmlspecialchars($image) . "@2x.jpg 2x' 
                                     alt='Ảnh sản phẩm' 
                                     class='img-thumbnail' 
                                     style='max-width: 400px; max-height: 400px; margin-right: 10px;'>
                              </a>";
                    }
                }
                ?>
=======
            <!-- Cột hiển thị ảnh sản phẩm (Carousel) -->
            <div class="col-md-6 pr-4">
                <div id="productImagesCarousel" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                        <?php
                        $images = json_decode($product['anh']);
                        if (is_array($images)) {
                            $isActive = true;
                            foreach ($images as $image) {
                                echo '<div class="carousel-item ' . ($isActive ? 'active' : '') . '">';
                                echo "<img src='" . htmlspecialchars($image) . "' alt='Ảnh sản phẩm' class='d-block w-100'>";
                                echo '</div>';
                                $isActive = false;
                            }
                        }
                        ?>
                    </div>
                    <a class="carousel-control-prev" href="#productImagesCarousel" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#productImagesCarousel" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
>>>>>>> c2d6aca84f78f6e77d36710ad1386d4d54d88cea
            </div>

            <!-- Cột hiển thị thông tin sản phẩm -->
            <div class="col-md-6">
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

<!-- Modal để phóng to ảnh -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <img id="modalImage" src="" alt="Ảnh phóng to" class="img-fluid">
            </div>
        </div>
    </div>
</div>

<script>
    function changeModalImage(imageSrc) {
        document.getElementById('modalImage').src = imageSrc;
    }
</script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>

<?php
$conn->close();
?>
