<?php
session_start();


if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit();
}


$servername = "mysql55.mydevil.net";
$username = "m1357_tester";  
$password = "yLUJOOgAsZyF1BD9=0*b:WAF4CWfyJ";      
$dbname = "m1357_bazaTestowa";

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bookId = $_POST['book_id'];  
    $rating = $_POST['rating'];   
    $review = isset($_POST['review']) ? $_POST['review'] : '';  

    $userId = $_SESSION['user_id'];  

    
    $checkSql = "SELECT * FROM reviews WHERE book_id = ? AND user_id = ?";
    $stmt = $conn->prepare($checkSql);
    $stmt->bind_param("ii", $bookId, $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        
        $updateSql = "UPDATE reviews SET rating = ?, review = ?, created_at = NOW() WHERE book_id = ? AND user_id = ?";
        $stmt = $conn->prepare($updateSql);
        $stmt->bind_param("isii", $rating, $review, $bookId, $userId);
        $stmt->execute();
    } else {
        
        $insertSql = "INSERT INTO reviews (book_id, user_id, review, rating, created_at) VALUES (?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($insertSql);
        $stmt->bind_param("iisi", $bookId, $userId, $review, $rating);
        $stmt->execute();
    }

    
    header('Location: account.php');
    exit();
}

$conn->close();
?>
