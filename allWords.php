<?php 
// Database connection
include "db_connect.php";

// Fetch words from the database
$sql = "SELECT * FROM word ORDER BY dateNow DESC";
$result = $conn->query($sql);

// Initialize an empty array to group words by date
$data_by_date = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $date = $row['dateNow'];
        
        // Store the words in a structured format
        for ($i = 1; $i <= 5; $i++) {
            if (!empty($row["w$i"])) {
                $data_by_date[$date][] = [
                    'user' => $row['bakchod'],
                    'word' => $row["w$i"],
                    'sentence' => $row["s$i"],
                    'meaning' => $row["m$i"]
                ];
            }
        }
    }
} else {
    echo "<p class='text-center text-gray-500'>No words found in the database.</p>";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex justify-center items-center p-6">

<?php require_once('header.php'); ?>

<div class="max-w-5xl w-full bg-white shadow-2xl rounded-2xl p-8 m-6 sm:m-10 lg:m-20">

    <!-- Navigation Buttons -->
    <div class="flex flex-col sm:flex-row justify-between gap-4 mb-6">
        <a href="download.php" class="bg-blue-500 text-white px-6 py-3 rounded-lg shadow-lg hover:bg-blue-600 transition-all ease-in-out duration-300 transform hover:scale-105">
            Download Word List
        </a>
        <a href="generateTest.php" class="bg-blue-500 text-white px-6 py-3 rounded-lg shadow-lg hover:bg-blue-600 transition-all ease-in-out duration-300 transform hover:scale-105">
            Generate Test
        </a>
    </div>

    <h1 class="text-4xl font-bold text-center text-blue-600 mb-8">Vocabulary Store (Date-wise)</h1>

    <!-- Search Input -->
    <div class="mb-6">
        <input 
            type="text" 
            id="searchInput" 
            class="w-full p-4 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="Search by User, Word, Sentence, or Meaning"
            onkeyup="searchTable()"
        >
    </div>

    <!-- Word Entries By Date -->
    <?php foreach ($data_by_date as $date => $words): ?>
        <div class="mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 bg-blue-200 p-4 rounded-md shadow-md mb-4">📅 <?php echo htmlspecialchars($date); ?></h2>

            <!-- Table Container with Scroll -->
            <div class="overflow-x-auto">
                <table class="w-full mt-3 border border-gray-300 rounded-lg shadow-md hover:shadow-xl transition-all duration-300">
                    <thead>
                        <tr class="bg-blue-500 text-white text-lg">
                            <th class="p-4">User</th>
                            <th class="p-4">Word</th>
                            <th class="p-4">Sentence</th>
                            <th class="p-4">Meaning</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 text-lg">
                        <?php foreach ($words as $word): ?>
                            <tr class="bg-gray-100 hover:bg-gray-200 transition-all duration-300">
                                <td class="p-4 font-medium"><?php echo htmlspecialchars($word['user']); ?></td>
                                <td class="p-4 font-medium"><?php echo htmlspecialchars($word['word']); ?></td>
                                <td class="p-4 italic"><?php echo htmlspecialchars($word['sentence']); ?></td>
                                <td class="p-4"><?php echo htmlspecialchars($word['meaning']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endforeach; ?>

</div>

<script>
    function searchTable() {
        let input = document.getElementById("searchInput").value.toLowerCase();
        let tables = document.querySelectorAll('table');

        tables.forEach(table => {
            let rows = table.getElementsByTagName('tr');
            for (let i = 1; i < rows.length; i++) { 
                let row = rows[i];
                let cells = row.getElementsByTagName('td');
                let matchFound = false;

                for (let cell of cells) {
                    if (cell.textContent.toLowerCase().includes(input)) {
                        matchFound = true;
                        break;
                    }
                }

                row.style.display = matchFound ? "" : "none";
            }
        });
    }
</script>

</body>
</html>
