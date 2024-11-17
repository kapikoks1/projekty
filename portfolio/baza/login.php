<?php
session_start();


$servername = "mysql55.mydevil.net";
$username = "m1357_tester";  
$password = "yLUJOOgAsZyF1BD9=0*b:WAF4CWfyJ";      
$dbname = "m1357_bazaTestowa";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   
    $email = $_POST['email'];  
    $pass = $_POST['password']; 

    
    $sql = "SELECT * FROM users WHERE email = ?";  
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);  
    $stmt->execute();
    $result = $stmt->get_result();
    
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
       
        if (password_verify($pass, $row['password'])) {
            
            $_SESSION['user_id'] = $row['id'];       
            $_SESSION['user_email'] = $row['email']; 

            
            header('Location: account.php'); 
            exit();  
        } else {
          
            header('Location: login.html?error=1'); 
            exit();
        }
    } else {
        
        header('Location: login.html?error=1');  
        exit();
    }
}


$conn->close();
?>
