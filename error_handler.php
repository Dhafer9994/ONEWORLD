<?php
// Register error handler
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    if (!(error_reporting() & $errno)) {
        return;
    }
    
    $errorTypes = [
        E_ERROR => 'ERROR',
        E_WARNING => 'WARNING',
        E_PARSE => 'PARSE',
        E_NOTICE => 'NOTICE',
        E_CORE_ERROR => 'CORE_ERROR',
        E_CORE_WARNING => 'CORE_WARNING',
        E_COMPILE_ERROR => 'COMPILE_ERROR',
        E_COMPILE_WARNING => 'COMPILE_WARNING',
        E_USER_ERROR => 'USER_ERROR',
        E_USER_WARNING => 'USER_WARNING',
        E_USER_NOTICE => 'USER_NOTICE',
        E_STRICT => 'STRICT',
        E_RECOVERABLE_ERROR => 'RECOVERABLE_ERROR',
        E_DEPRECATED => 'DEPRECATED',
        E_USER_DEPRECATED => 'USER_DEPRECATED'
    ];
    
    $type = $errorTypes[$errno] ?? 'UNKNOWN';
    $message = "[$type] $errstr in $errfile on line $errline";
    
    if (class_exists('Security')) {
        Security::logError($message, $type);
    } else {
        error_log($message);
    }
    
    return true;
});

// Register exception handler
set_exception_handler(function($exception) {
    $message = "[EXCEPTION] " . $exception->getMessage() . " in " . $exception->getFile() . " on line " . $exception->getLine();
    
    if (class_exists('Security')) {
        Security::logError($message, 'EXCEPTION');
    } else {
        error_log($message);
    }
    
    if ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['REMOTE_ADDR'] === '127.0.0.1') {
        die("<h1>Erreur</h1><pre>" . htmlspecialchars($message) . "</pre>");
    } else {
        die("<h1>Une erreur s'est produite</h1><p>Veuillez réessayer plus tard.</p>");
    }
});

// Log PHP errors to file
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/logs/php_errors.log');
?>