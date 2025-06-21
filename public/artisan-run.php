<?php
// artisan-run.php
// Jalankan perintah artisan penting dari browser untuk cPanel hosting

$commands = [
    'php artisan config:cache',
    'php artisan cache:clear',
    'php artisan route:clear',
    'php artisan view:clear',
    'php artisan migrate', // opsional, hapus jika tidak ingin auto-migrate
];

foreach ($commands as $cmd) {
    echo "<b>$cmd</b><br>";
    echo nl2br(shell_exec($cmd));
    echo "<hr>";
}
echo "<b>Done!</b>";
