document.addEventListener('DOMContentLoaded', function() {
    fetchBooks();
});

function fetchBooks() {
    fetch('index.php?action=fetch_books')
        .then(response => response.json())
        .then(data => {
            const booksList = document.getElementById('books-list');
            if (data.length === 0) {
                booksList.innerHTML = "<p>Brak książek w katalogu.</p>";
            } else {
                data.forEach(book => {
                    const bookItem = document.createElement('div');
                    bookItem.classList.add('book-item');

                    // HTML do wyświetlania książki
                    bookItem.innerHTML = `
                        <h3>${book.title}</h3>
                        <p>Autor: ${book.author}</p>
                        <p>Gatunek: ${book.genre}</p>
                        <p>Rok wydania: ${book.year}</p>
                        <p class="rating">Ocena recenzentów: ${book.rating ? book.rating : 'Brak ocen'}</p>
                        <p class="average-rating">Średnia ocen użytkowników: ${book.average_rating ? book.average_rating : 'Brak ocen'}</p>
                    `;

                    // Sprawdzamy, czy użytkownik jest zalogowany
                    const loggedIn = isLoggedIn(); // Ta funkcja sprawdza, czy użytkownik jest zalogowany

                    // Jeżeli użytkownik jest zalogowany, dodaj formularz do oceny książki
                    if (loggedIn) {
                        const rateForm = document.createElement('form');
                        rateForm.setAttribute('action', 'rate_book.php');
                        rateForm.setAttribute('method', 'POST');

                        const ratingInput = document.createElement('input');
                        ratingInput.setAttribute('type', 'number');
                        ratingInput.setAttribute('name', 'rating');
                        ratingInput.setAttribute('min', '1');
                        ratingInput.setAttribute('max', '10');
                        ratingInput.setAttribute('required', true);

                        const bookIdInput = document.createElement('input');
                        bookIdInput.setAttribute('type', 'hidden');
                        bookIdInput.setAttribute('name', 'book_id');
                        bookIdInput.setAttribute('value', book.id);

                        const submitButton = document.createElement('button');
                        submitButton.textContent = 'Dodaj ocenę';

                        rateForm.appendChild(ratingInput);
                        rateForm.appendChild(bookIdInput);
                        rateForm.appendChild(submitButton);

                        // Dodajemy formularz oceny książki
                        bookItem.appendChild(rateForm);
                    } else {
                        const loginMessage = document.createElement('p');
                        loginMessage.textContent = 'Zaloguj się, aby dodać ocenę.';
                        bookItem.appendChild(loginMessage);
                    }

                    booksList.appendChild(bookItem);
                });
            }
        })
        .catch(error => {
            console.error('Error fetching books:', error);
        });
}

// Funkcja sprawdzająca, czy użytkownik jest zalogowany (przykład z sesji, może wymagać dostosowania)
function isLoggedIn() {
    // To jest tylko przykładowa implementacja. W rzeczywistości możesz sprawdzać zalogowanie
    // np. poprzez ciasteczka, sesje lub tokeny w zależności od implementacji.
    return sessionStorage.getItem('userLoggedIn') === 'true'; // Przykład z sessionStorage
}
