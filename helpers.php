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
    $viewPath = basePath("App/views/{$name}.view.php");
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
function loadPartial($name, $data = [])
{
    $partialPath = basePath("App/views/partials/{$name}.php");
    if (file_exists($partialPath)) { //if the file exists load the file
        extract($data);
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
} //end of inspectAndDie($value)

/**
 * Format the salary
 * 
 * @param string salary
 * @return string formatted Salary
 */
function formatSalary($salary)
{
    return '$' . number_format(floatval(($salary)), 2, '.', '.');
} //end of formatSalary($salary)

/**
 * Sanitize Data
 * doesn't allow html input into the form(create job form)
 * @param string $input
 * @return string
 */
function sanitize($input)
{
    return filter_var(trim($input), FILTER_SANITIZE_SPECIAL_CHARS);
} //end of sanitize($input)


/**
 * Redirect to a given url
 * 
 * @param string $url
 * @return void
 */
function redirect($url)
{
    header("Location: {$url}");
    exit;
}//end of redirect($url)
