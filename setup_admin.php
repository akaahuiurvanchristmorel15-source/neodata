<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = \Illuminate\Http\Request::capture()
);

$admin = \App\Models\User::where('role', 'admin')->first();
if ($admin) {
    echo "Admin trouvé: {$admin->email}\n";
} else {
    $admin = \App\Models\User::create([
        'name' => 'Admin Principal',
        'email' => 'admin@neodata.local',
        'password' => bcrypt('AdminPass2024!'),
        'role' => 'admin'
    ]);
    echo "Admin créé: {$admin->email}\n";
}
?>
