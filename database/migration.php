<?php

require_once __DIR__ . '/../config/database.php';

$database = new Database();

$db = $database->connect();

/*
|--------------------------------------------------------------------------
| Tabla de migraciones
|--------------------------------------------------------------------------
*/

$db->exec("
CREATE TABLE IF NOT EXISTS migrations (

    id INT AUTO_INCREMENT PRIMARY KEY,

    migration VARCHAR(255) UNIQUE,

    executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP

)
");

/*
|--------------------------------------------------------------------------
| Buscar migraciones
|--------------------------------------------------------------------------
*/

$migrations = glob(__DIR__ . '/Migrations/*.sql');


sort($migrations);

foreach ($migrations as $archivo) {

    $nombre = basename($archivo);

    echo "Procesando: {$nombre}" . PHP_EOL;

    $stmt = $db->prepare(
        "SELECT COUNT(*) FROM migrations WHERE migration = ?"
    );

    $stmt->execute([$nombre]);

    $existe = $stmt->fetchColumn();

    echo "Existe: {$existe}" . PHP_EOL;

    if (!$existe) {

        $sql = file_get_contents($archivo);

        echo "Ejecutando SQL..." . PHP_EOL;

        try {

            $db->exec($sql);

            echo "OK" . PHP_EOL;

            $stmt = $db->prepare(
                "INSERT INTO migrations(migration) VALUES(?)"
            );

            $stmt->execute([$nombre]);

        } catch (PDOException $e) {

            echo "ERROR: " . $e->getMessage() . PHP_EOL;

        }

    }

}