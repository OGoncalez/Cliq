<?php
session_start();

require 'session.php';
require 'flash.php';
require 'conexao.php';

if (!isset($conn)) {
    die("Erro: Conexão com banco de dados não encontrada. Verifique o arquivo conexao.php");
}

// Check if user is logged in
if (!isset($_SESSION['usuario'])) {
    set_flash('Erro', 'Você precisa estar logado para realizar o pagamento.');
    header('Location: login.php');
    exit();
}

// Validate form submission
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: planos.php');
    exit();
}

$userId = $_SESSION['usuario']['id'];
$plan = $_POST['plan'] ?? 'padrao';
$cardNumber = $_POST['card_number'] ?? '';
$expiryDate = $_POST['expiry_date'] ?? '';
$cvv = $_POST['cvv'] ?? '';
$fullName = $_POST['full_name'] ?? '';
$cpf = $_POST['cpf'] ?? '';

// Validate required fields
if (empty($cardNumber) || empty($expiryDate) || empty($cvv) || empty($fullName) || empty($cpf)) {
    set_flash('Erro', 'Todos os campos são obrigatórios.');
    header('Location: checkout.php?plan=' . $plan);
    exit();
}

// Define plan prices
$planPrices = [
    'padrao' => 24.99,
    'super' => 49.99,
    'vip' => 69.99
];

$price = $planPrices[$plan] ?? 24.99;

// Check if user already has an active subscription
$stmt = $conn->prepare("SELECT id FROM assinaturas WHERE usuario_id = ? AND status = 'ativa'");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    set_flash('Erro', 'Você já possui uma assinatura ativa.');
    header('Location: home.php');
    exit();
}

// In a real application, you would process the payment with a payment gateway here
// For now, we'll simulate a successful payment

// Calculate expiration date (30 days from now)
$dataExpiracao = date('Y-m-d H:i:s', strtotime('+30 days'));

// Insert subscription into database
$stmt = $conn->prepare("INSERT INTO assinaturas (usuario_id, plano, preco, status, data_expiracao) VALUES (?, ?, ?, 'ativa', ?)");
$stmt->bind_param("isds", $userId, $plan, $price, $dataExpiracao);

if ($stmt->execute()) {
    set_flash('Sucesso', 'Pagamento realizado com sucesso! Sua assinatura está ativa.');
    header('Location: home.php');
    exit();
} else {
    set_flash('Erro', 'Erro ao processar pagamento. Tente novamente.');
    header('Location: checkout.php?plan=' . $plan);
    exit();
}
?>
