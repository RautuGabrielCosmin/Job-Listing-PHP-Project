<?php

namespace App\Controllers {
    function redirect($url)
    {
        throw new \RuntimeException("Redirect to $url");
    }
}
