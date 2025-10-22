<?php
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// conexão com banco de dados
$hostname = "localhost";
$bancodedados = "biblioteca";
$usuario = "root";
$senha = "";

$mysqli = new mysqli($hostname, $usuario, $senha, $bancodedados);

if ($mysqli->connect_errno) {
    echo json_encode(["success" => false, "error" => "Falha na conexão: " . $mysqli->connect_error]);
    exit();
}

// Verificar se na tabela têm coluna livros 
$check_table = $mysqli->query("SHOW TABLES LIKE 'livros'");
if ($check_table === false) {
    echo json_encode(["success" => false, "error" => "Erro ao verificar a tabela: " . $mysqli->error]);
    exit();
} elseif ($check_table->num_rows == 0) {
    echo json_encode(["success" => false, "error" => "A tabela 'livros' não existe no banco de dados."]);
    exit();
}

// para verificar a ação que estão realizando
$acao = $_POST['acao'] ?? '';

// adicionar o livro no banco
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

// procurar livro
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

// renomear o livro
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

// deletar o livro

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

// quando a ação não é reconhecida
else {
    echo json_encode(["success" => false, "error" => "Ação não reconhecida: " . $acao]);
}

$mysqli->close();
?>
