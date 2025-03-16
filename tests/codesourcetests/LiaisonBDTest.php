<?php

use PHPUnit\Framework\TestCase;

require "fonction/liaisonBD.php";

class LiaisonBDTest extends TestCase {
    
    public function testConnexionReussie() {
        try {
            $pdo = connecteBD();
            $this->assertInstanceOf(PDO::class, $pdo, "L'objet retourné doit être une instance de PDO.");
        } catch (Exception $e) {
            $this->fail("La connexion à la base de données a échoué : " . $e->getMessage());
        }
    }
    
    public function testConnexionEchoueAvecMauvaisIdentifiants() {
        $this->expectException(PDOException::class);
        
        $host = 'localhost';
        $db = 'test_inexistant';
        $user = 'mauvais_user';
        $pass = 'mauvais_pass';
        $charset = 'utf8mb4';
        $port = 3306;
        
        $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_EMULATE_PREPARES => false
        ];
        
        new PDO($dsn, $user, $pass, $options);
    }
}
?>
