<?php
// formation_apprentissage/includes/db_connect.php

/**
 * Connexion PDO avec gestion d'erreurs et fonctionnalités avancées
 */

class DB {
    private static $instance = null;
    private $pdo;
    private $error;
    private $queryCount = 0;
    private $queries = [];
    
    private function __construct() {
        try {
            $this->pdo = new PDO(
                'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::ATTR_PERSISTENT => false,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
                ]
            );
            
            // Définir le fuseau horaire
            $this->pdo->exec("SET time_zone = '+01:00'");
            
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            error_log('DB Connection Error: ' . $this->error);
            
            // Afficher une erreur conviviale en production
            if (ENVIRONMENT === 'production') {
                die('Une erreur de connexion à la base de données est survenue. Veuillez réessayer plus tard.');
            } else {
                die('Erreur de connexion DB: ' . $this->error);
            }
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new DB();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->pdo;
    }
    
    /**
     * Exécute une requête SELECT
     */
    public function select($sql, $params = []) {
        $start = microtime(true);
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            
            $this->logQuery($sql, $params, microtime(true) - $start);
            
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            $this->handleError($e, $sql, $params);
            return false;
        }
    }
    
    /**
     * Exécute une requête SELECT et retourne une seule ligne
     */
    public function selectOne($sql, $params = []) {
        $start = microtime(true);
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            
            $this->logQuery($sql, $params, microtime(true) - $start);
            
            return $stmt->fetch();
        } catch (PDOException $e) {
            $this->handleError($e, $sql, $params);
            return false;
        }
    }
    
    /**
     * Exécute une requête INSERT
     */
    public function insert($table, $data) {
        $start = microtime(true);
        
        try {
            $columns = implode(', ', array_keys($data));
            $placeholders = ':' . implode(', :', array_keys($data));
            
            $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
            $stmt = $this->pdo->prepare($sql);
            
            foreach ($data as $key => $value) {
                $stmt->bindValue(':' . $key, $value);
            }
            
            $result = $stmt->execute();
            
            $this->logQuery($sql, $data, microtime(true) - $start);
            
            if ($result) {
                return $this->pdo->lastInsertId();
            }
            
            return false;
        } catch (PDOException $e) {
            $this->handleError($e, $sql ?? '', $data);
            return false;
        }
    }
    
    /**
     * Exécute une requête UPDATE
     */
    public function update($table, $data, $where, $whereParams = []) {
        $start = microtime(true);
        
        try {
            $setParts = [];
            foreach (array_keys($data) as $column) {
                $setParts[] = "$column = :$column";
            }
            $setClause = implode(', ', $setParts);
            
            $sql = "UPDATE $table SET $setClause WHERE $where";
            $stmt = $this->pdo->prepare($sql);
            
            // Bind les valeurs SET
            foreach ($data as $key => $value) {
                $stmt->bindValue(':' . $key, $value);
            }
            
            // Bind les valeurs WHERE
            foreach ($whereParams as $key => $value) {
                $stmt->bindValue(':' . $key, $value);
            }
            
            $result = $stmt->execute();
            
            $this->logQuery($sql, array_merge($data, $whereParams), microtime(true) - $start);
            
            return $result ? $stmt->rowCount() : false;
        } catch (PDOException $e) {
            $this->handleError($e, $sql ?? '', array_merge($data, $whereParams));
            return false;
        }
    }
    
    /**
     * Exécute une requête DELETE
     */
    public function delete($table, $where, $params = []) {
        $start = microtime(true);
        
        try {
            $sql = "DELETE FROM $table WHERE $where";
            $stmt = $this->pdo->prepare($sql);
            
            foreach ($params as $key => $value) {
                $stmt->bindValue(':' . $key, $value);
            }
            
            $result = $stmt->execute();
            
            $this->logQuery($sql, $params, microtime(true) - $start);
            
            return $result ? $stmt->rowCount() : false;
        } catch (PDOException $e) {
            $this->handleError($e, $sql, $params);
            return false;
        }
    }
    
    /**
     * Exécute une requête personnalisée
     */
    public function query($sql, $params = []) {
        $start = microtime(true);
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute($params);
            
            $this->logQuery($sql, $params, microtime(true) - $start);
            
            return $stmt;
        } catch (PDOException $e) {
            $this->handleError($e, $sql, $params);
            return false;
        }
    }
    
    /**
     * Démarre une transaction
     */
    public function beginTransaction() {
        return $this->pdo->beginTransaction();
    }
    
    /**
     * Valide une transaction
     */
    public function commit() {
        return $this->pdo->commit();
    }
    
    /**
     * Annule une transaction
     */
    public function rollBack() {
        return $this->pdo->rollBack();
    }
    
    /**
     * Retourne le nombre de requêtes exécutées
     */
    public function getQueryCount() {
        return $this->queryCount;
    }
    
    /**
     * Retourne les requêtes exécutées (pour débogage)
     */
    public function getQueries() {
        return $this->queries;
    }
    
    /**
     * Échappe une chaîne pour SQL
     */
    public function quote($string) {
        return $this->pdo->quote($string);
    }
    
    /**
     * Vérifie si une table existe
     */
    public function tableExists($tableName) {
        try {
            $result = $this->selectOne(
                "SELECT COUNT(*) as count 
                 FROM information_schema.tables 
                 WHERE table_schema = ? 
                 AND table_name = ?",
                [DB_NAME, $tableName]
            );
            return $result && $result['count'] > 0;
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Journalise une requête
     */
    private function logQuery($sql, $params, $executionTime) {
        $this->queryCount++;
        
        if (ENVIRONMENT === 'development') {
            $this->queries[] = [
                'sql' => $sql,
                'params' => $params,
                'time' => round($executionTime * 1000, 2) . 'ms'
            ];
        }
    }
    
    /**
     * Gère les erreurs PDO
     */
    private function handleError($e, $sql, $params) {
        $this->error = $e->getMessage();
        
        // Journaliser l'erreur
        error_log('DB Error: ' . $this->error);
        error_log('SQL: ' . $sql);
        error_log('Params: ' . print_r($params, true));
        
        // En mode développement, afficher l'erreur
        if (ENVIRONMENT === 'development') {
            die('DB Error: ' . $this->error . '<br>SQL: ' . $sql);
        }
    }
    
    /**
     * Retourne la dernière erreur
     */
    public function getError() {
        return $this->error;
    }
}

// Constantes de configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'oneworld');
define('DB_USER', 'root');
define('DB_PASS', '');
define('ENVIRONMENT', 'development'); // development | production

// Initialiser la connexion
function db() {
    return DB::getInstance();
}
?>