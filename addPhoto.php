<?php
// Include database connection
include('db_connect.php'); // Ensure you have your database connection here
// Check if user is logged in, and if email is available in session
session_start();
if (!isset($_SESSION['email'])) {
    header('location: authenticate.php');
    die('User not logged in');
}

$user_email = $_SESSION['email'];  // Get logged-in user's email
$target_dir = "uploads/";  // Directory to save uploaded files

// Check if form is submitted and file is uploaded
if (isset($_POST["submit"]) && isset($_FILES["profilePhoto"])) {
    $target_file = $target_dir . basename($_FILES["profilePhoto"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if file is an image
    $check = getimagesize($_FILES["profilePhoto"]["tmp_name"]);
    if ($check !== false) {
        $message =  "File is an image - " . $check["mime"] . ".";
        $messageType = "success";
    } else {
        $message =  "File is not an image.";
        $uploadOk = 0;
        $messageType = "error";
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        $message =  "Sorry, file already exists.";
        $uploadOk = 0;
        $messageType = "error";
    }

    // Limit file size (max 2MB)
    if ($_FILES["profilePhoto"]["size"] > 2000000) {
        $message =  "Sorry, your file is too large.";
        $uploadOk = 0;
        $messageType = "error";
    }

    // Allow only certain file formats
    if (!in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
        $message =  "Sorry, only JPG, JPEG, PNG, and GIF files are allowed.";
        $uploadOk = 0;
        $messageType = "error";
    }

    // Check if uploadOk is 1 (i.e., no error)
    if ($uploadOk == 0) {
        $message =  "Sorry, your file was not uploaded.";
        $messageType = "error";
    } else {
        // Try to upload the file
        if (move_uploaded_file($_FILES["profilePhoto"]["tmp_name"], $target_file)) {
            $message =  "The file has been uploaded.";
            $messageType = "success";

            // Update the database with the file path
            $image_path = $target_file;  // Path of the uploaded image

            // SQL query to update the profile photo path for the logged-in user
            $sql = "UPDATE bakchod SET image = ? WHERE email = ?";
            $stmt = $conn->prepare($sql);  // Prepare the SQL statement
            $stmt->bind_param("ss", $image_path, $user_email);  // Bind parameters (image path, user email)

            // Execute the query
            if ($stmt->execute()) {
                $message =  "Profile photo path updated in database.";
                $messageType = "success";
            } else {
                $message =  "Error updating profile photo path in database.";
                $messageType = "error";
            }

            // Close the prepared statement
            $stmt->close();
        } else {
            $message =  "Sorry, there was an error uploading your file.";
            $messageType = "error";
        }
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Profile Photo</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex justify-center items-center p-6">
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
                    window.location.href = 'profile.php'; // Redirect after closing modal
                }, 300); // Short delay before redirect
            }

            // Auto close the modal after 3 seconds and redirect
            setTimeout(closeModalAndRedirect, 3000);
        </script>
    <?php endif; ?>

    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-lg">
        <h1 class="text-2xl font-bold text-center text-blue-600 mb-6">Upload Profile Photo</h1>

        <!-- Form for profile photo upload -->
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="mb-4">
                <label for="profilePhoto" class="block text-lg font-semibold text-gray-700">Choose Profile Photo</label>
                <input 
                    type="file" 
                    name="profilePhoto" 
                    id="profilePhoto" 
                    class="mt-2 p-3 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                    accept="image/*" 
                    required 
                />
            </div>

            <!-- Submit Button -->
            <div class="flex justify-center">
                <button type="submit" name="submit" class="bg-blue-500 text-white px-6 py-3 rounded-lg shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Upload Photo
                </button>
            </div>
        </form>

    </div>

</body>
</html>
