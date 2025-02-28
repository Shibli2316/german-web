<!-- Login -->

<?php
$showPopup = false; // Flag to display popups
$message = ""; // Stores success or error messages
$messageType = ""; // Can be 'success' or 'error'

include "db_connect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $pass = trim($_POST['password']);

    // Validate inputs
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format";
        $messageType = "error";
        $showPopup = true;
    } elseif (empty($pass)) {
        $message = "Password cannot be empty";
        $messageType = "error";
        $showPopup = true;
    } else {
        // Use prepared statements to prevent SQL injection
        $sql = "SELECT * FROM bakchod WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $num = $result->num_rows;

        if ($num === 1) {
            $row = $result->fetch_assoc();
            // Verify password hash
            if (password_verify($pass, $row['password'])) {
                // Start session, regenerate session ID for security
                session_start();
                session_regenerate_id(true);
                $_SESSION['loggedin'] = true;
                $_SESSION['email'] = $email;
                
                echo "
                    <script>
                        setTimeout(() => {
                            window.location.href = 'allWords.php';
                        }, 000);
                    </script>
                ";
            } else {
                $message = "Incorrect password";
                $messageType = "error";
                $showPopup = true;
            }
        } else {
            $message = "Invalid credentials";
            $messageType = "error";
            $showPopup = true;
        }
        $stmt->close();
    }
}

if ($showPopup) {
    echo "
    <div id='popup' class='fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50'>
        <div class='bg-white p-6 rounded-lg shadow-lg text-center'>
            <h3 class='text-xl font-semibold mb-4 " . ($messageType === "success" ? "text-green-600" : "text-red-600") . "'>" . 
            ($messageType === "success" ? "Success!" : "Error!") . 
            "</h3>
            <p class='mb-4 text-gray-700'>" . 
            htmlspecialchars($message) . 
            "</p>
            <button onclick='redirectToLogin()' class='px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition'>
                Close
            </button>
        </div>
    </div>

    <script>
        function redirectToLogin() {
            window.location.href = 'authenticate.php';
        }
    </script>
    ";
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex justify-center items-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-semibold text-center text-gray-800 mb-4">Sign In</h2>

        <form action="authenticate.php" method="POST" class="space-y-4">
            <div>
                <label class="block text-gray-700">Email</label>
                <input type="email" name="email" required class="w-full px-3 py-2 border rounded-md">
            </div>

            <div>
                <label class="block text-gray-700">Password</label>
                <input type="password" name="password" required class="w-full px-3 py-2 border rounded-md">
            </div>

            <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600">
                Sign In
            </button>

            <p class="text-center text-gray-600 mt-2">Don't have an account? <a href="authorize.php" class="text-blue-500">Sign Up</a></p>
        </form>
    </div>

</body>
</html>
