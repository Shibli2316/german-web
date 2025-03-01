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
    <title>Sign In</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50">

    <div class="min-h-screen bg-gray-50 flex items-center justify-center">
        <div
            class="max-w-screen-xl m-0 sm:m-10 bg-white shadow-xl rounded-lg flex justify-center flex-1 overflow-hidden">
            <div class="flex-1 bg-indigo-100 text-center hidden lg:flex">
                <div class="m-12 xl:m-16 w-full bg-contain bg-center bg-no-repeat"
                    style="background-image: url('https://storage.googleapis.com/devitary-image-host.appspot.com/15848031292911696601-undraw_designer_life_w96d.svg');">
                </div>
            </div>
            <div class="lg:w-1/2 xl:w-5/12 p-8 sm:p-12">
                <div class="text-center mb-8">
                    <h2 class="text-4xl font-extrabold text-indigo-600">EveryDay German</h2>
                    <p class="mt-2 text-gray-600 text-lg">Your journey to mastering German starts here.</p>
                </div>
                <div class="mt-12 flex flex-col items-center">
                    <h1 class="text-3xl xl:text-4xl font-extrabold text-gray-800 mb-6">
                        Sign Up to Continue
                    </h1>
                    <form action="authorize.php" method="POST" class="space-y-4">
                        <div class="w-full flex-1 mt-8 space-y-6">
                            <div class="mx-auto max-w-xs">
                                <input
                                    class="w-full px-8 py-4 rounded-lg font-medium bg-gray-100 border border-gray-200 placeholder-gray-500 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition-all duration-200"
                                    type="name" placeholder="Name" name="name" required />

                                <input
                                    class="w-full px-8 py-4 rounded-lg font-medium bg-gray-100 border border-gray-200 placeholder-gray-500 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent mt-5 transition-all duration-200"
                                    type="email" placeholder="Email" name="email" required />
                                <input
                                    class="w-full px-8 py-4 rounded-lg font-medium bg-gray-100 border border-gray-200 placeholder-gray-500 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent mt-5 transition-all duration-200"
                                    type="password" placeholder="Password" name="password" required />
                                <input
                                    class="w-full px-8 py-4 rounded-lg font-medium bg-gray-100 border border-gray-200 placeholder-gray-500 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent mt-5 transition-all duration-200"
                                    type="password" placeholder="Confirm Password" name="cpassword" required />

                                <button
                                    class="mt-6 tracking-wide font-semibold bg-indigo-500 text-white w-full py-4 rounded-lg hover:bg-indigo-600 transition-all duration-300 ease-in-out focus:outline-none focus:ring-4 focus:ring-indigo-300 flex items-center justify-center">
                                    <svg class="w-6 h-6 -ml-2" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
                                        <circle cx="8.5" cy="7" r="4" />
                                        <path d="M20 8v6M23 11h-6" />
                                    </svg>
                                    <span class="ml-3">Sign Up</span>
                                </button>

                                <div class="text-center mt-6">
                                    <p class="text-gray-600 text-sm">Already have an account? <a href="authenticate.php"
                                            class="text-indigo-500 hover:text-indigo-700 font-semibold">Sign In</a></p>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>


        </div>
    </div>

</body>

</html>