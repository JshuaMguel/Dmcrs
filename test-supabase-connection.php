<?php
echo "🔧 Testing Supabase PostgreSQL Connection...\n";

try {
    $pdo = new PDO(
        'pgsql:host=db.pnlfehzvhwnprfbdfip.supabase.co;port=5432;dbname=postgres', 
        'postgres', 
        'l7LgCClDLFEH6blx'
    );
    
    echo "✅ SUCCESS: Connected to Supabase PostgreSQL!\n";
    echo "📊 Server Version: " . $pdo->getAttribute(PDO::ATTR_SERVER_VERSION) . "\n";
    
    // Test a simple query
    $stmt = $pdo->query("SELECT version()");
    $version = $stmt->fetchColumn();
    echo "🗄️  Database: " . $version . "\n";
    
    echo "\n🎉 Connection test PASSED! Ready to migrate data.\n";
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "\n🔧 Troubleshooting:\n";
    echo "1. Check if PostgreSQL extensions are enabled\n";
    echo "2. Verify Supabase credentials\n";
    echo "3. Check internet connection\n";
}
?>