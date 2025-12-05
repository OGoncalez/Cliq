<?php
header('Content-Type: application/json');

$dataFile = 'data/profiles.json';

// Criar diretório se não existir
if (!file_exists('data')) {
    mkdir('data', 0755, true);
}

// Carregar perfis existentes
$profiles = [];
if (file_exists($dataFile)) {
    $profilesJson = file_get_contents($dataFile);
    $profiles = json_decode($profilesJson, true);
    if (!$profiles) {
        $profiles = [];
    }
}

// Obter dados do POST
$profileId = isset($_POST['profileId']) ? intval($_POST['profileId']) : 0;
$profileName = isset($_POST['profileName']) ? trim($_POST['profileName']) : '';
$imagePath = isset($_POST['imagePath']) ? trim($_POST['imagePath']) : '';

// Validar nome
if (empty($profileName)) {
    echo json_encode(['success' => false, 'message' => 'Nome do perfil é obrigatório']);
    exit;
}

if (strlen($profileName) > 50) {
    echo json_encode(['success' => false, 'message' => 'Nome muito longo (máx. 50 caracteres)']);
    exit;
}

// Sanitizar dados
$profileName = htmlspecialchars($profileName, ENT_QUOTES, 'UTF-8');

if ($profileId > 0) {
    // Atualizar perfil existente
    $found = false;
    foreach ($profiles as &$profile) {
        if ($profile['id'] == $profileId) {
            $profile['name'] = $profileName;
            if (!empty($imagePath)) {
                // Deletar imagem antiga se existir e não for a padrão
                if (isset($profile['image']) && 
                    $profile['image'] !== 'uploads/default-avatar.jpg' && 
                    file_exists($profile['image'])) {
                    unlink($profile['image']);
                }
                $profile['image'] = $imagePath;
            }
            $found = true;
            break;
        }
    }
    
    if (!$found) {
        echo json_encode(['success' => false, 'message' => 'Perfil não encontrado']);
        exit;
    }
} else {
    // Criar novo perfil
    $newId = 1;
    foreach ($profiles as $profile) {
        if ($profile['id'] >= $newId) {
            $newId = $profile['id'] + 1;
        }
    }
    
    $newProfile = [
        'id' => $newId,
        'name' => $profileName,
        'image' => !empty($imagePath) ? $imagePath : 'uploads/default-avatar.jpg'
    ];
    
    $profiles[] = $newProfile;
}

// Salvar perfis
if (file_put_contents($dataFile, json_encode($profiles, JSON_PRETTY_PRINT))) {
    echo json_encode(['success' => true, 'message' => 'Perfil salvo com sucesso', 'profiles' => $profiles]);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao salvar perfil']);
}
?>
