<?php
// Database connection
$servername = "localhost";
$username = "root"; // change if different
$password = "";     // change if different
$dbname = "megapod";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get data from form
$song_id = $_POST['song_id'];
$user_name = $_POST['user_name'];
$review = $_POST['review'];
$rating = $_POST['rating'];

// Insert review into database
$sql = "INSERT INTO reviews (song_id, user_name, review, rating, created_at) 
        VALUES ('$song_id', '$user_name', '$review', '$rating', NOW())";

if ($conn->query($sql) === TRUE) {
    // Redirect back to song_details.php
    header("Location: song_details.php?id=" . $song_id . "&success=1");
    exit();
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
