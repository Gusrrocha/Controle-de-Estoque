<?php
    function add($nome, $valor, $valor_c, $quantidade)
    {
        try
        {
            date_default_timezone_set('America/Bahia');
            $data = date('d/m/Y H:i:s');
            
            $pdo = new PDO('sqlite:./database/banco.db','','',[PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
            
            //$pdo->exec('insert into Estoque(nome,email) values("Gustavo", "fdafadko")');
            $query = $pdo->prepare("INSERT INTO Estoque(nome,valor,valor_compra,quantidade) values(:nome,:valor,:valor_compra,:quantidade)");
            $users = $query->execute([
                "nome" => $nome,
                "valor" => $valor,
                "valor_compra" => $valor_c,
                "quantidade" => $quantidade
            ]);
            $query = $pdo->prepare("INSERT INTO Relatorio(data_,tipo,nome,quantidade) values(:data_,:tipo,:nome,:quantidade)");
            $query->execute([
                "data_" => $data,
                "tipo" => "INSERIDO",
                "nome" => $nome,
                "quantidade" => $quantidade
            ]);
        }
        catch (PDOException $e)
        {
            echo "Erro ao se conectar com o banco de dados: ". $e->getMessage();
        }
    }

    function update($id, $nome, $valor, $valor_c, $quantidade)
    {   
        try
        {
            date_default_timezone_set('America/Bahia');
            $data = date('d/m/Y H:i:s');
            
            $file = $_SERVER["DOCUMENT_ROOT"].'/database/banco.db';
            $pdo = new PDO('sqlite:'.$file,'','',[PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
            $query = $pdo->query("SELECT quantidade FROM Estoque WHERE id=$id");
            $result = $query->fetchAll();
            $a = $result[0]['quantidade'];
            if ($a < $quantidade)
            {
                $query = $pdo->prepare("INSERT INTO Relatorio(data_,tipo,nome,quantidade) values(:data_,:tipo,:nome,:quantidade)");
                $query->execute([
                    "data_" => $data,
                    "tipo" => "ENTRADA",
                    "nome" => $nome,
                    "quantidade" => $quantidade-$a
                ]);
            }
            else 
            {
                if ($a > $quantidade)
                {
                    $query = $pdo->prepare("INSERT INTO Relatorio(data_,tipo,nome,quantidade) values(:data_,:tipo,:nome,:quantidade)");
                    $query->execute([
                        "data_" => $data,
                        "tipo" => "SAÍDA",
                        "nome" => $nome,
                        "quantidade" => $a-$quantidade
                    ]);
                }
            }
            $query = $pdo->query("UPDATE Estoque SET nome='$nome', valor=$valor, valor_compra=$valor_c, quantidade=$quantidade WHERE id=$id;");
            $query->execute();
        }
        catch (PDOException $e)
        {
            echo "Erro ao se conectar com o banco de dados: ". $e->getMessage();
        }
    }
    
    function listar()
    {
        try
        {
            $file = $_SERVER["DOCUMENT_ROOT"].'/database/banco.db';
            $pdo = new PDO('sqlite:'.$file,'','',[PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
            $query = $pdo->query("SELECT * FROM Estoque;");
            $result = $query->fetchall();
            return $result;
        }
        catch (PDOException $e)
        {
            echo "Erro ao se conectar com o banco de dados: ". $e->getMessage();
        }
    }
    function listar_mov()
    {
        try
        {
            $file = $_SERVER["DOCUMENT_ROOT"].'/database/banco.db';
            $pdo = new PDO('sqlite:'.$file,'','',[PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
            $query = $pdo->query("SELECT * FROM Relatorio;");
            $result = $query->fetchall();
            return $result;
        }
        catch (PDOException $e)
        {
            echo "Erro ao se conectar com o banco de dados: ". $e->getMessage();
        }
    }
    function remover($id)
    {
        try
        {   
            date_default_timezone_set('America/Bahia');
            $data = date('d/m/Y H:i:s');
            $file = $_SERVER["DOCUMENT_ROOT"].'/database/banco.db';
            $pdo = new PDO('sqlite:'.$file,'','',[PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
            $query = $pdo->query("SELECT nome FROM Estoque WHERE id=$id");
            $result = $query->fetchAll();
            $nome = $result[0]['nome'];
            $query = $pdo->query("DELETE FROM Estoque WHERE id=$id;");
            $query->execute();
            $query = $pdo->prepare("INSERT INTO Relatorio(data_,tipo,nome,quantidade) values(:data_,:tipo,:nome,:quantidade)");
            $query->execute([
                "data_" => $data,
                "tipo" => "REMOVIDO",
                "nome" => $nome,
                "quantidade" => '-'
            ]);
        }
        catch (PDOException $e)
        {
            echo "Erro ao se conectar com o banco de dados: ". $e->getMessage();
        }
    }
    function login($usuario, $senha)
    {
        try
        {
            $file = $_SERVER["DOCUMENT_ROOT"].'/database/banco.db';
            $pdo = new PDO('sqlite:'.$file,'','',[PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
            $query = $pdo->query("SELECT * FROM Usuarios WHERE user=$usuario AND senha=$senha;");
            $result = $query->fetchall();
            return $result;
        }
        catch (PDOException $e)
        {
            echo "Erro ao se conectar com o banco de dados: ". $e->getMessage();
        }
    }
?>
