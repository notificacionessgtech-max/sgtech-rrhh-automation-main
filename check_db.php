<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

echo "=== Estado de la Base de Datos ===" . PHP_EOL . PHP_EOL;

$userCount = User::count();
echo "Total de usuarios: " . $userCount . PHP_EOL . PHP_EOL;

if ($userCount > 0) {
    echo "Usuarios encontrados:" . PHP_EOL;
    $users = User::all();
    foreach ($users as $user) {
        echo "- Nombre: " . $user->name . PHP_EOL;
        echo "  Email: " . $user->email . PHP_EOL;
        echo "  Roles: " . $user->getRoleNames()->implode(', ') . PHP_EOL;
        echo PHP_EOL;
    }
} else {
    echo "⚠️ No hay usuarios en la base de datos" . PHP_EOL;
}
