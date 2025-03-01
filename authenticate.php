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

<body class="bg-gray-50">

    <div class="min-h-screen bg-gray-50 flex items-center justify-center">
        <div
            class="max-w-screen-xl m-0 sm:m-10 bg-white shadow-xl rounded-lg flex justify-center flex-1 overflow-hidden">
            <div class="lg:w-1/2 xl:w-5/12 p-8 sm:p-12">
                <div class="text-center mb-8">
                    <h2 class="text-4xl font-extrabold text-indigo-600">EveryDay German</h2>
                    <p class="mt-2 text-gray-600 text-lg">Your journey to mastering German starts here.</p>
                </div>
                <div class="mt-12 flex flex-col items-center">
                    <h1 class="text-3xl xl:text-4xl font-extrabold text-gray-800 mb-6">
                        Sign In to Continue
                    </h1>
                    <form action="authenticate.php" method="POST" class="space-y-4">
                        <div class="w-full flex-1 mt-8 space-y-6">
                            <div class="mx-auto max-w-xs">
                                <input
                                    class="w-full px-8 py-4 rounded-lg font-medium bg-gray-100 border border-gray-200 placeholder-gray-500 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition-all duration-200"
                                    type="email" placeholder="Email" name="email" required />
                                <input
                                    class="w-full px-8 py-4 rounded-lg font-medium bg-gray-100 border border-gray-200 placeholder-gray-500 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent mt-5 transition-all duration-200"
                                    type="password" placeholder="Password" name="password" required />

                                <button
                                    class="mt-6 tracking-wide font-semibold bg-indigo-500 text-white w-full py-4 rounded-lg hover:bg-indigo-600 transition-all duration-300 ease-in-out focus:outline-none focus:ring-4 focus:ring-indigo-300 flex items-center justify-center">
                                    <svg class="w-6 h-6 -ml-2" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
                                        <circle cx="8.5" cy="7" r="4" />
                                        <path d="M20 8v6M23 11h-6" />
                                    </svg>
                                    <span class="ml-3">Sign In</span>
                                </button>

                                <div class="text-center mt-6">
                                    <p class="text-gray-600 text-sm">Don't have an account? <a href="authorize.php"
                                            class="text-indigo-500 hover:text-indigo-700 font-semibold">Sign Up</a></p>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="flex-1 bg-indigo-100 text-center hidden lg:flex">
                <div class="m-12 xl:m-16 w-full bg-contain bg-center bg-no-repeat"
                    style="background-image: url('https://storage.googleapis.com/devitary-image-host.appspot.com/15848031292911696601-undraw_designer_life_w96d.svg');">
                </div>
            </div>
        </div>
    </div>

</body>

</html>