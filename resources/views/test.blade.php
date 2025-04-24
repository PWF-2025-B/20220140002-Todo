<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dark Mode Test</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-white dark:bg-black text-black dark:text-white">
    <button id="dark-toggle" class="p-2 border m-4">
        <span id="theme-icon">🌙</span> Toggle Theme
    </button>
    <p class="m-4">Ini paragraf tes untuk dark mode.</p>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const html = document.documentElement;
            const toggle = document.getElementById('dark-toggle');
            const icon = document.getElementById('theme-icon');

            if (localStorage.getItem('theme') === 'dark') {
                html.classList.add('dark');
                icon.textContent = '☀️';
            } else {
                html.classList.remove('dark');
                icon.textContent = '🌙';
            }

            toggle.addEventListener('click', () => {
                html.classList.toggle('dark');
                if (html.classList.contains('dark')) {
                    localStorage.setItem('theme', 'dark');
                    icon.textContent = '☀️';
                } else {
                    localStorage.setItem('theme', 'light');
                    icon.textContent = '🌙';
                }
            });
        });
    </script>
</body>
</html>
