<?php
// Start session
session_start();

// Include the database connection
include 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['student_id'])) {
    header('Location: login.php');
    exit();
}

// Fetch questions from the database
$query = "SELECT * FROM questions";
$result = mysqli_query($conn, $query);

// Check for database errors
if (!$result) {
    die("Database query failed: " . mysqli_error($conn));
}

// Count total questions
$total_questions = mysqli_num_rows($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Online Exam</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js" defer></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e0f7fa; /* Light cyan background */
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 20px;
        }
        #timer {
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
            padding: 10px;
            background-color: #ffcc00; /* Bright yellow */
            color: #ffffff;
            border-radius: 5px;
            font-weight: bold;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
        .question {
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            color: #2c3e50; /* Darker text for questions */
        }
        .question p {
            margin: 0 0 10px;
            font-size: 18px;
            color: #2980b9; /* Blue color for question text */
        }
        label {
            display: block;
            margin-bottom: 5px;
            padding: 10px;
            background-color: #f8f9fa; /* Light gray for labels */
            border-radius: 3px;
            transition: background-color 0.3s;
            color: #333; /* Dark text for options */
        }
        label:hover {
            background-color: #d1ecf1; /* Light blue on hover */
        }
        button {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            font-size: 18px;
            background-color: #3498db; /* Blue */
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #2980b9; /* Darker blue */
        }
    </style>
</head>
<body>
    <form action="submit_exam.php" method="POST" id="exam-form">
        <h1>Online Exam</h1>
        <div id="timer">Time left: <span id="time">30:00</span></div>

        <?php
        $index = 0;

        // Loop through the questions
        while ($question = mysqli_fetch_assoc($result)) {
            echo "<div class='question'>";
            echo "<p>" . ($index + 1) . ". " . htmlspecialchars($question['question_text']) . "</p>";
            echo "<label><input type='radio' name='question_$index' value='" . htmlspecialchars($question['option_a']) . "'> " . htmlspecialchars($question['option_a']) . "</label>";
            echo "<label><input type='radio' name='question_$index' value='" . htmlspecialchars($question['option_b']) . "'> " . htmlspecialchars($question['option_b']) . "</label>";
            echo "<label><input type='radio' name='question_$index' value='" . htmlspecialchars($question['option_c']) . "'> " . htmlspecialchars($question['option_c']) . "</label>";
            echo "<label><input type='radio' name='question_$index' value='" . htmlspecialchars($question['option_d']) . "'> " . htmlspecialchars($question['option_d']) . "</label>";
            echo "</div>";
            $index++;
        }

        // Close the result set
        mysqli_free_result($result);
        ?>
        
        <button type="submit">Submit Exam</button>
    </form>
</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>
