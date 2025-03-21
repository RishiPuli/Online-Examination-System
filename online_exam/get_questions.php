<?php
include 'db.php';

$query = "SELECT * FROM questions";
$result = mysqli_query($conn, $query);

$questions = [];
while ($row = mysqli_fetch_assoc($result)) {
    $questions[] = $row;
}

echo json_encode($questions);
?>
