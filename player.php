<?php
require 'session.php';
require 'flash.php';

// Verificar autenticação
if (empty($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

// Obter parâmetros - SEM urldecode no título
$video_url = $_GET['video'] ?? '';
$title = $_GET['title'] ?? 'CLIQ Player';
$type = $_GET['type'] ?? 'movie';

if (empty($video_url)) {
    die("URL de vídeo não fornecida.");
}

// Converter URLs do YouTube para embed
if (strpos($video_url, 'youtube.com') !== false || strpos($video_url, 'youtu.be') !== false) {
    if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $video_url, $matches)) {
        $video_url = "https://www.youtube.com/embed/{$matches[1]}?autoplay=1&rel=0";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?> - CLIQ</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            background: #000;
            color: white;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            height: 100vh;
            overflow: hidden;
        }

        /* Player ocupa toda a tela */
        .player-fullscreen {
            width: 100%;
            height: 100vh;
            position: relative;
        }

        /* Header fixo no topo */
        .player-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: rgba(58, 12, 58, 0.9);
            padding: 15px 20px;
            display: flex;
            align-items: center;
            gap: 20px;
            border-bottom: 2px solid yellow;
            z-index: 1000;
        }

        .back-btn {
            background: #8e24aa;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.3s;
        }

        .back-btn:hover {
            background: #a83cb3;
        }

        #playerTitle {
            color: #eb6ad1;
            margin: 0;
        }

        /* Iframe ocupa toda a área disponível */
        .video-container {
            width: 100%;
            height: 100vh;
            padding-top: 70px; /* Compensa o header */
        }

        #mainPlayer {
            width: 100%;
            height: 100%;
            border: none;
        }

        /* Footer escondido inicialmente */
        .footer {
            display: none;
            background: #1a0a1a;
            color: white;
            padding: 40px 0 20px;
            position: relative;
            z-index: 100;
        }

        .footer-visible {
            display: block;
        }
    </style>
</head>
<body>
    <div class="player-fullscreen">
        <div class="player-header">
            <a href="home.php" class="back-btn">← Voltar</a>
            <h1 id="playerTitle"><?php echo htmlspecialchars($title); ?></h1>
        </div>
        
        <div class="video-container">
            <iframe id="mainPlayer" 
                    src="<?php echo htmlspecialchars($video_url); ?>" 
                    frameborder="0" 
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                    allowfullscreen>
            </iframe>
        </div>
    </div>

    <!-- Footer (copiado da home) -->
    <footer class="footer" id="pageFooter">
        <div class="footer-content">
            <div class="footer-section">
                <div class="footer-logo">Cliq</div>
                <p class="footer-description">
                    Sua plataforma de streaming favorita com os melhores filmes e séries. 
                    Assista onde quiser, quando quiser.
                </p>
                <div class="social-links">
                    <a href="#" class="social-btn" title="Facebook">FB</a>
                    <a href="#" class="social-btn" title="Instagram">IG</a>
                    <a href="#" class="social-btn" title="YouTube">YT</a>
                    <a href="#" class="social-btn" title="TikTok">TT</a>
                </div>
            </div>
            <div class="footer-section">
                <h3>Navegação</h3>
                <ul class="footer-links">
                    <li><a href="home.php">Home</a></li>
                    <li><a href="kids.php">Infantil</a></li>
                    <li><a href="perfil.php">Perfil</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Empresa</h3>
                <ul class="footer-links">
                    <li><a href="sobre.php">Sobre Nós</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© 2025 CLIQ Streaming. Todos os direitos reservados.</p>
        </div>
    </footer>

    <script>
        // Mostrar footer apenas quando houver scroll
        let footerShown = false;
        
        document.addEventListener('wheel', function(e) {
            if (!footerShown && e.deltaY > 0) {
                // Primeiro scroll para baixo - mostrar footer
                document.getElementById('pageFooter').classList.add('footer-visible');
                footerShown = true;
                
                // Habilitar scroll na página
                document.body.style.overflowY = 'auto';
            }
        });

        // Voltar com ESC
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                window.location.href = 'home.php';
            }
        });

        // Focar no player
        document.getElementById('mainPlayer').focus();
    </script>
</body>
</html>