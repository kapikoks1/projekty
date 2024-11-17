<?php

$servername = "mysql55.mydevil.net";
$username = "m1357_tester";  
$password = "yLUJOOgAsZyF1BD9=0*b:WAF4CWfyJ";      
$dbname = "m1357_bazaTestowa";

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


function fetchBooks($conn) {
    
    $sql = "SELECT b.id, b.title, b.author, b.genre, b.year, b.rating, 
                   IFNULL(AVG(r.rating), 0) AS average_rating
            FROM books b
            LEFT JOIN reviews r ON b.id = r.book_id
            GROUP BY b.id";
    $result = $conn->query($sql);
    
    $books = array();
    while ($row = $result->fetch_assoc()) {
        $books[] = $row;
    }

    return $books; 
}


$books = fetchBooks($conn);


$conn->close();


echo json_encode($books);
?>
