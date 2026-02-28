<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$data = [
    'nama_objek' => 'Test Wisata',
    'id_kecamatan' => 1,
    'alamat' => 'Alamat',
    'latitude' => 10,
    'longitude' => 10,
    'keterangan' => 'Keterangan',
    'akses_transportasi' => ['Kendaraan Pribadi (Mobil)', 'Bus Pariwisata']
];

$obj = \App\Models\ObjekWisata::create($data);
echo "ID: " . $obj->id . "\n";
echo "Akses: " . json_encode($obj->akses_transportasi) . "\n";
$db_obj = \App\Models\ObjekWisata::find($obj->id);
echo "From DB: " . json_encode($db_obj->akses_transportasi) . "\n";
$db_obj->delete();
