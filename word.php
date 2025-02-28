<?php
session_start(); // Start the session to get user information

// Check if the session contains the email, else redirect to login page
if (!isset($_SESSION['email'])) {
    header("Location: authenticate.php");
    exit();
}

$email = $_SESSION['email']; // Get the email from session

// Connect to your database
include "db_connect.php"; // Assuming this file connects to your database

// Fetch the username based on the email stored in the session
$sql = "SELECT name FROM bakchod WHERE email = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($username);
    $stmt->fetch(); // Fetch the username from the result
    $stmt->close();
} else {
    $username = "Unknown"; // Default username if query fails
}

// Get today's date in DD-MM-YYYY format
$dateNow = date('d-m-Y');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get words and sentences from the form
    $words = $_POST['word'];
    $sentences = $_POST['sentence'];
    $meaning = $_POST['meaning'];

    // Prepare the SQL query to insert data into the database
    $sql = "INSERT INTO word (bakchod, dateNow, w1, s1, w2, s2, w3, s3, w4, s4, w5, s5, m1, m2, m3, m4, m5) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare and bind the SQL statement
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param(
            "sssssssssssssssss",
            $username,  // bakchod
            $dateNow,   // dateNow
            $words[0],  // w1
            $sentences[0], // s1
            $words[1],  // w2
            $sentences[1], // s2
            $words[2],  // w3
            $sentences[2], // s3
            $words[3],  // w4
            $sentences[3], // s4
            $words[4],  // w5
            $sentences[4], // s5
            $meaning[0],
            $meaning[1],
            $meaning[2],
            $meaning[3],
            $meaning[4]
        );

        // Execute the query
        if ($stmt->execute()) {
            $message = "Data submitted successfully! You are being redirected.";
            $messageType = "success";
        } else {
            $message = "Error submitting data. Please try again.";
            $messageType = "error";
        }
        $stmt->close(); // Close the statement
    } else {
        $message = "Error with the database query.";
        $messageType = "error";
    }

    $conn->close(); // Close the database connection
}
?>





<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Language Learning Form - Step 2</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex justify-center items-center min-h-screen">


    <?php require_once('header.php') ?>
    <?php if (isset($message)): ?>
        <!-- Modal -->
        <div id="popupModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white p-8 rounded-lg shadow-lg text-center">
                <h3 class="text-xl font-semibold mb-4 text-<?= $messageType === 'success' ? 'green' : 'red' ?>-600">
                    <?= $messageType === 'success' ? 'Success!' : 'Error!' ?>
                </h3>
                <p class="mb-4 text-gray-700"><?= htmlspecialchars($message) ?></p>
                <button onclick="closeModalAndRedirect()"
                    class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition">
                    Close
                </button>
            </div>
        </div>

        <script>
            function closeModalAndRedirect() {
                document.getElementById('popupModal').style.display = 'none';
                setTimeout(() => {
                    window.location.href = 'allWords.php'; // Redirect after closing modal
                }, 300); // Short delay before redirect
            }

            // Auto close the modal after 3 seconds and redirect
            setTimeout(closeModalAndRedirect, 3000);
        </script>
    <?php endif; ?>
    <div class="bg-white p-8 rounded-lg shadow-lg w-96 mt-20">
        <form action="word.php" method="POST">
            <h2 class="text-2xl font-semibold mb-4 text-center">Add your words</h2>

            <!-- Hidden Fields to Carry Data from Step 1 -->
            <input type="hidden" name="date" value="<?php echo $date; ?>">
            <input type="hidden" name="name" value="<?php echo $name; ?>">

            <!-- Word Input Repeated 5 Times -->
            <div id="word-section" class="space-y-4">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <div class="mb-4">
                        <label for="word-<?php echo $i; ?>" class="block text-sm font-medium text-gray-700">Word
                            <?php echo $i; ?></label>
                        <input type="text" id="word-<?php echo $i; ?>" name="word[]" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md text-gray-700">
                    </div>
                    <div class="mb-4">
                        <label for="meaning-<?php echo $i; ?>" class="block text-sm font-medium text-gray-700">Meaning
                            <?php echo $i; ?></label>
                        <input type="text" id="meaning-<?php echo $i; ?>" name="meaning[]" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md text-gray-700">
                    </div>
                    <div class="mb-4">
                        <label for="sentence-<?php echo $i; ?>" class="block text-sm font-medium text-gray-700">Sentence
                            <?php echo $i; ?></label>
                        <input type="text" id="sentence-<?php echo $i; ?>" name="sentence[]" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md text-gray-700">
                    </div>
                <?php endfor; ?>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 mt-4">
                Submit
            </button>
        </form>
    </div>

</body>

</html>