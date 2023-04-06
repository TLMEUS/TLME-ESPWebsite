<?php
/**
 * This file contains the C:/TLME/Projects/ESP/TLME-ESP-Website/Core/Error.php class for the TLME-Framework
 *
 * PHP Version: 8.2
 *
 * @author troylmarker
 * @version 1.0
 * @since 2023-3-23
 */

namespace Core;

/**
 * Import needed classes
 */
use App\Config;
use ErrorException;
use Exception;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Error and exception handler
 */
class Error
{

    /**
     * Error handler. Convert all errors to Exceptions by throwing an ErrorException.
     *
     * @param int $level Error level
     * @param string $message Error message
     * @param string $file Filename the error was raised in
     * @param int $line Line number in the file
     * @return void
     * @throws ErrorException
     */
    public static function errorHandler(int $level, string $message, string $file, int $line)
    {
        if (error_reporting() !== 0) {  // to keep the @ operator working
            throw new ErrorException($message, 0, $level, $file, $line);
        }
    }

    /**
     *  Exception handler.
     *
     * @param Exception $exception The exception
     * @return void
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public static function exceptionHandler($exception)
    {
        $code = $exception->getCode();
        http_response_code($code);
        if (Config::SHOW_ERRORS) {
            echo "<h1>Fatal error</h1>";
            echo "<p>Uncaught exception: '" . get_class($exception) . "'</p>";
            echo "<p>Message: '" . $exception->getMessage() . "'</p>";
            echo "<p>Stack trace:<pre>" . $exception->getTraceAsString() . "</pre></p>";
            echo "<p>Thrown in '" . $exception->getFile() . "' on line " . $exception->getLine() . "</p>";
        } else {
            $log = dirname(__DIR__) . '/logs/' . date('Y-m-d') . '.log';
            ini_set('error_log', $log);
            $message = "Uncaught exception: '" . get_class($exception) . "'";
            $message .= " with message '" . $exception->getMessage() . "'";
            $message .= "\nStack trace: " . $exception->getTraceAsString();
            $message .= "\nThrown in '" . $exception->getFile() . "' on line " . $exception->getLine();
            $message .= "\n--------------------------------------------------------------------------------------------------------------------";
            error_log($message);
            View::render("$code.twig");
        }
    }
}