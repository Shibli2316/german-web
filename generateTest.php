<?php
session_start();
require_once('db_connect.php');

if (!isset($_SESSION['email'])) {
    header('location: authenticate.php');
    die('User not logged in');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get user inputs
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $num_words = $_POST['num_words'];

    // Convert date format to match the varchar format (d-m-Y)
    $start_date = DateTime::createFromFormat('Y-m-d', $start_date)->format('d-m-Y');
    $end_date = DateTime::createFromFormat('Y-m-d', $end_date)->format('d-m-Y');

    // Query to fetch words between the specified dates
    $sql = "SELECT w1, w2, w3, w4, w5, m1, m2, m3, m4, m5, s1, s2, s3, s4, s5, dateNow FROM word WHERE STR_TO_DATE(dateNow, '%d-%m-%Y') BETWEEN STR_TO_DATE(?, '%d-%m-%Y') AND STR_TO_DATE(?, '%d-%m-%Y')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $start_date, $end_date);
    $stmt->execute();
    $result = $stmt->get_result();

    $words = [];
    while ($row = $result->fetch_assoc()) {
        // Extract the word columns (w1 to w5) from the row
        for ($i = 1; $i <= 5; $i++) {
            if (!empty($row["w$i"])) {
                $words[] = [
                    'word' => $row["w$i"],
                    'meaning' => $row["m$i"],
                    'sentence' => $row["s$i"]
                ];
            }
        }
    }

    // Randomly shuffle and select the desired number of words
    shuffle($words);
    $selected_words = array_slice($words, 0, $num_words);
    
    $_SESSION['selected_words'] = $selected_words;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Words</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Toggle the visibility of meanings when the button is clicked
        // Toggle the visibility of meanings when the button is clicked
function toggleAnswers() {
    const meanings = document.querySelectorAll('.meaning');
    meanings.forEach(meaning => {
        // Toggle the visibility of each meaning
        if (meaning.style.display === 'none' || meaning.style.display === '') {
            meaning.style.display = 'block'; // Show the meaning
        } else {
            meaning.style.display = 'none'; // Hide the meaning
        }
    });
}

// Toggle the visibility of sentences when the button is clicked
function toggleHint() {
    const sentences = document.querySelectorAll('.sentence'); // Correct the class name to 'sentence'
    sentences.forEach(sentence => {
        // Toggle the visibility of each sentence
        if (sentence.style.display === 'none' || sentence.style.display === '') {
            sentence.style.display = 'block'; // Show the sentence
        } else {
            sentence.style.display = 'none'; // Hide the sentence
        }
    });
}

    </script>
</head>
<body class="bg-gray-100 p-6">
    
<?php require_once('header.php') ?>

    <div class="bg-white p-8 rounded-lg shadow-md max-w-lg mx-auto mt-20">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Take a test</h2>

        <form method="POST">
            <!-- Start Date -->
            <div class="mb-4">
                <label for="start_date" class="block text-gray-700 font-medium mb-2">Start Date</label>
                <input type="date" id="start_date" name="start_date" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
            </div>

            <!-- End Date -->
            <div class="mb-4">
                <label for="end_date" class="block text-gray-700 font-medium mb-2">End Date</label>
                <input type="date" id="end_date" name="end_date" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
            </div>

            <!-- Number of Words -->
            <div class="mb-4">
                <label for="num_words" class="block text-gray-700 font-medium mb-2">Number of Words</label>
                <input type="number" id="num_words" name="num_words" min="1" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
            </div>

            <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 transition">Fetch Words</button>
        </form>
    </div>

    <?php if (isset($selected_words) && count($selected_words) > 0): ?>
    <div class="mt-6 bg-white p-8 rounded-lg shadow-md max-w-lg mx-auto">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Test Words</h3>
        <ul class="space-y-4">
            <?php foreach ($selected_words as $word): ?>
                <li>
                    <div class="font-bold"><?php echo htmlspecialchars($word['word']); ?></div>
                    <!-- Hide the meaning initially with display: none -->
                    <p class="meaning text-sm text-gray-500 mt-2" style="display: none;"><?php echo htmlspecialchars($word['meaning']); ?></p>
                    <!-- Hide the sentence initially with display: none -->
                    <p class="sentence text-sm text-gray-500 mt-2" style="display: none;"><?php echo htmlspecialchars($word['sentence']); ?></p>
                </li>
            <?php endforeach; ?>
        </ul>

        <!-- Show answers button to toggle the meanings -->
        <button onclick="toggleHint()" class="w-full bg-green-500 text-white py-2 px-4 rounded-lg hover:bg-green-600 mt-4 transition">Show Hint</button>
        <button onclick="toggleAnswers()" class="w-full bg-green-500 text-white py-2 px-4 rounded-lg hover:bg-green-600 mt-4 transition">Show Answers</button>
    </div>
<?php endif; ?>


</body>
</html>
