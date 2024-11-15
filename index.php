<?php
include 'connect.php';  // Kết nối cơ sở dữ liệu
$sql = "SELECT masp, tensp, dongia, soluong, mota, anh FROM sanpham";
$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách sản phẩm</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>


<div class="container mt-5">
    <h2 class="text-center mb-4">Danh sách sản phẩm</h2>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Danh sách sản phẩm</h2>
        <a href="add_product.php" class="btn btn-primary">Thêm mới</a>
    </div>
    <table class="table table-striped table-bordered table-hover">
        <thead class="thead-dark">
            <tr>
                <th scope="col">Mã sản phẩm</th>
                <th scope="col">Tên sản phẩm</th>
                <th scope="col">Đơn giá</th>
                <th scope="col">Số lượng</th>
                <th scope="col">Mô tả</th>
                <th scope="col">Ảnh</th>
                <th scope="col">Xem</th>
            </tr>
        </thead>
        <tbody>
        <?php
    if ($result->num_rows > 0) {
        // Lặp qua từng sản phẩm trong kết quả truy vấn
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['masp']) . "</td>";
            echo "<td>" . htmlspecialchars($row['tensp']) . "</td>";
            echo "<td>" . number_format($row['dongia'], 0, ',', '.') . " VND</td>";
            echo "<td>" . number_format($row['soluong'], 0, ',', '.') . "</td>";
            echo "<td>" . htmlspecialchars($row['mota']) . "</td>";

            // Đảm bảo ảnh nằm gọn trong thẻ <td>
            echo "<td>";
            $images = json_decode($row['anh']);
            if (is_array($images)) {
                foreach ($images as $image) {
                    echo "<img src='" . htmlspecialchars($image) . "' alt='Ảnh sản phẩm' class='img-thumbnail' style='width: 50px; height: 50px; margin-right: 10px;'>";
                }
            }
            echo "</td>";

            // Thêm liên kết "Xem chi tiết"
            echo "<td><a href='product_details.php?masp=" . urlencode($row['masp']) . "'>Xem chi tiết</a></td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='7' class='text-center'>Không có sản phẩm nào</td></tr>";
    }
?>

        </tbody>
    </table>
</div>


<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


</body>
</html>


<?php
// Đóng kết nối cơ sở dữ liệu
$conn->close();
?>



