<?php
include 'connect.php';

// Truy vấn để lấy danh mục từ cơ sở dữ liệu
$sql = "SELECT maloai as id, tenloai as name FROM loaisp";
$categories = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm sản phẩm</title>
    <!-- Thêm Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Thêm Cropper.js CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" />
    <style>
        .image-preview {
            position: relative;
            display: inline-block;
            margin-right: 10px;
            margin-bottom: 10px;
        }

        .image-preview img {
            width: 100px;
            height: 100px;
            object-fit: cover;
        }

        .image-preview .delete-icon {
            position: absolute;
            top: 0;
            right: 0;
            background-color: rgba(255, 0, 0, 0.7);
            color: white;
            border: none;
            padding: 5px;
            cursor: pointer;
            border-radius: 50%;
        }

        .image-preview .image-name {
            text-align: center;
            font-size: 12px;
            margin-top: 5px;
        }

        #previewContainer {
            width: 100%;
            margin: 20px 0;
        }

        #image-preview {
            width: 100%;
            max-width: 500px;
            margin: 20px 0;
        }

        /* Cropper.js Container */
        #cropperContainer {
            width: 100%;
            max-width: 500px;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Thêm sản phẩm mới</h2>

        <form action="upload_product.php" method="POST" enctype="multipart/form-data" class="p-4 border rounded shadow-sm bg-light">
            <div class="form-group">
                <label for="name">Tên sản phẩm:</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="price">Giá sản phẩm:</label>
                <input type="number" name="price" id="price" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="category">Chọn danh mục:</label>
                <select name="category" id="category" class="form-control" required>
                    <option value="">-- Chọn danh mục --</option>
                    <?php
                    if ($categories->num_rows > 0) {
                        while ($row = $categories->fetch_assoc()) {
                            echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="images">Chọn ảnh sản phẩm:</label>
                <input type="file" name="images[]" id="images" class="form-control-file" multiple accept="image/*" onchange="previewImage()">
            </div>

            <!-- Chỗ hiển thị ảnh đã chọn -->
            <div id="imagePreviewContainer"></div>

            <!-- Chỗ hiển thị ảnh đã crop -->
            <div id="cropperContainer" style="display: none;">
                <img id="image-preview" src="" alt="Image Preview" />
                <div>
                    <button type="button" class="btn btn-success mt-2" onclick="getCroppedImage()">Lưu ảnh đã crop</button>
                </div>
            </div>

            <input type="hidden" name="cropped_image" id="cropped_image">

            <button type="submit" class="btn btn-primary btn-block">Thêm sản phẩm</button>
        </form>
    </div>

    <!-- Thêm Cropper.js và các script -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
    <script>
        let cropper;

        // Hàm load ảnh chọn từ file input
        function previewImage() {
            const files = document.getElementById('images').files;
            const previewContainer = document.getElementById('imagePreviewContainer');
            previewContainer.innerHTML = ""; // Xóa các ảnh đã hiển thị trước đó

            Array.from(files).forEach((file, index) => {
                const reader = new FileReader();

                reader.onload = function (e) {
                    const imgElement = document.getElementById('image-preview');
                    imgElement.src = e.target.result;
                    document.getElementById('cropperContainer').style.display = 'block'; // Hiển thị container cropper

                    // Khởi tạo cropper khi ảnh được load
                    if (cropper) {
                        cropper.destroy();
                    }
                    cropper = new Cropper(imgElement, {
                        aspectRatio: 1,
                        viewMode: 1,
                        dragMode: 'move',
                        crop(event) {
                            // Tùy chỉnh crop theo nhu cầu
                        }
                    });
                };
                reader.readAsDataURL(file);
            });
        }

        // Hàm lấy ảnh đã crop
        function getCroppedImage() {
            const croppedCanvas = cropper.getCroppedCanvas({
                width: 300, // Kích thước ảnh đã crop
                height: 300,
            });
            const croppedImage = croppedCanvas.toDataURL('image/jpeg'); // Chuyển ảnh đã crop thành base64
            document.getElementById('cropped_image').value = croppedImage; // Lưu ảnh đã crop vào hidden field
            alert("Ảnh đã được crop và lưu vào form.");
        }

        // Gọi hàm getCroppedImage trước khi submit form
        document.querySelector('form').addEventListener('submit', function (e) {
            e.preventDefault(); // Ngừng hành động submit mặc định
            getCroppedImage(); // Lấy ảnh đã crop
            this.submit(); // Gửi form sau khi lấy ảnh
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
