<?php
/**
 * Database Migration Script: Railway MySQL → Supabase PostgreSQL
 * Run this script to export your data from Railway and prepare for Supabase import
 */

require_once 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Railway MySQL Connection
$mysqlHost = $_ENV['DB_HOST'] ?? 'localhost';
$mysqlPort = $_ENV['DB_PORT'] ?? '3306';
$mysqlDB = $_ENV['DB_DATABASE'] ?? 'dmcrs';
$mysqlUser = $_ENV['DB_USERNAME'] ?? 'root';
$mysqlPass = $_ENV['DB_PASSWORD'] ?? '';

try {
    $mysql = new PDO("mysql:host={$mysqlHost};port={$mysqlPort};dbname={$mysqlDB}", $mysqlUser, $mysqlPass);
    $mysql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Connected to Railway MySQL database\n";
    
    // Get all tables
    $tables = $mysql->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    
    echo "📊 Found " . count($tables) . " tables to export:\n";
    foreach ($tables as $table) {
        echo "  - {$table}\n";
    }
    
    // Export each table to SQL
    $exportFile = 'supabase_import.sql';
    $handle = fopen($exportFile, 'w');
    
    fwrite($handle, "-- DMCRS Database Export for Supabase Import\n");
    fwrite($handle, "-- Generated on: " . date('Y-m-d H:i:s') . "\n\n");
    
    foreach ($tables as $table) {
        echo "📤 Exporting table: {$table}\n";
        
        // Get table structure
        $createTable = $mysql->query("SHOW CREATE TABLE {$table}")->fetch(PDO::FETCH_ASSOC);
        $createSQL = $createTable['Create Table'];
        
        // Convert MySQL syntax to PostgreSQL
        $createSQL = convertMySQLToPostgreSQL($createSQL, $table);
        
        fwrite($handle, "-- Table: {$table}\n");
        fwrite($handle, $createSQL . "\n\n");
        
        // Get table data
        $rows = $mysql->query("SELECT * FROM {$table}")->fetchAll(PDO::FETCH_ASSOC);
        
        if (!empty($rows)) {
            $columns = array_keys($rows[0]);
            $columnsList = implode(', ', array_map(function($col) { return '"' . $col . '"'; }, $columns));
            
            foreach ($rows as $row) {
                $values = array_map(function($val) {
                    if ($val === null) return 'NULL';
                    if (is_string($val)) return "'" . str_replace("'", "''", $val) . "'";
                    return $val;
                }, array_values($row));
                
                $valuesStr = implode(', ', $values);
                fwrite($handle, "INSERT INTO \"{$table}\" ({$columnsList}) VALUES ({$valuesStr});\n");
            }
            fwrite($handle, "\n");
        }
    }
    
    fclose($handle);
    
    echo "✅ Export completed! File saved as: {$exportFile}\n";
    echo "📋 Next steps:\n";
    echo "1. Create Supabase project\n";
    echo "2. Run the SQL in Supabase SQL Editor\n";
    echo "3. Update .env with Supabase credentials\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

function convertMySQLToPostgreSQL($createSQL, $tableName) {
    // Basic MySQL to PostgreSQL conversion
    $createSQL = str_replace('`', '"', $createSQL);
    $createSQL = str_replace('AUTO_INCREMENT', '', $createSQL);
    $createSQL = str_replace('ENGINE=InnoDB', '', $createSQL);
    $createSQL = str_replace('DEFAULT CHARSET=utf8mb4', '', $createSQL);
    $createSQL = str_replace('COLLATE=utf8mb4_unicode_ci', '', $createSQL);
    
    // Data type conversions
    $createSQL = preg_replace('/bigint\(\d+\)/', 'BIGINT', $createSQL);
    $createSQL = preg_replace('/int\(\d+\)/', 'INTEGER', $createSQL);
    $createSQL = preg_replace('/varchar\((\d+)\)/', 'VARCHAR($1)', $createSQL);
    $createSQL = str_replace('datetime', 'TIMESTAMP', $createSQL);
    $createSQL = str_replace('tinyint(1)', 'BOOLEAN', $createSQL);
    
    return $createSQL;
}
?>