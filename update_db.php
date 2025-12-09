<?php
require_once 'config.php';

try {
    $pdo = config::getConnexion();
    
  
    try {
        $pdo->exec("ALTER TABLE user ADD COLUMN google_id VARCHAR(255) NULL");
        echo "Column 'google_id' added successfully.<br>";
    } catch (PDOException $e) {
        echo "Column 'google_id' might already exist or error: " . $e->getMessage() . "<br>";
    }

   
    try {
        $pdo->exec("ALTER TABLE user ADD COLUMN facebook_id VARCHAR(255) NULL");
        echo "Column 'facebook_id' added successfully.<br>";
    } catch (PDOException $e) {
        echo "Column 'facebook_id' might already exist or error: " . $e->getMessage() . "<br>";
    }

    echo "Database update completed.";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
