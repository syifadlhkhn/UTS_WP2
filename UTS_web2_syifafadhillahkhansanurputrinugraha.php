
<?php
// Daftar buku dan statusnya (true jika tersedia, false jika sudah dipinjam)
$books = [
    ['title' => 'Harry Potter', 'author' => 'J.K. Rowling', 'year' => 1997, 'available' => true, 'image' => 'img/Harry-Potter.jpg'],
    ['title' => 'Lord of the Rings', 'author' => 'J.R.R. Tolkien', 'year' => 1954, 'available' => true, 'image' => 'img/lord.jpg'],
    ['title' => 'To Kill a Mockingbird', 'author' => 'Harper Lee', 'year' => 1960, 'available' => true, 'image' => 'img/kill.jpeg'],
    ['title' => 'See You In The Cosmos', 'author' => 'Jack Cheng', 'year' => 2017, 'available' => true, 'image' => 'img/see.jpg'],
    ['title' => 'Pride and Prejudice', 'author' => 'Jane Austen', 'year' => 1813, 'available' => true, 'image' => 'img/jane.jpg'],
    ['title' => 'The Black Swan', 'author' => 'Nassim Nicholas Taleb', 'year' => 2007, 'available' => true, 'image' => 'img/swan.jpg'],
    ['title' => 'Earth', 'author' => 'Tere Liye', 'year' => 2014, 'available' => true, 'image' => 'img/earth.jpg'],
];

// Fungsi untuk meminjam buku
function borrowBook($bookIndex) {
    global $books;
    if ($books[$bookIndex]['available']) {
        $books[$bookIndex]['available'] = false;
        return true;
    }
    return false;
}

// Fungsi untuk mengembalikan buku
function returnBook($bookIndex) {
    global $books;
    if (!$books[$bookIndex]['available']) {
        $books[$bookIndex]['available'] = true;
        return true;
    }
    return false;
}

// Fungsi untuk mencari buku berdasarkan judul
function searchBooks($keyword) {
    global $books;
    $filteredBooks = [];
    foreach ($books as $book) {
        if (stripos($book['title'], $keyword) !== false) {
            $filteredBooks[] = $book;
        }
    }
    return $filteredBooks;
}

// Pesan untuk menampilkan keberhasilan peminjaman atau pengembalian buku
$message = '';

// Memeriksa apakah ada kata kunci pencarian yang dikirimkan
if (isset($_GET['keyword'])) {
    $keyword = $_GET['keyword'];
    $books = searchBooks($keyword);
}

// Memeriksa apakah permintaan adalah POST dan menangani permintaan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['pinjam'])) {
        $bookIndex = $_POST['book'];
        if (borrowBook($bookIndex)) {
            $message = 'Buku ' . $books[$bookIndex]['title'] . ' berhasil dipinjam!';
        } else {
            $message = 'Buku ' . $books[$bookIndex]['title'] . ' tidak tersedia untuk dipinjam.';
        }
    } elseif (isset($_POST['kembalikan'])) {
        $bookIndex = $_POST['book'];
        returnBook($bookIndex);
        $message = 'Buku ' . $books[$bookIndex]['title'] . ' berhasil dikembalikan!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PERPUSTAKAAN SYIFA</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            border-collapse: collapse;
            width: 80%;
            margin: 20px auto;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        form {
            margin-top: 20px;
            text-align: center;
        }
        button {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
        }
        .book-image {
            width: 100px;
            height: auto;
        }
        .message {
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<h2>PERPUSTAKAAN SYIFA </h2>

<form method="get">
    <label for="keyword">Search:</label>
    <input type="text" id="keyword" name="keyword">
    <button type="submit">Search</button>
</form>

<div class="message"><?php echo $message; ?></div>

<table>
    <tr>
        <th>Cover</th>
        <th>Title</th>
        <th>Author</th>
        <th>Year</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
    <?php foreach ($books as $index => $book): ?>
    <tr>
        <td><img src="<?php echo $book['image']; ?>" alt="<?php echo $book['title']; ?>" class="book-image"></td>
        <td><?php echo $book['title']; ?></td>
        <td><?php echo $book['author']; ?></td>
        <td><?php echo $book['year']; ?></td>
        <td><?php echo $book['available'] ? 'Available' : 'Not Available'; ?></td>
        <td>
            <form method="post">
                <input type="hidden" name="book" value="<?php echo $index; ?>">
                <?php if ($book['available']): ?>
                    <button type="submit" name="pinjam">Pinjam</button>
                <?php else: ?>
                    <button type="submit" name="kembalikan">Kembalikan</button>
                <?php endif; ?>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
