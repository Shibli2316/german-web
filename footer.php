
    <!-- Footer -->
    <footer class="bg-blue-600 py-4 mt-10">
        <div class="container mx-auto text-center">
            <p class="text-white text-lg font-semibold">Made by <a href="https://github.com/shibli2316" target="_blank" class="text-yellow-400 hover:text-yellow-500">Shibli</a></p>
            <p id="current-year" class="text-white text-sm">© <span id="year"></span> All Rights Reserved</p>
        </div>
    </footer>

    <script>
        
        const currentYear = new Date().getFullYear();
        
        document.getElementById("year").textContent = currentYear;
    </script>