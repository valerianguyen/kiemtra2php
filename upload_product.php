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
            $fileSize = $_FILES['images']['size'][$key]; // Kích thước tệp hiện tại

            // Kiểm tra kích thước tệp
            if ($fileSize > $maxFileSize) {
                $errorMessage = "Kích thước ảnh tối đa là 2MB.";
                break; // Dừng lại nếu tệp quá lớn
            }

            if (move_uploaded_file($tmpName, $targetFilePath)) {
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
    $stmt = $conn->prepare($sql);

    // Kiểm tra nếu câu truy vấn không thành công
    if ($stmt === false) {
        die("Lỗi khi chuẩn bị câu truy vấn SQL: " . $conn->error);
    }

    // Thực hiện bind_param với đúng số lượng và kiểu dữ liệu
// "ssiss" - 5 tham số: masp (string), tensp (string), dongia (integer), maloai (string), anh (string)
    $stmt->bind_param("siss",  $name, $price, $category_id, $imagesJson);

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