<?php
/**
 * An example of a project-specific implementation.
 *
 * After registering this autoload function with SPL, the following line
 * would cause the function to attempt to load the \Foo\Bar\Baz\Qux class
 * from /path/to/project/src/Baz/Qux.php:
 *
 *      new \Foo\Bar\Baz\Qux;
 *
 * @param string $class The fully-qualified class name.
 * @return void
 */
spl_autoload_register(function ($class) {
    $baseDir = __DIR__ . '/src/';

    $file = $baseDir . str_replace('\\', '/', $class) . '.php';

    error_log("Autoloader sucht Klasse: {$class}");
    error_log("Erwarteter Dateipfad: {$file}");

    if (file_exists($file)) {
        require_once $file;
        error_log("Datei gefunden und eingebunden: {$file}");
    } else {
        error_log("Datei nicht gefunden: {$file}");
    }
});
