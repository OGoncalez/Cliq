<?php
require_once 'conexao.php';

header('Content-Type: application/json');

// Habilitar CORS se necessário
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

$action = $_GET['action'] ?? '';

try {
    switch ($action) {
        case 'list':
            $stmt = $conn->prepare("SELECT id, title, name, year, genre, synopsis, image, category, movie_link, trailer_link FROM movies ORDER BY created_at DESC");
            $stmt->execute();
            $result = $stmt->get_result();
            
            $movies = [];
            while ($row = $result->fetch_assoc()) {
                $movies[] = $row;
            }
            
            echo json_encode($movies);
            break;

        case 'add':
            $title = $_POST['title'] ?? '';
            $name = $_POST['name'] ?? '';
            $year = $_POST['year'] ?? '';
            $genre = $_POST['genre'] ?? '';
            $category = $_POST['category'] ?? 'novidades';
            $synopsis = $_POST['synopsis'] ?? '';
            $movie_link = $_POST['movie_link'] ?? '';
            $trailer_link = $_POST['trailer_link'] ?? '';
            
            // Upload da imagem
            $imagePath = 'uploads/default-movie.jpg';
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'uploads/movies/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $filename = uniqid() . '.' . $extension;
                $imagePath = $uploadDir . $filename;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
                    // Upload bem sucedido
                } else {
                    throw new Exception("Erro ao fazer upload da imagem");
                }
            }
            
            $stmt = $conn->prepare("INSERT INTO movies (title, name, year, genre, category, synopsis, image, movie_link, trailer_link) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssissssss", $title, $name, $year, $genre, $category, $synopsis, $imagePath, $movie_link, $trailer_link);
            
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Filme adicionado com sucesso']);
            } else {
                throw new Exception("Erro ao adicionar filme: " . $stmt->error);
            }
            break;

        case 'update':
            $index = $_POST['index'] ?? '';
            $title = $_POST['title'] ?? '';
            $name = $_POST['name'] ?? '';
            $year = $_POST['year'] ?? '';
            $genre = $_POST['genre'] ?? '';
            $category = $_POST['category'] ?? 'novidades';
            $synopsis = $_POST['synopsis'] ?? '';
            $movie_link = $_POST['movie_link'] ?? '';
            $trailer_link = $_POST['trailer_link'] ?? '';
            
            // Se nova imagem foi enviada
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'uploads/movies/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $filename = uniqid() . '.' . $extension;
                $imagePath = $uploadDir . $filename;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
                    $stmt = $conn->prepare("UPDATE movies SET title=?, name=?, year=?, genre=?, category=?, synopsis=?, image=?, movie_link=?, trailer_link=? WHERE id=?");
                    $stmt->bind_param("ssissssssi", $title, $name, $year, $genre, $category, $synopsis, $imagePath, $movie_link, $trailer_link, $index);
                } else {
                    throw new Exception("Erro ao fazer upload da imagem");
                }
            } else {
                $stmt = $conn->prepare("UPDATE movies SET title=?, name=?, year=?, genre=?, category=?, synopsis=?, movie_link=?, trailer_link=? WHERE id=?");
                $stmt->bind_param("ssisssssi", $title, $name, $year, $genre, $category, $synopsis, $movie_link, $trailer_link, $index);
            }
            
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Filme atualizado com sucesso']);
            } else {
                throw new Exception("Erro ao atualizar filme: " . $stmt->error);
            }
            break;

        case 'delete':
            $index = $_POST['index'] ?? '';
            
            $stmt = $conn->prepare("DELETE FROM movies WHERE id = ?");
            $stmt->bind_param("i", $index);
            
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Filme excluído com sucesso']);
            } else {
                throw new Exception("Erro ao excluir filme: " . $stmt->error);
            }
            break;

        default:
            echo json_encode(['error' => 'Ação não reconhecida: ' . $action]);
    }
} catch (Exception $e) {
    error_log("Erro no server.php: " . $e->getMessage());
    echo json_encode(['error' => $e->getMessage()]);
}

$conn->close();
?>