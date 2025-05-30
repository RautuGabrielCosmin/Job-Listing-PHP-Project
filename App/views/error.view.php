<?php
http_response_code(404);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>404 – Page Not Found</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            height: 100%;
            font-family: sans-serif;
            background: #f4f4f4;
        }

        .wrapper {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100%;
            text-align: center;
            color: #333;
        }

        h1 {
            font-size: 6rem;
            margin-bottom: 0.5rem;
        }

        p {
            font-size: 1.25rem;
            margin-bottom: 1.5rem;
        }

        button {
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            border: none;
            background: #007BFF;
            color: white;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.2s ease;
        }

        button:hover {
            background: #0056b3;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <h1><?= $status ?></h1>
        <p><?= $message ?></p>
        <button id="go-home">Go Home</button>
    </div>

    <script>
        document.getElementById('go-home').addEventListener('click', function() {
            window.location.href = '/';
        });
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                window.location.href = '/';
            }
        });
    </script>
</body>

</html>