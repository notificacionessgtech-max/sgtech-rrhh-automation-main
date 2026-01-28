<?php

use App\Models\User;

echo "=== Usuarios creados en la base de datos ===" . PHP_EOL . PHP_EOL;

$users = User::all();

foreach ($users as $user) {
    echo "Nombre: " . $user->name . PHP_EOL;
    echo "Email: " . $user->email . PHP_EOL;
    echo "Roles: " . $user->getRoleNames()->implode(', ') . PHP_EOL;
    echo "---" . PHP_EOL;
}

echo PHP_EOL . "Total de usuarios: " . $users->count() . PHP_EOL;
