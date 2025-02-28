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


<?php require_once('header.php')?>

    <div class="container mx-auto py-8 px-4">
        <div class="grid grid-cols-1 sm:grid-cols-12 gap-8">
            <!-- Profile Section -->
            <div class="col-span-1 sm:col-span-3">
                <div class="card p-6">
                    <div class="text-center">
                        <img src="#"
                            class="w-32 h-32 bg-gray-300 rounded-full mx-auto mb-4 shadow-lg">
                        <h1 class="text-2xl font-semibold text-gray-800">Tawiz Sahab</h1>
                        <p class="text-gray-500 text-sm mb-4">Busy insan</p>
                        <div class="mt-6 flex justify-center gap-4">
                            <a href="#"
                                class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg shadow-md transition duration-300">Contact</a>
                        </div>
                    </div>
                    <hr class="my-6 border-t border-gray-200">
                    
                    
                </div>
            </div>
            <!-- About Section -->
            <div class="col-span-1 sm:col-span-9">
                <div class="card p-6">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4">My German Personality</h2>
                    <p class="text-gray-600 text-base leading-relaxed">
                        "Die beste Zeit, einen Baum zu pflanzen, war vor zwanzig Jahren. Die zweitbeste Zeit ist jetzt."

                        Meaning: "The best time to plant a tree was twenty years ago. The second best time is now."
                    </p>
                </div>

                <!-- Daily Learning Table Section -->
                <div class="card p-8 mt-6">
                    <h1 class="text-3xl font-semibold text-center text-blue-600 mb-6">Words contributed by date</h1>

                    <!-- Table for 27-02-2025 -->
                    <div class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-800 bg-blue-100 p-3 rounded-md">📅 27-02-2025</h2>
                        <table class="w-full mt-3 border border-gray-300 rounded-lg shadow-md">
                            <thead>
                                <tr class="table-header text-lg">
                                    <th class="p-4 text-left">Word</th>
                                    <th class="p-4 text-left">Meaning</th>
                                    <th class="p-4 text-left">Sentence</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700">
                                <tr class="table-row">
                                    <td class="p-4 font-medium">Hallo</td>
                                    <td class="p-4 font-medium">Hello</td>
                                    <td class="p-4 italic">Hallo, wie geht es dir?</td>
                                </tr>
                                <tr class="table-row">
                                    <td class="p-4 font-medium">Danke</td>
                                    <td class="p-4 font-medium">Thank you</td>
                                    <td class="p-4 italic">Danke für deine Hilfe.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Table for 28-02-2025 -->
                    <div class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-800 bg-purple-100 p-3 rounded-md">📅 28-02-2025</h2>
                        <table class="w-full mt-3 border border-gray-300 rounded-lg shadow-md">
                            <thead>
                                <tr class="table-header text-lg">
                                    <th class="p-4 text-left">Word</th>
                                    <th class="p-4 text-left">Meaning</th>
                                    <th class="p-4 text-left">Sentence</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700">
                                <tr class="table-row">
                                    <td class="p-4 font-medium">Bitte</td>
                                    <td class="p-4 font-medium">Please</td>
                                    <td class="p-4 italic">Bitte, können Sie mir helfen?</td>
                                </tr>
                                <tr class="table-row">
                                    <td class="p-4 font-medium">Guten Morgen</td>
                                    <td class="p-4 font-medium">Good Morning</td>
                                    <td class="p-4 italic">Guten Morgen! Wie war deine Nacht?</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>


    <?php require_once('footer.php')?>

</body>

</html>