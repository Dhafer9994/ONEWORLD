<?php
if (!class_exists('config')) {
    class config
    {
        private static $pdo = null;

        public static function getConnexion()
        {
            // Ensure timezone is set
            date_default_timezone_set('Africa/Tunis');

            if (!isset(self::$pdo)) {
                try {
                    self::$pdo = new PDO(
                        'mysql:host=localhost;dbname=oneworld',
                        'root',
                        '',
                        [
                            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                        ]
                    );
                } catch (Exception $e) {
                    die('Erreur: ' . $e->getMessage());
                }
            }
            return self::$pdo;
        }
    }
}
