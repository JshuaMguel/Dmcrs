<?php
echo "Testing PostgreSQL support...\n";

// Check if PDO PostgreSQL extension is loaded
if (extension_loaded('pdo_pgsql')) {
    echo "✅ PDO PostgreSQL extension is loaded\n";
} else {
    echo "❌ PDO PostgreSQL extension is NOT loaded\n";
}

// Check if PostgreSQL extension is loaded
if (extension_loaded('pgsql')) {
    echo "✅ PostgreSQL extension is loaded\n";
} else {
    echo "❌ PostgreSQL extension is NOT loaded\n";
}

// List all loaded extensions
echo "\nAll loaded extensions:\n";
$extensions = get_loaded_extensions();
sort($extensions);
foreach ($extensions as $ext) {
    if (strpos($ext, 'pg') !== false || strpos($ext, 'pdo') !== false) {
        echo "- $ext\n";
    }
}

// Test PDO PostgreSQL connection (without actually connecting)
try {
    $availableDrivers = PDO::getAvailableDrivers();
    echo "\nAvailable PDO drivers:\n";
    foreach ($availableDrivers as $driver) {
        echo "- $driver\n";
    }
    
    if (in_array('pgsql', $availableDrivers)) {
        echo "✅ PDO PostgreSQL driver is available\n";
    } else {
        echo "❌ PDO PostgreSQL driver is NOT available\n";
    }
} catch (Exception $e) {
    echo "Error checking PDO drivers: " . $e->getMessage() . "\n";
}
?>