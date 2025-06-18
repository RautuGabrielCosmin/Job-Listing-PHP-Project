<?php

namespace Framework\Middleware {
    function redirect($url)
    {
        throw new \RuntimeException("Redirect to $url");
    }
}
