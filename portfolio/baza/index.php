<?php
// Połączenie z bazą danych
$servername = "mysql55.mydevil.net";
$username = "m1357_tester";  
$password = "yLUJOOgAsZyF1BD9=0*b:WAF4CWfyJ";      
$dbname = "m1357_bazaTestowa";

// Tworzenie połączenia
$conn = new mysqli($servername, $username, $password, $dbname);

// Sprawdzenie połączenia
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Funkcja pobierająca książki z bazy danych
function fetchBooks($conn) {
    // Zapytanie SQL pobierające książki z bazy danych oraz obliczające średnią ocenę
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

    return $books; // Zwracanie książek w postaci tablicy
}

// Pobieramy książki z bazy danych
$books = fetchBooks($conn);

// Zamykanie połączenia z bazą danych
$conn->close();

// Zwracamy książki w formacie JSON
echo json_encode($books);
?>
