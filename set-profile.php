<?php
require 'session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    $profile_id = $input['profile_id'] ?? null;
    $profile_name = $input['profile_name'] ?? null;
    
    if ($profile_id && $profile_name) {
        // Salvar perfil ativo na sessão
        $_SESSION['perfil_ativo'] = [
            'id' => $profile_id,
            'nome' => $profile_name
        ];
        
        echo json_encode(['success' => true, 'message' => 'Perfil selecionado com sucesso']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Dados do perfil inválidos']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
}
?>