<?php
require 'flash.php';
require 'auth.php';

// Verificação de autenticação
if (empty($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

// Verificar se tem perfil selecionado
if (empty($_SESSION['perfil_ativo'])) {
    header('Location: perfil.php');
    exit;
}

// Função CORRIGIDA - sem user_id
function getProfileImage()
{
    // Se já tem imagem na sessão, retorna
    if (isset($_SESSION['perfil_ativo']['imagem'])) {
        return $_SESSION['perfil_ativo']['imagem'];
    }

    // Se não tem, busca no arquivo JSON sem verificar user_id
    $profilesFile = 'data/profiles.json';
    if (file_exists($profilesFile)) {
        $profiles = json_decode(file_get_contents($profilesFile), true) ?: [];
        $perfilAtivoId = $_SESSION['perfil_ativo']['id'];

        foreach ($profiles as $profile) {
            // Busca apenas pelo ID do perfil, sem user_id
            if ($profile['id'] == $perfilAtivoId) {
                // Atualiza a sessão com a imagem encontrada
                $_SESSION['perfil_ativo']['imagem'] = $profile['image'];
                return $profile['image'];
            }
        }
    }

    // Fallback
    return 'uploads/default-avatar.jpg';
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cliq - Streaming de Filmes</title>

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <link rel="stylesheet" href="home.css">
    <link rel="stylesheet" href="sobre.css">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

    </script>
</head>

<body>
    <nav class="navbar">
        <img src="midias\LOGO CLIQ DEFINITIVA.png" class="logo" alt="Logo Cliq">

        <div class="nav-list">
            <a href="home.php">Home</a>
            <a href="kids.php">Infantil</a>

            <!-- Versão SIMPLES da foto do perfil -->
            <div class="profile-simple">
                <a href="perfil.php" class="profile-link">
                    <img src="<?php echo getProfileImage(); ?>"
                        alt="<?php echo htmlspecialchars($_SESSION['perfil_ativo']['nome']); ?>"
                        class="profile-avatar-small" onerror="this.src='uploads/default-avatar.jpg'">
                </a>
            </div>

            <a href="logout.php">Sair</a>
        </div>
    </nav>
    <div class="container">
        <h2>Uma nova forma de assistir, maratonar e se apaixonar por filmes e séries!<br>
        A plataforma de streaming que une tecnologia, diversão e personalidade com o mascote mais carismático da internet: o Cliquetinho!<br><img src="midias\criquetinho.png" class="cliquetinho"><br>
        Cliq é perfeita para quem ama maratonar filmes com estilo e
        personalidade, sem ter que se estressar com anúncios.</h2>
    </div>
    


    <footer class="footer">
        <div class="footer-content">
            <!-- Seção Sobre -->
            <div class="footer-section">
                <div class="footer-logo">Cliq</div>
                <p class="footer-description">
                    Sua plataforma de streaming favorita com os melhores filmes e séries.
                    Assista onde quiser, quando quiser.
                </p>
                <div class="social-links">
                    <a href="#" class="social-btn" title="Facebook">
                        <ion-icon name="logo-facebook"></ion-icon>
                    </a>
                    <a href="#" class="social-btn" title="Instagram">
                        <ion-icon name="logo-instagram"></ion-icon>
                    </a>
                    <a href="#" class="social-btn" title="YouTube">
                        <ion-icon name="logo-youtube"></ion-icon>
                    </a>
                    <a href="#" class="social-btn" title="TikTok">
                        <ion-icon name="logo-tiktok"></ion-icon>
                    </a>
                </div>
            </div>

            <!-- Seção Navegação -->
            <div class="footer-section">
                <h3>Navegação</h3>
                <ul class="footer-links">
                    <li><a href="home.php">Home</a></li>
                    <li><a href="kids.php">Infantil</a></li>
                    <li><a href="perfil.php">Perfil</a></li>
                </ul>
            </div>

            <!-- Seção Suporte -->
            <div class="footer-section">
                <h3>Suporte</h3>
                <ul class="footer-links">
                </ul>
                <button class="chatbot-trigger" onclick="openChatbot()">
                    <ion-icon name="chatbubble-ellipses"></ion-icon>
                    Chat de Suporte
                </button>
            </div>

            <!-- Seção Empresa -->
            <div class="footer-section">
                <h3>Empresa</h3>
                <ul class="footer-links">
                    <li><a href="sobre.php">Sobre Nós</a></li>
                </ul>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <p>© 2025 CLIQ Streaming. Todos os direitos reservados.</p>
    </footer>

    <!-- Chatbot Modal -->
    <div id="chatbotModal" class="chatbot-modal">
        <div class="chatbot-header">
            <h4><img src="midias\criquetinho.png" class="cliquetinho"> Suporte CLIQ</h4>
            <button class="chatbot-close" onclick="closeChatbot()">
                <ion-icon name="close"></ion-icon>
            </button>
        </div>
        <div class="chatbot-body" id="chatbotMessages">
            <div class="chat-message bot-message">
                Olá! Sou o assistente virtual da Cliq, O Cliquetinho!
                Como posso ajudar você hoje?
            </div>
            <div class="chat-message bot-message">
                Escolha uma opção:<br>
                1. Problemas com reprodução<br>
                2. Dúvidas sobre conta<br>
                3. Assinatura e pagamentos<br>
                4. Sugestões de conteúdo<br>
                5. Falar com atendente
            </div>
        </div>
        <div class="chatbot-input">
            <input type="text" id="chatbotInput" placeholder="Digite sua mensagem...">
            <button onclick="sendMessage()">
                <ion-icon name="send"></ion-icon>
            </button>
        </div>
    </div>

</body>
<script src="home.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</html>