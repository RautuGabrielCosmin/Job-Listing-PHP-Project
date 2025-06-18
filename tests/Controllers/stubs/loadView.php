<?php

namespace App\Controllers {
    function loadView($name, $data = [])
    {
        echo json_encode([
            'view' => $name,
            'data' => $data
        ]);
    }
}
