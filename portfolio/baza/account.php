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


$userId = $_SESSION['user_id'];
$sql = "SELECT username FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();


$booksSql = "SELECT id, title, author, genre, year, rating FROM books";
$booksResult = $conn->query($booksSql);
$books = [];


$reviewsSql = "SELECT book_id, rating, review FROM reviews WHERE user_id = ?";
$reviewsStmt = $conn->prepare($reviewsSql);
$reviewsStmt->bind_param("i", $userId);
$reviewsStmt->execute();
$reviewsResult = $reviewsStmt->get_result();
$userReviews = [];

while ($review = $reviewsResult->fetch_assoc()) {
    $userReviews[$review['book_id']] = $review;
}


while ($book = $booksResult->fetch_assoc()) {
    $books[] = $book;
}


$conn->close();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moje Konto</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <h1>Witaj, <?php echo htmlspecialchars($user['username']); ?>!</h1>
    <div class="auth-container">
        <a href="logout.php">Wyloguj się</a> |
        <a href="reviews.php">Recenzje</a>
    </div>
</header>

<main>
    <section class="account-info">
        <h2>Twoje konto</h2>
        <p>Witaj w swoim koncie. Możesz teraz przeglądać książki i dodawać oceny oraz recenzje.</p>
    </section>

    <section class="books">
        <h2>Lista Książek</h2>
        <div id="books-list">
            <?php if (count($books) > 0): ?>
                <?php foreach ($books as $book): ?>
                    <div class="book-item">
                        <h3><?php echo htmlspecialchars($book['title']); ?></h3>
                        <p>Autor: <?php echo htmlspecialchars($book['author']); ?></p>
                        <p>Gatunek: <?php echo htmlspecialchars($book['genre']); ?></p>
                        <p>Rok wydania: <?php echo htmlspecialchars($book['year']); ?></p>
                        <p class="rating">Ocena: <?php echo $book['rating'] ? htmlspecialchars($book['rating']) : 'Brak ocen'; ?></p>

                        <?php if (isset($userReviews[$book['id']])): ?>
                           
                            <p>Twoja ocena: <?php echo $userReviews[$book['id']]['rating']; ?>/10</p>
                            <p>Twoja recenzja: <?php echo htmlspecialchars($userReviews[$book['id']]['review']); ?></p>
                        <?php else: ?>
                            
                            <form action="rate_book.php" method="POST">
                                <label for="rating-<?php echo $book['id']; ?>">Twoja ocena (1-10):</label>
                                <input type="number" name="rating" id="rating-<?php echo $book['id']; ?>" min="1" max="10" required>
                                <label for="review-<?php echo $book['id']; ?>">Twoja recenzja (opcjonalnie):</label>
                                <textarea name="review" id="review-<?php echo $book['id']; ?>" rows="4" placeholder="Dodaj swoją recenzję (opcjonalnie)"></textarea>
                                <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
                                <button type="submit">Dodaj ocenę i recenzję</button>
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Brak książek w katalogu.</p>
            <?php endif; ?>
        </div>
    </section>
</main>

<footer>
    <p>© 2024 Kacper Król. Wszystkie prawa zastrzeżone.</p>
</footer>


</body>
</html>
