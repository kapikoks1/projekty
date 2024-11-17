<?php

$servername = "mysql55.mydevil.net";
$username = "m1357_tester";  
$password = "yLUJOOgAsZyF1BD9=0*b:WAF4CWfyJ";      
$dbname = "m1357_bazaTestowa";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


session_start();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    
    if ($password != $confirm_password) {
        echo "Hasła nie są zgodne!";
        exit();
    }

    
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Użytkownik z takim e-mailem już istnieje!";
        exit();
    }

    
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $hashed_password);

    if ($stmt->execute()) {
        
        header("Location: login.html");
        exit();
    } else {
        echo "Błąd podczas rejestracji: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>
