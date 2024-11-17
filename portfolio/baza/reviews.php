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

$reviewsSql = "SELECT users.username AS reviewer, books.title, books.author, books.genre, books.year, reviews.rating, reviews.review 
               FROM reviews
               JOIN books ON reviews.book_id = books.id
               JOIN users ON reviews.user_id = users.id"; 

$reviewsStmt = $conn->prepare($reviewsSql);
$reviewsStmt->execute();
$reviewsResult = $reviewsStmt->get_result();
$allReviews = [];

while ($review = $reviewsResult->fetch_assoc()) {
    $allReviews[] = $review;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recenzje wszystkich użytkowników</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <h1>Witaj, <?php echo htmlspecialchars($user['username']); ?>!</h1>
    <div class="auth-container">
        <a href="logout.php">Wyloguj się</a> |
        <a href="account.php">Moje konto</a>
    </div>
</header>

<main>
    <section class="reviews">
        <h2>Recenzje wystawione przez użytkowników</h2>
        <div id="reviews-list">
            <?php if (count($allReviews) > 0): ?>
                <?php foreach ($allReviews as $review): ?>
                    <div class="review-item">
                        <h3><?php echo htmlspecialchars($review['title']); ?> (<?php echo htmlspecialchars($review['year']); ?>)</h3>
                        <p>Autor: <?php echo htmlspecialchars($review['author']); ?></p>
                        <p>Gatunek: <?php echo htmlspecialchars($review['genre']); ?></p>
                        <p>Ocena: <?php echo $review['rating']; ?>/10</p>
                        <p>Recenzja: <?php echo htmlspecialchars($review['review']); ?></p>
                        <p>Recenzja wystawiona przez: <?php echo htmlspecialchars($review['reviewer']); ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Nie ma jeszcze żadnych recenzji.</p>
            <?php endif; ?>
        </div>
    </section>
</main>

<footer>
    <p>© 2024 Kacper Król. Wszystkie prawa zastrzeżone.</p>
</footer>

</body>
</html>
