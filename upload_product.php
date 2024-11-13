<?php
// Kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kiemtra2";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $category_id = $_POST['category'];
    $uploadDirectory = "uploads/";

    if (!is_dir($uploadDirectory)) {
        mkdir($uploadDirectory, 0777, true);
    }

    $uploadedFiles = [];
    $errorMessage = "";
    $maxFileSize = 2 * 1024 * 1024; // Giới hạn tệp 2MB

    // Các định dạng ảnh cho phép
    $allowedExtensions = ['jpg', 'png', 'gif'];

    if (!empty($_FILES['images']['name'][0])) {
        foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {
            $fileName = basename($_FILES['images']['name'][$key]);
            $targetFilePath = $uploadDirectory . $fileName;
            $fileSize = $_FILES['images']['size'][$key];

            // Kiểm tra kích thước tệp
            if ($fileSize > $maxFileSize) {
                $errorMessage = "Kích thước ảnh tối đa là 2MB.";
                break;
            }

            // Kiểm tra định dạng tệp
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            if (!in_array($fileExtension, $allowedExtensions)) {
                $errorMessage = "Chỉ chấp nhận định dạng ảnh .jpg, .png, .gif.";
                break;
            }

            // Nén và resize ảnh trước khi lưu
            if ($fileExtension == 'gif') {
                resizeGifImageGD($tmpName, $targetFilePath, 400, 400);
            } else {
                compressAndResizeImage($tmpName, $targetFilePath, 400, 400, $fileExtension);
            }

            // Thêm file vào mảng nếu nén và resize thành công
            if (file_exists($targetFilePath)) {
                $uploadedFiles[] = $targetFilePath;
            } else {
                $errorMessage = "Lỗi khi tải ảnh lên.";
                break;
            }
        }
    }

    // Kiểm tra nếu có lỗi
    if ($errorMessage) {
        echo $errorMessage;
        exit; // Dừng thực thi nếu có lỗi
    }

    // Mã hóa mảng ảnh thành chuỗi JSON để lưu vào cột "anh"
    $imagesJson = json_encode($uploadedFiles);

    // Chuẩn bị câu truy vấn SQL
    $sql = "INSERT INTO sanpham (tensp, dongia, maloai, anh) VALUES (?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        // Thực hiện bind_param với đúng số lượng và kiểu dữ liệu
        $stmt->bind_param("siss", $name, $price, $category_id, $imagesJson);

        if ($stmt->execute()) {
            echo "Thêm sản phẩm thành công!";
            header("Location: index.php");
        } else {
            echo "Lỗi khi thực thi câu truy vấn: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Lỗi khi chuẩn bị câu truy vấn SQL: " . $conn->error;
    }
}

$conn->close();


// Hàm nén và resize ảnh JPEG và PNG
function compressAndResizeImage($source, $destination, $width, $height, $extension) {
    $image = null;
    if ($extension == 'jpg' || $extension == 'jpeg') {
        $image = imagecreatefromjpeg($source);
    } elseif ($extension == 'png') {
        $image = imagecreatefrompng($source);
    }

    if ($image) {
        $newImage = imagescale($image, $width, $height);
        if ($extension == 'jpg' || $extension == 'jpeg') {
            imagejpeg($newImage, $destination, 75); // Chất lượng 75% cho JPEG
        } elseif ($extension == 'png') {
            imagepng($newImage, $destination, 6); // Chất lượng trung bình cho PNG
        }
        imagedestroy($image);
        imagedestroy($newImage);
    }
}

function resizeGifImageGD($source, $destination, $width, $height) {
    // Mở ảnh GIF động
    $image = imagecreatefromgif($source);
    
    // Kiểm tra nếu ảnh được mở thành công
    if ($image !== false) {
        // Tạo một bản sao ảnh mới với kích thước đã thay đổi
        $newImage = imagecreatetruecolor($width, $height);
        
        // Tạo ảnh GIF động mới với các khung hình đã được nén và resize
        $frames = [];
        $delays = [];

        // Lấy các khung hình của ảnh GIF
        $numFrames = imagegif($image, $source);
        for ($i = 0; $i < $numFrames; $i++) {
            // Đặt các khung hình ảnh vào một mảng
            $frames[] = $image;
            $delays[] = 100; // Điều chỉnh delay của mỗi khung hình
        }

        // Lưu ảnh GIF mới vào thư mục đích
        imagegif($newImage, $destination);

        // Giải phóng bộ nhớ
        imagedestroy($image);
        imagedestroy($newImage);
    }
}


?>
