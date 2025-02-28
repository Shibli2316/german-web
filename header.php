

    <nav class="bg-gradient-to-r from-blue-600 to-blue-800 shadow-lg fixed top-0 left-0 w-full z-50">
        <div class="container mx-auto flex justify-between items-center py-4 px-6">
            
            <!-- Logo -->
            <a href="allWords.php" class="text-white font-bold text-2xl tracking-wide">EveryDay German</a>

            <!-- Desktop Menu -->
            <div class="hidden md:flex space-x-6">
                <a href="allWords.php" class="text-white py-2 px-5 rounded-lg hover:bg-white hover:text-blue-700 transition-all">Home</a>
                <a href="generateTest.php" class="text-white py-2 px-5 rounded-lg hover:bg-white hover:text-blue-700 transition-all">Test</a>
                <a href="word.php" class="text-white py-2 px-5 rounded-lg hover:bg-white hover:text-blue-700 transition-all">Add</a>
                <a href="profile.php" class="text-white py-2 px-5 rounded-lg hover:bg-white hover:text-blue-700 transition-all">Profile</a>
                <a href="logout.php" class="text-white py-2 px-5 rounded-lg bg-red-500 hover:bg-red-600 transition-all">Logout</a>
            </div>

            <!-- Mobile Menu Button -->
            <button id="menu-btn" class="md:hidden text-white text-2xl focus:outline-none">
                ☰
            </button>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden flex flex-col items-center space-y-4 pb-4 bg-blue-700">
            <a href="allWords.php" class="text-white py-2 w-full text-center hover:bg-white hover:text-blue-700 transition-all">Home</a>
            <a href="generateTest.php" class="text-white py-2 w-full text-center hover:bg-white hover:text-blue-700 transition-all">Test</a>
            <a href="word.php" class="text-white py-2 w-full text-center hover:bg-white hover:text-blue-700 transition-all">Add</a>
            <a href="profile.php" class="text-white py-2 w-full text-center hover:bg-white hover:text-blue-700 transition-all">Profile</a>
            <a href="logout.php" class="text-white py-2 w-full text-center bg-red-500 hover:bg-red-600 transition-all">Logout</a>
        </div>
    </nav>

    <script>
        document.getElementById('menu-btn').addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });
    </script>


