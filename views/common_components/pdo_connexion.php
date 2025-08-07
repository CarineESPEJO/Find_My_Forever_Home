<?php
try {
    $pdo = new PDO("mysql:host=localhost;port=3306;dbname=FMDH_DB", "admin", "mdp");
    
    // Set error mode to exception for better error handling
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Connection to the database was successful!";
} catch (PDOException $e) {
    echo "❌ Connection to the database failed: " . $e->getMessage();
}
