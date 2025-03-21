<?php
session_start();
include 'db.php'; // Include database connection

$score = 0;
$incorrect_answers = [];
$total_questions = 40; // Update this based on the number of questions

$query = "SELECT * FROM questions";
$result = mysqli_query($conn, $query);
$questions = [];
while ($row = mysqli_fetch_assoc($result)) {
    $questions[] = $row;
}

foreach ($questions as $index => $question) {
    if (isset($_POST["question_$index"])) {
        $selected_answer = $_POST["question_$index"];
        if ($selected_answer == $question['correct_answer']) {
            $score++;
        } else {
            $incorrect_answers[] = [
                'question' => $question['question_text'],
                'selected' => $selected_answer,
                'correct' => $question['correct_answer']
            ];
        }
    }
}

$accuracy = ($score / $total_questions) * 100;

// Display results
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Exam Results</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e0f7fa; /* Light cyan */
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #2c3e50;
        }
        .result {
            text-align: center;
            margin: 20px 0;
            background-color: #ffffff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .incorrect {
            color: #e74c3c; /* Red for incorrect answers */
        }
        .charts-container {
            display: flex;
            justify-content: center; /* Center the charts */
            margin: 20px 0;
        }
        .charts-container > div {
            margin: 0 70px; /* Increased margin between the charts */
        }
        .feedback {
            margin: 20px 0;
            text-align: center;
        }
        .feedback label {
            font-size: 18px;
        }
        .feedback select {
            margin: 10px 0;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #3498db;
        }
        button {
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #2980b9;
        }
        canvas {
            max-width: 400px; /* Increased size of pie chart and bar chart */
            height: 300px; /* Fixed height for better visibility */
        }
    </style>
</head>
<body>

<div class="result">
    <h1>Results 🎉</h1>
    <p>Your Score: <strong><?php echo $score; ?></strong> out of <strong><?php echo $total_questions; ?></strong> <span>✅</span></p>
    <p>Accuracy: <strong><?php echo number_format($accuracy, 2); ?>%</strong> <span>📈</span></p>

    <div class="charts-container">
        <div>
            <canvas id="scoreChart" width="400" height="300"></canvas>
        </div>
        <div>
            <canvas id="barChart" width="400" height="300"></canvas>
        </div>
    </div>

    <script>
        const ctxPie = document.getElementById('scoreChart').getContext('2d');
        const scoreChart = new Chart(ctxPie, {
            type: 'pie',
            data: {
                labels: ['Correct Answers 😊', 'Incorrect Answers 😢'],
                datasets: [{
                    label: 'Exam Score',
                    data: [<?php echo $score; ?>, <?php echo $total_questions - $score; ?>],
                    backgroundColor: ['#2ecc71', '#e74c3c'],
                    borderColor: ['#ffffff', '#ffffff'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                }
            }
        });

        const ctxBar = document.getElementById('barChart').getContext('2d');
        const barChart = new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: ['Score', 'Total Questions'],
                datasets: [{
                    label: 'Your Score',
                    data: [<?php echo $score; ?>, <?php echo $total_questions; ?>],
                    backgroundColor: ['#3498db', '#e74c3c'],
                    borderColor: ['#2980b9', '#c0392b'],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Questions'
                        }
                    }
                },
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                }
            }
        });
    </script>
</div>

<?php if (!empty($incorrect_answers)): ?>
    <div class="result incorrect">
        <h2>Incorrect Answers:</h2>
        <?php foreach ($incorrect_answers as $answer): ?>
            <p>Question: <strong><?php echo $answer['question']; ?></strong><br>
               Your Answer: <strong><?php echo $answer['selected']; ?></strong><br>
               Correct Answer: <strong><?php echo $answer['correct']; ?></strong></p>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p>All answers were correct! Great job! 🎊</p>
<?php endif; ?>

<div class="feedback">
    <h2>Rate Your Exam Experience 🌟</h2>
    <label for="rating">Please provide your rating (1-5):</label>
    <select id="rating" name="rating">
        <option value="1">1 - Poor</option>
        <option value="2">2 - Fair</option>
        <option value="3">3 - Good</option>
        <option value="4">4 - Very Good</option>
        <option value="5">5 - Excellent</option>
    </select>
    <button type="submit">Submit Feedback</button>
</div>

</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>
