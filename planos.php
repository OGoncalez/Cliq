<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CLIQ - Planos</title>
    <link rel="stylesheet" href="planos.css">
    <link rel="stylesheet" href="home.css">
</head>
<body>
    <nav class="navbar">
        <img src="midias\LOGO CLIQ DEFINITIVA.png" class="logo" alt="Logo Cliq">
        <div class="nav-list">
        </div>
    </nav>

    <main class="main-content">
        <div class="pricing-cards">
            <!-- Padrão -->
            <a href="checkout.php?plan=padrao" class="card-link">
                <div class="pricing-card">
                    <div class="plan-header">
                        <h2 class="plan-name">Padrão</h2>
                    </div>
                    <div class="plan-body">
                        <p class="plan-price">R$29,99 p/mês</p>
                        <ul class="plan-features">
                            <li>4 telas</li>
                            <li>1080p (Full HD)</li>
                            <li>Sem anúncios</li>
                        </ul>
                    </div>
                </div>
            </a>

            <!-- Super -->
            <a href="checkout.php?plan=super" class="card-link">
                <div class="pricing-card">
                    <div class="plan-header">
                        <h2 class="plan-name">Super</h2>
                    </div>
                    <div class="plan-body">
                        <p class="plan-price">R$49,99 p/mês</p>
                        <ul class="plan-features">
                            <li>4 telas</li>
                            <li>2K (Quad HD)</li>
                            <li>Sem anúncios</li>
                        </ul>
                    </div>
                </div>
            </a>

            <!-- VIP -->
            <a href="checkout.php?plan=vip" class="card-link">
                <div class="pricing-card">
                    <div class="plan-header">
                        <h2 class="plan-name">VIP</h2>
                    </div>
                    <div class="plan-body">
                        <p class="plan-price">R$69,99 p/mês</p>
                        <ul class="plan-features">
                            <li>4 telas</li>
                            <li>4K (Ultra HD)</li>
                            <li>Sem anúncios</li>
                        </ul>
                    </div>
                </div>
            </a>
        </div>
    </main>

    <footer class="footer">
        <p>Cliq© Todos os direitos reservados</p>
    </footer>
</body>
</html>
