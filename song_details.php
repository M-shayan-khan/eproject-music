<!-- Css Styles -->
<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
<link rel="stylesheet" href="css/font-awesome.min.css" type="text/css">
<link rel="stylesheet" href="css/elegant-icons.css" type="text/css">
<link rel="stylesheet" href="css/magnific-popup.css" type="text/css">
<link rel="stylesheet" href="css/owl.carousel.min.css" type="text/css">
<link rel="stylesheet" href="css/slicknav.min.css" type="text/css">
<link rel="stylesheet" href="./css/style.css?v=2">

<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "megapod");

// Get song id from URL
$song_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Fetch song details
$song = null;
if ($song_id > 0) {
    $sql = "SELECT * FROM songs WHERE id = $song_id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $song = $result->fetch_assoc();
    }
}

// Fetch reviews
$reviews = [];
if ($song_id > 0) {
    $sql_reviews = "SELECT * FROM reviews WHERE song_id = $song_id ORDER BY created_at DESC";
    $reviews = $conn->query($sql_reviews);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Song Details</title>

    <!-- Css Styles -->
    <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="css/style.css?v=3">

    <style>
        body {
            background-color: #000;
            color: #fff;
            font-family: Arial, sans-serif;
        }

        .song-header h1 {
            color: #6763fd;
            font-size: 2.5rem;
            font-weight: bold;
        }

        .song-player {
            background: #111;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(103, 99, 253, 0.4);
            margin-top: 20px;
        }

        .song-player h2 {
            color: #6763fd;
            font-size: 1.5rem;
        }

        .song-left img {
            width: 100%;
            border-radius: 15px;
            box-shadow: 0 0 20px #6763fd;
        }

        audio {
            width: 100%;
            margin-top: 15px;
        }

        .review-section {
            margin: 50px auto;
            background: #111;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 15px #6763fd;
        }

        .review-section h2 {
            color: #6763fd;
            margin-bottom: 20px;
        }

        .review-form input,
        .review-form textarea,
        .review-form button {
            width: 100%;
            margin: 10px 0;
            padding: 12px;
            border-radius: 8px;
            border: none;
            font-size: 14px;
        }

        .review-form input,
        .review-form textarea {
            background: #222;
            color: #fff;
        }

        .review-form button {
            background: #6763fd;
            color: #fff;
            font-weight: bold;
            transition: 0.3s;
        }

        .review-form button:hover {
            background: #4e48d5;
        }

        .review {
            border-bottom: 1px solid #333;
            padding: 10px 0;
        }

        .review strong {
            color: #6763fd;
        }

        @media (max-width: 768px) {
            .song-header h1 {
                font-size: 1.8rem;
            }

            .song-player h2 {
                font-size: 1.2rem;
            }
        }
    </style>
</head>

<body>

    <!-- Header -->
    <header class="d-flex justify-content-between align-items-center p-3 border-bottom border-secondary">
        <a href="index.php"><img src="./img/logo.png" alt="MEGAPOD" style="height: 35px;"></a>
        <div class="header__right__search">
            <form action="search.php" method="GET">
                <input type="text" name="q" placeholder="Search and hit enter..." required>
                <button type="submit"><i class="fa fa-search"></i></button>
            </form>
        </div>
    </header>

    <main class="container py-5">
        <?php if ($song): ?>
            <div class="row g-4 align-items-center">
                <!-- Left: Song Image -->
                <div class="col-lg-5 col-md-6 col-12 song-left">
                    <img src="<?php echo $song['image_path']; ?>" alt="Song Image">
                </div>

                <!-- Right: Song Info -->
                <div class="col-lg-7 col-md-6 col-12 song-right">
                    <div class="song-header">
                        <h1><?php echo $song['name']; ?></h1>
                        <p><strong>Artist:</strong> <?php echo $song['artist']; ?></p>
                        <p><strong>Album:</strong> <?php echo $song['album']; ?></p>
                        <p><strong>Year:</strong> <?php echo $song['year']; ?></p>
                    </div>

                    <div class="song-player">
                        <h2><?php echo $song['name']; ?> - <?php echo $song['artist']; ?></h2>
                        <audio controls>
                            <source src="<?php echo $song['file_path']; ?>" type="audio/mpeg">
                            Your browser does not support the audio element.
                        </audio>
                    </div>
                </div>
            </div>

            <!-- Review Section -->
            <div class="review-section mt-5">
                <h2>Leave a Review</h2>
                <form class="review-form" action="submit_review.php" method="POST">
                    <input type="hidden" name="song_id" value="<?php echo $song['id']; ?>">
                    <input type="text" name="user_name" placeholder="Your Name" required>
                    <input type="number" name="rating" min="1" max="5" placeholder="Rating (1-5)" required>
                    <textarea name="review" rows="4" placeholder="Write your review..." required></textarea>
                    <button type="submit">Submit Review</button>
                </form>

                <h2 class="mt-4">Reviews</h2>
                <?php if ($reviews->num_rows > 0): ?>
                    <?php while ($rev = $reviews->fetch_assoc()): ?>
                        <div class="review">
                            <strong><?php echo htmlspecialchars($rev['user_name']); ?></strong>
                            <p><?php echo htmlspecialchars($rev['review']); ?></p>
                            <small><?php echo $rev['created_at']; ?></small>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No reviews yet. Be the first to review!</p>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <p class="text-center text-primary mt-5">Song not found!</p>
        <?php endif; ?>
    </main>

    <!-- Footer -->
    <footer class="footer text-center text-white py-4 mt-5" style="background:#111;">
        <div class="container">
            <a href="#"><img src="img/logo.png" alt="logo" class="mb-3" style="height: 35px;"></a>
            <p class="mb-0">©
                <script>document.write(new Date().getFullYear())</script> MEGAPOD | Made with ❤️ by
                <a href="https://colorlib.com" class="text-decoration-none text-primary">Muhammed Shayan</a>
            </p>
            <div class="mt-3">
                <a href="#" class="text-white me-3"><i class="fa fa-facebook"></i></a>
                <a href="#" class="text-white me-3"><i class="fa fa-twitter"></i></a>
                <a href="#" class="text-white me-3"><i class="fa fa-instagram"></i></a>
                <a href="#" class="text-white"><i class="fa fa-youtube-play"></i></a>
            </div>
        </div>
    </footer>

</body>

</html>