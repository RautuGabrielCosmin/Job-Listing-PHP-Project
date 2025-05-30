<?php
// send the 403 Forbidden header
http_response_code(403);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>403 – Forbidden</title>
    <style>
        /* full-screen flex centering */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #2a2a72, #009ffd);
            color: #fff;
            font-family: 'Segoe UI', sans-serif;
            text-align: center;
        }

        .container {
            max-width: 400px;
        }

        .lock {
            font-size: 5rem;
            animation: pop 1.5s ease-in-out infinite;
            cursor: pointer;
            user-select: none;
        }

        h1 {
            font-size: 2.5rem;
            margin: 0.5rem 0;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        p {
            margin-bottom: 1.5rem;
            line-height: 1.4;
        }

        button {
            background: #fff;
            color: #2a2a72;
            border: none;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            border-radius: 4px;
            cursor: pointer;
            transition: transform 0.2s, background 0.2s;
        }

        button:hover {
            transform: scale(1.05);
            background: #f0f0f0;
        }

        .countdown {
            margin-top: 1rem;
            font-size: 0.9rem;
            opacity: 0.8;
        }

        @keyframes pop {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.2) rotate(-5deg);
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="lock" id="lock">🔒</div>
        <h1>403 – Forbidden</h1>
        <p>Sorry, you don’t have permission to view this page.</p>
        <button id="homeBtn">Go Home</button>
        <button id="backBtn">Go Back</button>
        <div class="countdown" id="countdown">Redirecting home in <span id="secs">5</span> seconds…</div>
    </div>

    <script>
        // button behavior
        document.getElementById('homeBtn').onclick = () => {
            window.location.href = '/';
        };
        document.getElementById('backBtn').onclick = () => {
            history.back();
        };

        // allow clicking the lock to shake it
        const lock = document.getElementById('lock');
        lock.onclick = () => {
            lock.style.animation = 'shake 0.5s';
            lock.onanimationend = () => lock.style.animation = 'pop 1.5s infinite';
        };

        // countdown + auto‐redirect
        let seconds = 5;
        const secsEl = document.getElementById('secs');
        const timer = setInterval(() => {
            seconds--;
            if (seconds <= 0) {
                clearInterval(timer);
                window.location.href = '/';
            } else {
                secsEl.textContent = seconds;
            }
        }, 1000);

        // shake keyframes injected via JS
        const style = document.createElement('style');
        style.textContent = `
      @keyframes shake {
        0%,100% { transform: translateX(0) rotate(0); }
        25%     { transform: translateX(-10px) rotate(-10deg); }
        50%     { transform: translateX(10px) rotate(10deg); }
        75%     { transform: translateX(-10px) rotate(-10deg); }
      }
    `;
        document.head.appendChild(style);
    </script>
</body>

</html>