<?php
include 'connect.php';
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
    </style>
</head>


<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Thêm sản phẩm mới</h2>


        <form action="upload_product.php" method="POST" enctype="multipart/form-data"
            class="p-4 border rounded shadow-sm bg-light">


            <div class="form-group">
                <label for="name">Tên sản phẩm:</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="quantity">Số lượng:</label>
                <input type="number" name="quantity" id="quantity" class="form-control" required>
            </div>


            <div class="form-group">
                <label for="price">Giá sản phẩm:</label>
                <input type="number" name="price" id="price" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="description">Mô tả:</label>
                <textarea name="description" id="description" class="form-control" rows="3" required></textarea>
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
                <input type="file" name="images[]" id="images" class="form-control-file" multiple accept="image/*" onchange="previewImages()">


            </div>


            <!-- Chỗ hiển thị ảnh đã chọn -->
            <div id="imagePreviewContainer"></div>

            <button type="submit" class="btn btn-primary btn-block">Thêm sản phẩm</button>
        </form>
    </div>


    <!-- Bootstrap JS, Popper.js và jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        let filesArray = []; // Mảng lưu trữ các tệp ảnh đã chọn


        // Hàm hiển thị ảnh đã chọn và tạo nút xóa
        function previewImages() {
            const files = document.getElementById('images').files;
            const previewContainer = document.getElementById('imagePreviewContainer');
            previewContainer.innerHTML = ""; // Xóa các ảnh đã hiển thị trước đó


            Array.from(files).forEach((file, index) => {
                const reader = new FileReader();


                reader.onload = function (e) {
                    // Tạo thẻ div cho mỗi ảnh
                    const div = document.createElement('div');
                    div.classList.add('image-preview');
                    div.innerHTML = `
                        <img src="${e.target.result}" alt="Image ${index}">
                        <button type="button" class="delete-icon" onclick="removeImage(${index}, this)">X</button>
                    `;
                    previewContainer.appendChild(div);
                    // Thêm ảnh vào mảng filesArray
                    filesArray.push(file);
                };


                reader.readAsDataURL(file);
            });
        }


        // Hàm xóa ảnh khi nhấn vào nút X
        function removeImage(index, button) {
            const previewContainer = document.getElementById('imagePreviewContainer');
            previewContainer.removeChild(button.parentElement);
            // Xóa ảnh khỏi mảng filesArray
            filesArray.splice(index, 1);
            // Cập nhật lại phần input file để loại bỏ ảnh đã xóa
            updateFileInput();
        }


        function updateFileInput() {
            const fileInput = document.getElementById('images');
            const dataTransfer = new DataTransfer();
            filesArray.forEach(file => {
                dataTransfer.items.add(file);
            });
            fileInput.files = dataTransfer.files;
        }


    </script>
</body>


</html>

