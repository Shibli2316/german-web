<?php 
session_start();
require_once('db_connect.php'); // Include your DB connection

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    die("User not logged in.");
}

$username = $_SESSION['email'];
$bakchodData = null;

// Fetch existing user data
$sql_bakchod = "SELECT name, tag, quote FROM bakchod WHERE email = ?";
$stmt_bakchod = $conn->prepare($sql_bakchod);
$stmt_bakchod->bind_param("s", $username);
$stmt_bakchod->execute();
$result_bakchod = $stmt_bakchod->get_result();

if ($result_bakchod->num_rows > 0) {
    $bakchodData = $result_bakchod->fetch_assoc();
}

$stmt_bakchod->close();

// Handle form submission (Update the database)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_tag = $_POST['tag'];
    $new_quote = $_POST['quote'];

    $update_sql = "UPDATE bakchod SET tag = ?, quote = ? WHERE email = ?";
    $stmt_update = $conn->prepare($update_sql);
    $stmt_update->bind_param("sss", $new_tag, $new_quote, $username);

    if ($stmt_update->execute()) {
        echo "<p class='text-green-500'>Profile updated successfully!</p>";
        // Refresh the page to reflect changes
        header("Location: profile.php");
        exit();
    } else {
        echo "<p class='text-red-500'>Error updating profile.</p>";
    }

    $stmt_update->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    
<?php require_once('header.php') ?>

    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4 text-center">Edit Profile</h2>

        <form method="POST">
            <!-- Email (Read-Only) -->
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Email (cannot be changed)</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($username); ?>" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-200 cursor-not-allowed" readonly>
            </div>

            <!-- Tag -->
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Tag</label>
                <input type="text" name="tag" value="<?php echo htmlspecialchars($bakchodData['tag'] ?? ''); ?>" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-blue-300" required>
            </div>

            <!-- Quote -->
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Quote</label>
                <textarea name="quote" rows="3" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-blue-300" required><?php echo htmlspecialchars($bakchodData['quote'] ?? ''); ?></textarea>
            </div>

            <!-- Submit Button -->
            <div class="mt-6">
                <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 transition">
                    Save Changes
                </button>
            </div>
        </form>
    </div>

</body>
</html>
