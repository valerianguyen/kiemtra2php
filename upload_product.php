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
    $quantity = $_POST['quantity'];
    $description = $_POST['description'];
    $uploadDirectory = "uploads/";


    if (!is_dir($uploadDirectory)) {
        mkdir($uploadDirectory, 0777, true);
    }


    $uploadedFiles = [];
    $errorMessage = "";
    $maxFileSize = 2 * 1024 * 1024; // Giới hạn tệp 2MB


    // Các định dạng ảnh cho phép
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];


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
                $errorMessage = "Chỉ chấp nhận định dạng ảnh .jpg, .jpeg, .png, .gif.";
                break;
            }


            // Nén và resize ảnh trước khi lưu
            if ($fileExtension == 'gif') {
                resizeGifImage($tmpName, $targetFilePath, 400, 400);
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
    $sql = "INSERT INTO sanpham (tensp, dongia, maloai, soluong, mota, anh) VALUES (?, ?, ?, ?, ?, ?)";


    if ($stmt = $conn->prepare($sql)) {
        // Thực hiện bind_param với đúng số lượng và kiểu dữ liệu
        $stmt->bind_param("sissss", $name, $price, $category_id, $quantity, $description, $imagesJson);


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
        // Resize ảnh
        $newImage = imagescale($image, $width, $height);
       
        // Lưu ảnh với chất lượng nén
        if ($extension == 'jpg' || $extension == 'jpeg') {
            imagejpeg($newImage, $destination, 90); // Chất lượng 90% cho JPEG
        } elseif ($extension == 'png') {
            imagepng($newImage, $destination, 9); // Chất lượng tốt hơn cho PNG
        }


        // Giải phóng bộ nhớ
        imagedestroy($image);
        imagedestroy($newImage);
    }
}


// Hàm resize và lưu ảnh GIF động
function resizeGifImage($source, $destination, $width, $height) {
    // Mở ảnh GIF động
    $gif = imagecreatefromgif($source);
   
    if ($gif === false) {
        return false;
    }

    // Tạo một ảnh GIF động mới với các khung hình đã được resize
    $newGif = imagecreatetruecolor($width, $height);
    $totalFrames = 5; // Giả sử GIF có 5 khung hình

    for ($frameIndex = 0; $frameIndex < $totalFrames; $frameIndex++) {
        // Chọn từng khung hình và resize
        imagecopyresampled($newGif, $gif, 0, 0, 0, 0, $width, $height, imagesx($gif), imagesy($gif));
    }

    // Lưu ảnh GIF động vào đích
    imagegif($newGif, $destination);


    // Giải phóng bộ nhớ
    imagedestroy($gif);
    imagedestroy($newGif);

    return true;
}
?>
