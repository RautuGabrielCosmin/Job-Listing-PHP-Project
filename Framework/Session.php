<?php

namespace Framework;

class Session
{
    /**
     * Start the session
     * 
     * @return void
     */
    public static function startSession()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Set a session ket/value pair
     * 
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Get a session value by the key
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get($key, $default = null)
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : $default;
    }

    /**
     * Check if session if session key exists
     * 
     * @param string $key
     * @return boolean
     */
    public static function check($key)
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Clear a session by key
     * 
     * @param string $key
     * @return void
     */
    public static function clear($key)
    {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    /**
     * Clear all session data
     * 
     * @return void
     */
    public static function clearALL()
    {
        session_unset();
        session_destroy();
    }

    /**
     * Set a flash message
     * 
     * @param string $key
     * @param string $message 
     * @return void
     */
    public static function setFlashMessage($key, $message)
    {
        self::set('flash_' . $key, $message);
    }

    /**
     * Get a flash message and unset
     * 
     * @param string $key 
     * @param mixed $default
     * @return string
     */
    public static function getFlashMessage($key, $default = null)
    {
        $message = self::get('flash_' . $key, $default);
        self::clear('flash_' . $key);
        return $message;
    }
}
