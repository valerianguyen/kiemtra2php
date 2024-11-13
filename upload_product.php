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
    $maxFileSize = 2 * 1024 * 1024;

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

            // Kiểm tra định dạng ảnh và tạo nguồn ảnh tương ứng
            $imageType = exif_imagetype($tmpName);
            if ($imageType == IMAGETYPE_JPEG) {
                $sourceImage = imagecreatefromjpeg($tmpName);
            } elseif ($imageType == IMAGETYPE_PNG) {
                $sourceImage = imagecreatefrompng($tmpName);
            } elseif ($imageType == IMAGETYPE_GIF) {
                $sourceImage = imagecreatefromgif($tmpName);
            } else {
                $errorMessage = "Định dạng ảnh không hỗ trợ.";
                break;
            }

            // Lấy kích thước ảnh gốc và thiết lập kích thước mới
            list($width, $height) = getimagesize($tmpName);
            $newWidth = 300;
            $newHeight = ($height / $width) * $newWidth;

            // Tạo ảnh mới với kích thước đã thay đổi
            $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
            imagecopyresampled($resizedImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

            // Lưu ảnh đã resize
            if ($imageType == IMAGETYPE_JPEG) {
                imagejpeg($resizedImage, $targetFilePath);
            } elseif ($imageType == IMAGETYPE_PNG) {
                imagepng($resizedImage, $targetFilePath);
            } elseif ($imageType == IMAGETYPE_GIF) {
                imagegif($resizedImage, $targetFilePath);
            }

            $uploadedFiles[] = $targetFilePath;
            imagedestroy($resizedImage);
            imagedestroy($sourceImage);
        }
    }

    if ($errorMessage) {
        echo $errorMessage;
        exit;
    }

    // Lưu thông tin ảnh vào cơ sở dữ liệu
    $imagesJson = json_encode($uploadedFiles);
    $sql = "INSERT INTO sanpham (tensp, dongia, maloai, anh) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Lỗi khi chuẩn bị câu truy vấn SQL: " . $conn->error);
    }

    $stmt->bind_param("siss", $name, $price, $category_id, $imagesJson);

    if ($stmt->execute()) {
        echo "Thêm sản phẩm thành công!";
        header("Location: index.php");
    } else {
        echo "Lỗi khi thực thi câu truy vấn: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
