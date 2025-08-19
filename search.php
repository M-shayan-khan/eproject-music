<link rel="stylesheet" href="css/font-awesome.min.css" type="text/css">

<?php
// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "megapod");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Search logic
$keyword = isset($_GET['q']) ? trim($_GET['q']) : "";
$songs = [];

if (!empty($keyword)) {
    $stmt = $conn->prepare("
        SELECT * FROM songs 
        WHERE name LIKE ? 
           OR artist LIKE ? 
           OR album LIKE ? 
           OR year LIKE ?
    ");
    $likeKeyword = "%$keyword%";
    $stmt->bind_param("ssss", $likeKeyword, $likeKeyword, $likeKeyword, $likeKeyword);
    $stmt->execute();
    $result = $stmt->get_result();
    $songs = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Search Results</title>
    <link rel="stylesheet" href="css/style.css"> <!-- Apna main CSS file -->
    <style>
        body {
            background-color: black;
            margin: 0;
            padding: 0;
        }

        /* Header ke niche space dene ke liye */
        .main-content {
            padding-top: 20px;
            /* Adjust based on header height */
            min-height: 100vh;
        }

        .search-container {
            max-width: 900px;
            margin: auto;
            padding: 20px;
        }

        .search-container h2 {
            color: #ffffffff;
            padding-bottom: 35px;
        }

        .song-item {
            background: #6763fd;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .song-item h3 {
            margin-top: 0;
            color: #ffffffff;
        }

        .song-item p {
            margin: 5px 0;
            color: #000000ff;
        }

        .song-item a {
            display: inline-block;
            margin-top: 8px;
            color: #252525ff;
            text-decoration: none;
        }

        .song-item a:hover {
            text-decoration: underline;
        }

        .no-results {
            text-align: center;
            color: #ffffffff;
            padding: 20px;
            font-size: 25
        }

        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .main-content {
                padding-top: 100px;
            }

            .search-container {
                padding: 10px;
            }
        }
    </style>
</head>

<body>

    <!-- Mini Header -->
    <header
        style="background-color: black; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid rgba(255, 255, 255, 0.3);">
        <!-- Logo -->
        <div style="display: flex; align-items: center;">
            <a href="index.php"><img src="./img/logo.png" alt="MEGAPOD" style="height: 35px; margin-left: 15px;"></a>
        </div>

        <!-- Search Form -->
        <div class="col-lg-4 d-flex justify-content-end align-items-center"
            style="margin-top: 15px; margin-right: 15px;">
            <div class="header__right__search">
                <form action="search.php" method="GET">
                    <input type="text" name="q" placeholder="Search and hit enter..." required>
                    <button type="submit"><i class="fa fa-search"></i></button>
                </form>
            </div>
    </header>


    <div class="main-content">
        <div class="search-container">
            <h2>Search Results for: "<?php echo htmlspecialchars($keyword); ?>"</h2>

            <?php if (!empty($songs)): ?>
                <?php foreach ($songs as $song): ?>
                    <div class="song-item" style="position: relative;">
                        <!-- Play Button -->
                        <button class="play-btn" data-src="<?php echo htmlspecialchars($song['file_path']); ?>"
                            style="position: absolute; top: 10px; right: 10px; background: #fff; border: none; padding: 8px 12px; border-radius: 50%; cursor: pointer;">
                            ▶
                        </button>
                        <h3><?php echo htmlspecialchars($song['name']); ?></h3>
                        <p>Artist: <?php echo htmlspecialchars($song['artist']); ?></p>
                        <p>Album: <?php echo htmlspecialchars($song['album']); ?></p>
                        <p>Year: <?php echo htmlspecialchars($song['year']); ?></p>
                        <a href="song_details.php?id=<?php echo $song['id']; ?>">View Details</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-results">No songs found.</p>
            <?php endif; ?>

            <!-- Hidden audio element -->
            <audio id="audio-player" style="display: none;"></audio>

            <script>
                let audioPlayer = document.getElementById("audio-player");
                let currentBtn = null;

                document.querySelectorAll(".play-btn").forEach(btn => {
                    btn.addEventListener("click", function () {
                        let songSrc = this.getAttribute("data-src");

                        if (audioPlayer.src !== songSrc) {
                            audioPlayer.src = songSrc;
                            audioPlayer.play();
                            if (currentBtn) currentBtn.textContent = "▶";
                            this.textContent = "⏸";
                            currentBtn = this;
                        } else {
                            if (audioPlayer.paused) {
                                audioPlayer.play();
                                this.textContent = "⏸";
                            } else {
                                audioPlayer.pause();
                                this.textContent = "▶";
                            }
                        }
                    });
                });

                audioPlayer.addEventListener("ended", function () {
                    if (currentBtn) currentBtn.textContent = "▶";
                });
            </script>

        </div>
    </div>

</body>

</html>