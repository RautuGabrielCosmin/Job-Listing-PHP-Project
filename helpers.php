<?php

/**
 * Get the base path
 * 
 * @param string $path
 * @return string
 */
function basePath($path = '')
{
    return __DIR__ . '/' . $path;
} //end basePath($path = '')

/**
 * Load a view.
 * 
 * @param string $name
 * @return void
 */
function loadView($name, $data = [])
{
    $viewPath = basePath("views/{$name}.view.php");
    if (file_exists($viewPath)) { //if the file exists load the file
        extract($data);
        require $viewPath;
    } else { //else if the file does not exists "throw" this error
        echo "View '{$name} not found!'";
    }
} //end of loadView($name)

/**
 * Load a partial for the webpage.
 * 
 * @param string $name
 * @return void
 */
function loadPartial($name)
{
    $partialPath = basePath("views/partials/{$name}.php");
    if (file_exists($partialPath)) { //if the file exists load the file
        require $partialPath;
    } else { //else if the file does not exists "throw" this error
        echo "View '{$name} not found!'";
    }
} //end of loadPartial($name)

/**
 * Inspect a parameter
 * 
 * @param mixed $value
 * @return void
 */
function inspect($value)
{
    echo "<pre>";
    var_dump($value);
    echo "</pre>";
} //end of inspedt($value)

/**
 * Inspect a parameter and terminate the rest of the code for visibility
 * 
 * @param mixed $value
 * @return void
 */
function inspectAndDie($value)
{
    echo "<pre>";
    var_dump($value);
    echo "</pre>";
    die();
}//end of inspectAndDie($value)
