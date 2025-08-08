<?php
try {
    $pdo = new PDO("mysql:host=localhost;port=3306;dbname=FMDH_DB;charset=utf8", "admin", "mdp");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    throw $e; 
}
