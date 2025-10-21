<?php
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// SUA CONEXÃO ORIGINAL - NÃO ALTEREI
$hostname = "localhost";
$bancodedados = "biblioteca";
$usuario = "root";
$senha = "";

$mysqli = new mysqli($hostname, $usuario, $senha, $bancodedados);

if ($mysqli->connect_errno) {
    echo json_encode(["success" => false, "error" => "Falha na conexão: " . $mysqli->connect_error]);
    exit();
}

// Verificar se a tabela livros existe, se não, criar
$check_table = $mysqli->query("SHOW TABLES LIKE 'livros'");
if ($check_table->num_rows == 0) {
    // Criar tabela se não existir
    $create_table = "CREATE TABLE livros (
        id INT AUTO_INCREMENT PRIMARY KEY,
        livrodb VARCHAR(255) NOT NULL,
        autor_livro VARCHAR(255) NOT NULL,
        data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    if (!$mysqli->query($create_table)) {
        echo json_encode(["success" => false, "error" => "Erro ao criar tabela: " . $mysqli->error]);
        exit();
    }
}

// Verificar o tipo de ação
$acao = $_POST['acao'] ?? '';

// ADICIONAR LIVRO
if ($acao === 'adicionar') {
    $livrodb = $_POST['livrodb'] ?? '';
    $autor_livro = $_POST['autor_livro'] ?? '';
    
    if (empty($livrodb) || empty($autor_livro)) {
        echo json_encode(["success" => false, "error" => "Todos os campos são obrigatórios"]);
        exit();
    }
    
    $sql = "INSERT INTO livros (livrodb, autor_livro) VALUES (?, ?)";
    $stmt = $mysqli->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("ss", $livrodb, $autor_livro);
        
        if ($stmt->execute()) {
            echo json_encode(["success" => true, "id" => $stmt->insert_id, "message" => "Livro adicionado com sucesso"]);
        } else {
            echo json_encode(["success" => false, "error" => "Erro ao adicionar livro: " . $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(["success" => false, "error" => "Erro na preparação: " . $mysqli->error]);
    }
}

// BUSCAR LIVRO
else if ($acao === 'buscar') {
    $id = $_POST['id'] ?? '';
    
    if (empty($id)) {
        echo json_encode(["success" => false, "error" => "ID é obrigatório"]);
        exit();
    }
    
    $sql = "SELECT * FROM livros WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $livro = $result->fetch_assoc();
        
        if ($livro) {
            echo json_encode(["success" => true, "livro" => $livro]);
        } else {
            echo json_encode(["success" => false, "error" => "Livro não encontrado"]);
        }
        $stmt->close();
    } else {
        echo json_encode(["success" => false, "error" => "Erro na preparação: " . $mysqli->error]);
    }
}

// RENOMEAR LIVRO
else if ($acao === 'renomear') {
    $id = $_POST['id'] ?? '';
    $livrodb = $_POST['livrodb'] ?? '';
    $autor_livro = $_POST['autor_livro'] ?? '';
    
    if (empty($id) || empty($livrodb) || empty($autor_livro)) {
        echo json_encode(["success" => false, "error" => "Todos os campos são obrigatórios"]);
        exit();
    }
    
    $sql = "UPDATE livros SET livrodb = ?, autor_livro = ? WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("ssi", $livrodb, $autor_livro, $id);
        
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                echo json_encode(["success" => true, "message" => "Livro atualizado com sucesso"]);
            } else {
                echo json_encode(["success" => false, "error" => "Nenhum livro encontrado com este ID"]);
            }
        } else {
            echo json_encode(["success" => false, "error" => "Erro ao atualizar livro: " . $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(["success" => false, "error" => "Erro na preparação: " . $mysqli->error]);
    }
}

// EXCLUIR LIVRO
else if ($acao === 'excluir') {
    $id = $_POST['id'] ?? '';
    
    if (empty($id)) {
        echo json_encode(["success" => false, "error" => "ID é obrigatório"]);
        exit();
    }
    
    $sql = "DELETE FROM livros WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                echo json_encode(["success" => true, "message" => "Livro excluído com sucesso"]);
            } else {
                echo json_encode(["success" => false, "error" => "Nenhum livro encontrado com este ID"]);
            }
        } else {
            echo json_encode(["success" => false, "error" => "Erro ao excluir livro: " . $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(["success" => false, "error" => "Erro na preparação: " . $mysqli->error]);
    }
}

// AÇÃO NÃO RECONHECIDA
else {
    echo json_encode(["success" => false, "error" => "Ação não reconhecida: " . $acao]);
}

$mysqli->close();
?>