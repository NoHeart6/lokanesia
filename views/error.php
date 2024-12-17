<?php
$message = $message ?? 'Terjadi kesalahan pada server';
$debug = $debug ?? false;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error - Lokanesia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        }
        .error-container {
            text-align: center;
            color: white;
            padding: 2rem;
            border-radius: 1rem;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            max-width: 600px;
            width: 90%;
        }
        .error-code {
            font-size: 5rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }
        .error-message {
            font-size: 1.2rem;
            margin-bottom: 2rem;
        }
        .home-button {
            background: white;
            color: #1e3c72;
            padding: 0.8rem 2rem;
            border-radius: 2rem;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s;
        }
        .home-button:hover {
            background: #f8f9fa;
            transform: translateY(-2px);
        }
        .debug-info {
            margin-top: 2rem;
            text-align: left;
            background: rgba(0, 0, 0, 0.2);
            padding: 1rem;
            border-radius: 0.5rem;
            font-family: monospace;
            white-space: pre-wrap;
            word-break: break-all;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-code">500</div>
        <div class="error-message"><?php echo htmlspecialchars($message); ?></div>
        <a href="/" class="home-button">Kembali ke Beranda</a>
        <?php if ($debug && isset($e)): ?>
        <div class="debug-info">
            <?php 
            echo "Error: " . htmlspecialchars($e->getMessage()) . "\n";
            echo "File: " . htmlspecialchars($e->getFile()) . "\n";
            echo "Line: " . $e->getLine() . "\n";
            echo "Trace:\n" . htmlspecialchars($e->getTraceAsString());
            ?>
        </div>
        <?php endif; ?>
    </div>
</body>
</html> 