<?php
header('Content-Type: application/json');

// Configurações de segurança
$maxFileSize = 5 * 1024 * 1024; // 5MB
$allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
$uploadDir = 'uploads/';

// Criar diretório se não existir
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Verificar se o arquivo foi enviado
if (!isset($_FILES['profileImage']) || $_FILES['profileImage']['error'] === UPLOAD_ERR_NO_FILE) {
    echo json_encode(['success' => false, 'message' => 'Nenhum arquivo enviado']);
    exit;
}

$file = $_FILES['profileImage'];

// Verificar erros de upload
if ($file['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'message' => 'Erro ao fazer upload do arquivo']);
    exit;
}

// Verificar tamanho do arquivo
if ($file['size'] > $maxFileSize) {
    echo json_encode(['success' => false, 'message' => 'Arquivo muito grande. Máximo: 5MB']);
    exit;
}

// Verificar tipo do arquivo
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = finfo_file($finfo, $file['tmp_name']);
finfo_close($finfo);

if (!in_array($mimeType, $allowedTypes)) {
    echo json_encode(['success' => false, 'message' => 'Tipo de arquivo não permitido']);
    exit;
}

// Gerar nome único para o arquivo
$extension = pathinfo($file['name'], PATHINFO_EXTENSION);
$newFileName = 'profile_' . uniqid() . '_' . time() . '.' . $extension;
$targetPath = $uploadDir . $newFileName;

// Mover arquivo para o diretório de uploads
if (move_uploaded_file($file['tmp_name'], $targetPath)) {
    echo json_encode([
        'success' => true,
        'message' => 'Upload realizado com sucesso',
        'filePath' => $targetPath
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao salvar o arquivo']);
}
?>
