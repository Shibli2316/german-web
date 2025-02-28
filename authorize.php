
<?php
include "db_connect.php";
$showPopup = false; // Flag for displaying popups
$errorMessage = ""; // Stores error messages
$successMessage = ""; // Stores success messages

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate inputs
    $name = trim(mysqli_real_escape_string($conn, $_POST["name"]));
    $email = filter_var(trim(mysqli_real_escape_string($conn, $_POST['email'])), FILTER_SANITIZE_EMAIL);
    $password = trim(mysqli_real_escape_string($conn, $_POST["password"]));
    $cpassword = trim(mysqli_real_escape_string($conn, $_POST["cpassword"]));

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = "Invalid email format";
        $showPopup = true;
    } elseif (strlen($password) < 5) {
        $errorMessage = "Password too small (minimum 5 characters)";
        $showPopup = true;
    } else {
        // Check if the email already exists
        $existSql = "SELECT 1 FROM `bakchod` WHERE email = ?";
        $stmt = $conn->prepare($existSql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        $numExistRows = $stmt->num_rows;
        $stmt->close();

        if ($numExistRows > 0) {
            $errorMessage = "Email already exists";
            $showPopup = true;
        } else {
            // Check if passwords match
            if ($password === $cpassword) {
                // Hash the password
                $hash = password_hash($password, PASSWORD_DEFAULT);

                // Start a transaction for both inserts
                $conn->begin_transaction();

                try {
                    // Insert into `auth` table
                    $authSql = "INSERT INTO `bakchod` (name, password, email) VALUES (?, ?, ?)";
                    $authStmt = $conn->prepare($authSql);
                    $authStmt->bind_param("sss", $name, $hash, $email);
                    $authStmt->execute();

                    

                    // Commit transaction if both queries succeed
                    $conn->commit();

                    // Close statements
                    $authStmt->close();
                    

                    // Display success popup and redirect
                    $successMessage = "Account created successfully! You will be redirected to the login page shortly.";
                    $showPopup = true;

                    // JavaScript for delayed redirection
                    echo "
                        <script>
                            setTimeout(() => {
                                window.location.href = 'authenticate.php';
                            }, 1000); // Redirect after 1 seconds
                        </script>
                    ";
                } catch (Exception $e) {
                    // Rollback transaction on failure
                    $conn->rollback();
                    $errorMessage = "Something went wrong. Please try again later.";
                    $showPopup = true;
                }
            } else {
                $errorMessage = "Passwords do not match";
                $showPopup = true;
            }
        }
    }
}

if ($showPopup) {
    echo "
    <div id='popup' class='fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50'>
        <div class='bg-white p-6 rounded-lg shadow-lg text-center'>
            <h3 class='text-xl font-semibold mb-4 " . ($successMessage ? "text-green-600" : "text-red-600") . "'>" .
            ($successMessage ? "Success!" : "Error!") .
            "</h3>
            <p class='mb-4 text-gray-700'>" .
            htmlspecialchars($successMessage ?: $errorMessage) .
            "</p>
            <button onclick='redirectToSignup()' class='px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition'>
                Close
            </button>
        </div>
    </div>

    <script>
        function redirectToSignup() {
            window.location.href = 'authorize.php';
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
    <title>Sign Up</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex justify-center items-center min-h-screen">

    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-semibold text-center text-gray-800 mb-4">Create an Account</h2>

        <form action="authorize.php" method="POST" class="space-y-4">
            <div>
                <label class="block text-gray-700">Name</label>
                <input type="text" name="name" required class="w-full px-3 py-2 border rounded-md">
            </div>

            <div>
                <label class="block text-gray-700">Email</label>
                <input type="email" name="email" required class="w-full px-3 py-2 border rounded-md">
            </div>

            <div>
                <label class="block text-gray-700">Password</label>
                <input type="password" name="password" required class="w-full px-3 py-2 border rounded-md">
            </div>

            <div>
                <label class="block text-gray-700">Confirm Password</label>
                <input type="password" name="cpassword" required class="w-full px-3 py-2 border rounded-md">
            </div>

            <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600">
                Sign Up
            </button>

            <p class="text-center text-gray-600 mt-2">Already have an account? <a href="authenticate.php" class="text-blue-500">Sign In</a></p>
        </form>
    </div>

</body>
</html>
