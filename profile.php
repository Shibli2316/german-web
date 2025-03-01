<?php
session_start();
require_once('header.php');
require_once('db_connect.php'); // Assuming this file contains the database connection


if (!isset($_SESSION['email'])) {
    header('location: authenticate.php');
    die('User not logged in');
}

// Get the logged-in email
$username = $_SESSION['email'];

$bakchodData = null;
$sql_bakchod = "SELECT name, tag, quote, image FROM bakchod WHERE email = ?";
$stmt_bakchod = $conn->prepare($sql_bakchod);
$stmt_bakchod->bind_param("s", $username);
$stmt_bakchod->execute();
$result_bakchod = $stmt_bakchod->get_result();

if ($result_bakchod->num_rows > 0) {
    $bakchodData = $result_bakchod->fetch_assoc();
}
$nameUser = $bakchodData['name'];
$stmt_bakchod->close();


// Fetch words contributed by the user
$sql = "SELECT dateNow, w1, m1, s1, w2, m2, s2, w3, m3, s3, w4, m4, s4, w5, m5, s5 
        FROM word 
        WHERE bakchod = ? 
        ORDER BY dateNow DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $nameUser);
$stmt->execute();
$result = $stmt->get_result();

// Organize data by date
$dataByDate = [];
while ($row = $result->fetch_assoc()) {
    $date = $row['dateNow'];
    if (!isset($dataByDate[$date])) {
        $dataByDate[$date] = [];
    }

    // Loop through w1-w5, m1-m5, s1-s5 dynamically
    for ($i = 1; $i <= 5; $i++) {
        if (!empty($row["w$i"])) {
            $dataByDate[$date][] = [
                'word' => $row["w$i"],
                'meaning' => $row["m$i"],
                'sentence' => $row["s$i"]
            ];
        }
    }
}
$stmt->close();
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile - Shibli</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .card {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            background-color: #fff;
        }

        .card-header {
            background-color: #f7f7f7;
            padding: 1rem;
            border-radius: 8px 8px 0 0;
        }

        .table-header {
            background-color: #5c6bc0;
            color: #fff;
        }

        .table-row:hover {
            background-color: #f1f5f9;
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen flex flex-col font-sans leading-normal">


    <?php require_once('header.php') ?>

    <div class="container mx-auto py-8 px-4 mt-20">
        <div class="grid grid-cols-1 sm:grid-cols-12 gap-8">
            <!-- Profile Section -->
            <div class="col-span-1 sm:col-span-3">
                <div class="card p-6">
                    <div class="text-center">
                        <img src="<?php echo $bakchodData['image']; ?>" class="w-32 h-32 bg-gray-300 rounded-full mx-auto mb-4 shadow-lg">
                        <h1 class="text-2xl font-semibold text-gray-800">
                            <?php echo htmlspecialchars($bakchodData['name'] ?? 'Unknown'); ?>
                        </h1>
                        <p class="text-gray-500 text-sm mb-4">
                            <?php echo htmlspecialchars($bakchodData['tag'] ?? 'No tag available'); ?>
                        </p>
                        <div class="mt-6 flex justify-center gap-4">
                            <a href="editProfile.php"
                                class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg shadow-md transition duration-300">Edit</a>
                            <a href="addPhoto.php"
                                class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg shadow-md transition duration-300">Add Photo</a>
                        </div>
                    </div>
                    <hr class="my-6 border-t border-gray-200">


                </div>
            </div>
            <!-- About Section -->
            <div class="col-span-1 sm:col-span-9 ">
                <div class="card p-6">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4">My German Personality</h2>
                    <p class="text-gray-600 text-base leading-relaxed">
                        <?php echo htmlspecialchars($bakchodData['quote'] ?? 'No quote available'); ?>
                    </p>
                </div>

                <!-- Daily Learning Table Section -->
                <div class="col-span-1 sm:col-span-9 mt-6">
                    <div class="card p-8">
                        <h1 class="text-3xl font-semibold text-center text-blue-600 mb-6">Words Contributed</h1>

                        <?php if (!empty($dataByDate)): ?>
                            <?php foreach ($dataByDate as $date => $words): ?>
                                <div class="mb-8">
                                    <h2 class="text-2xl font-semibold text-gray-800 bg-blue-100 p-3 rounded-md">📅
                                        <?php echo $date; ?></h2>
                                    <table class="w-full mt-3 border border-gray-300 rounded-lg shadow-md">
                                        <thead>
                                            <tr class="bg-blue-500 text-white text-lg">
                                                <th class="p-4 text-left">Word</th>
                                                <th class="p-4 text-left">Meaning</th>
                                                <th class="p-4 text-left">Sentence</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-gray-700">
                                            <?php foreach ($words as $wordData): ?>
                                                <tr class="bg-white hover:bg-gray-200 transition">
                                                    <td class="p-4 font-medium"><?php echo htmlspecialchars($wordData['word']); ?>
                                                    </td>
                                                    <td class="p-4"><?php echo htmlspecialchars($wordData['meaning']); ?></td>
                                                    <td class="p-4 italic"><?php echo htmlspecialchars($wordData['sentence']); ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-center text-gray-600">No words contributed yet.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <?php require_once('footer.php') ?>

</body>

</html>