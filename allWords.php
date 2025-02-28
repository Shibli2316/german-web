<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white min-h-screen flex justify-center items-center p-6">

    <div class="max-w-5xl w-full bg-white shadow-2xl rounded-2xl p-8">
        <h1 class="text-4xl font-bold text-center text-blue-600 mb-6">Vocabulary Store date wise</h1>

        
        <div class="mb-6">
            <input 
                type="text" 
                id="searchInput" 
                class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Search by User, Word, Sentence, or Meaning"
                onkeyup="searchTable()"
            >
        </div>

        
        <div class="mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 bg-blue-200 p-3 rounded-md">📅 27-02-2025</h2>
            <table class="w-full mt-3 border border-gray-300 rounded-lg shadow-md" id="table1">
                <thead>
                    <tr class="bg-blue-500 text-white text-lg">
                        <th class="p-3">User</th>
                        <th class="p-3">Word</th>
                        <th class="p-3">Sentence</th>
                        <th class="p-3">Meaning</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 text-lg">
                    <tr class="bg-gray-100 hover:bg-gray-200 transition">
                        <td class="p-4 font-medium">Shibli</td>
                        <td class="p-4 font-medium">Hallo</td>
                        <td class="p-4 italic">Hallo, wie geht es dir?</td>
                        <td class="p-4">Hello</td>
                    </tr>
                    <tr class="bg-white hover:bg-gray-200 transition">
                        <td class="p-4 font-medium">Aisha</td>
                        <td class="p-4 font-medium">Danke</td>
                        <td class="p-4 italic">Danke für deine Hilfe.</td>
                        <td class="p-4">Thank you</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Table for 28-02-2025 -->
        <div class="mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 bg-purple-200 p-3 rounded-md">📅 28-02-2025</h2>
            <table class="w-full mt-3 border border-gray-300 rounded-lg shadow-md" id="table2">
                <thead>
                    <tr class="bg-purple-500 text-white text-lg">
                        <th class="p-3">User</th>
                        <th class="p-3">Word</th>
                        <th class="p-3">Sentence</th>
                        <th class="p-3">Meaning</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 text-lg">
                    <tr class="bg-gray-100 hover:bg-gray-200 transition">
                        <td class="p-4 font-medium">Shibli</td>
                        <td class="p-4 font-medium">Bitte</td>
                        <td class="p-4 italic">Bitte, können Sie mir helfen?</td>
                        <td class="p-4">Please</td>
                    </tr>
                    <tr class="bg-white hover:bg-gray-200 transition">
                        <td class="p-4 font-medium">Aisha</td>
                        <td class="p-4 font-medium">Guten Morgen</td>
                        <td class="p-4 italic">Guten Morgen! Wie war deine Nacht?</td>
                        <td class="p-4">Good morning</td>
                    </tr>
                </tbody>
            </table>
        </div>

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

                    
                    if (matchFound) {
                        row.style.display = "";
                    } else {
                        row.style.display = "none";
                    }
                }
            });
        }
    </script>

</body>
</html>
