<!DOCTYPE html>
<html data-backdrop="static" data-keyboard="false">
    <head>  
        <title>Controle de Estoque</title>
        <script src="./lib/jquery-3.7.1.js"></script>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="icon" href="images/favicon.ico" type="image/x-ico">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/style/estilos.css">
        <link rel="stylesheet" href="/style/table.css">
        <link rel="stylesheet" href="/style/popup.css">
        <link rel="stylesheet" href="/style/top.css">
        <link rel="stylesheet" href="/style/layout.css">
        <script src="funcoes.js"></script>
        <style>
            #top-content
            {
                background-image: url('./images/bg-00.jpg');
                background-size: cover;
            }
            #baixo
            {
                background-image: url('./images/bg-00.jpg');
                background-size: cover;
            }
        </style>
    </head>
    <body>       
        <?php $num = 1;
            include("./database/db.php");
            $itens = listar();
            $mov = array_reverse(listar_mov());
            if(isset($_COOKIE['rm']))
            {
                remover($_COOKIE['rm']);
                setcookie('rm', "", time() - 3600);
                
            }
            
            if (!isset($_SESSION))
            {
                session_start();
            }
            if(isset($_COOKIE['md']) && isset($_COOKIE['check']))
            {
                update($_COOKIE['md'],$_COOKIE['nome'],$_COOKIE['valor'],$_COOKIE['valor_c'],$_COOKIE['quant']);
                
                unset($_COOKIE['md']);
                unset($_COOKIE['nome']);
                unset($_COOKIE['valor']);
                unset($_COOKIE['valor_c']);
                unset($_COOKIE['quant']);

                setcookie('md', "", time() - 3600);
                setcookie('nome', "", time() - 3600);
                setcookie('valor', "", time() - 3600);
                setcookie('valor_c', "", time() - 3600);
                setcookie('quant', "", time() - 3600);
                setcookie('check', "", time() - 3600);
                $_SESSION['postdata'] = $_POST;
                unset($_POST);
                header("Location: ". $_SERVER['PHP_SELF']);
                exit;
            }
            if ($_SERVER['REQUEST_METHOD'] === 'POST') 
            {
                $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                if(isset($dados['botao1']))
                {
                    add($_POST['name'],$_POST['valor'], $_POST['valor_c'], $_POST['quantidade']); 
                }
                $_SESSION['postdata'] = $_POST;
                unset($_POST);
                header("Location: ". $_SERVER['PHP_SELF']);
                
                exit;
            }
        ?>
        <div id="top-content">
            <div id="logo">
                <figure>
                    <img src="./images/logo.png" alt="Minha Figura">
                    <figcaption>Controle de Estoque</figcaption>
                </figure>
            </div>
            <div id="direita">
                <label>PAINEL<label>
            </div>
        </div>
        <div id="parent">
            <div class="janela">
                <div id="cadastrar" class="popup">
                    <div class="popup-content">
                        <form id="form1" method="post" action="">
                            <h1 id="titulo">Cadastro de Produto</h1>
                            <div class="dados">
                                <div class="nm">
                                    <label>Nome do produto:</label> 
                                    <input type="text" name="name" id="nome" placeholder="Nome do produto" required>
                                </div>
                                <div class="qt">
                                    <label>Quantidade:</label>
                                    <input type="number" name="quantidade" id="quantidade" pattern="\d" title="Insira apenas números." placeholder="Quantidade de itens" min="0" required><br>
                                </div> 
                                <div class="val">
                                    <label>Valor:</label> 
                                    <label>R$</label>
                                    <input type="text" name="valor" id="valor" pattern="[0-9]+\.[0-9]{1,10000}" title="Insira no padrão pedido {123.45} incluindo o ponto ao invés da vírgula" placeholder="000.00" required>
                                </div>
                                <div class="vc">
                                    <label>Valor de compra:</label>
                                    <label>R$</label>
                                    <input type="text" name="valor_c" id="valor_c" pattern="[0-9]+\.[0-9]{1,10000}" title="Insira no padrão pedido {123.45} incluindo o ponto ao invés da vírgula" placeholder="000.00" required>
                                </div>
                            </div> 
                            <input type="submit" class="botao" name="botao1" id="botao1" value="Adicionar">
                            <input type="submit" class="botao" name="botao2" id="botao2" value="Atualizar" style="hidden" onclick="check()">
                        </form>
                        <button id="close">
                            <img src="./images/close.png">
                        </button>
                    </div>
                </div>
            </div>    
        </div>
        <section class="layout">
            <div id='esquerda'>
                <a class="navi" onclick="showPage(event, 'inicio')">Início</a>
                <a  class="navi" onclick="showPage(event,'div1')">Tabela</a>
                <a class="navi" onclick="showPage(event, 'mov')">Relatório</a>
            </div>
            <div id="meio">
                <div id="inicio" class="conteudo">
                    <p style="font-size:20px;font-weight:bold">Bem-vindo ao website "Controle de Estoque"!</p><br>
                    <p>Este site foi desenvolvido para gerenciar o estoque de empresas, permitindo diversas funções
                        como: 
                    </p>
                    <ul style="margin-left:40px;margin-top:20px;">
                        <li>
                            Adição, remoção e modificação de itens/produtos no estoque!
                        </li>
                        <li>
                            Sistema de entrada e saída!
                        </li>
                        <li>
                            Relatório contendo o histórico das ações realizadas no site!
                        </li>
                    </ul>
                </div>
                <div id="div1" class="conteudo">
                    <h1>Lista de Produtos no Estoque</h1>
                    <div id="int">
                        <h2>Interações</h2>
                        <button id="addb" onclick="check_2()">Adicionar Produto</button>
                    </div>
                    <table id="tabela1">
                        <tr>
                            <th>Código</th>
                            <th>Item</th>
                            <th>Valor</th>
                            <th>Valor de Compra</th>
                            <th>Quantidade</th>
                            <th>Ações</th>
                        </tr>
                        <?php foreach($itens as $row): ?>
                        <tr>
                            <td id=<?php echo strval($row['id'])."1";?>><?= htmlspecialchars($row['id']) ?></td>
                            <td id=<?php echo strval($row['id'])."2";?>><?= htmlspecialchars($row['nome']) ?></td>
                            <td id=<?php echo strval($row['id'])."3";?>>R$ <?= htmlspecialchars($row['valor']) ?></td>
                            <td id=<?php echo strval($row['id'])."4";?>>R$ <?= htmlspecialchars($row['valor_compra']) ?></td>
                            <td id=<?php echo strval($row['id'])."5";?>><?= htmlspecialchars($row['quantidade']) ?></td>
                            <td>
                                <button class="modificar" id=<?php echo $row['id'];?> onclick='mod(this.id)'>Modificar</button>
                                <form method="post" action="">
                                    <input type="submit" name="deletar" class="deletar" id="<?php echo $row['id'];?>" onclick='remover_j(this.id)' value="Remover">
                                </form>
                            </td>
                            <?php $num += 1; ?>
                        </tr>
                        <?php endforeach ?>
                    </table>
                </div>
                <div id="mov" class="conteudo">
                    <h1>Movimentação</h1>
                    <table id="relat">
                        <tr>
                            <th>Data</th>
                            <th>Tipo</th>
                            <th>Nome</th>
                            <th>Quantidade</th>
                        </tr>
                        <?php foreach($mov as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['data_']) ?></td>
                            <td style=<?php
                                        if(htmlspecialchars($row['tipo']) == "INSERIDO")
                                        {
                                            echo "color:cyan";

                                        }
                                        if(htmlspecialchars($row['tipo']) == "REMOVIDO")
                                        {
                                            echo "color:#fc0303";

                                        }
                                        if(htmlspecialchars($row['tipo']) == "ENTRADA")
                                        {
                                            echo "color:lightgreen";
                                        }     
                                        if(htmlspecialchars($row['tipo']) == "SAÍDA")
                                        {
                                            echo "color:#f75c5c;font-weight:bold";
                                            
                                        }
                                        ?>><?= htmlspecialchars($row['tipo']) ?></td>
                            <td><?= htmlspecialchars($row['nome']) ?></td>
                            <td><?= htmlspecialchars($row['quantidade']) ?></td>
                        </tr>
                        <?php endforeach ?>
                    </table>
                </div>
                <div id="response">
                    
                </div>
            </div>
            <div id="sidebar2">
                
            </div>
            <div id="baixo">
                
            </div>
        </div>
        </section>
        <script>
            $(document).ready(function() {
                $(window).keydown(function(event){
                    if(event.keyCode == 13) {
                    event.preventDefault();
                    return false;
                    }
                });
            });
            botao = document.getElementById("addb");
            cadastrar = document.getElementById("cadastrar");
            fechar = document.getElementById("close");
            botao.addEventListener("click", function () {
                cadastrar.classList.add("show");
            });
            fechar.addEventListener("click", function () {
                document.getElementById("botao1").style.visibility = "visible";
                document.getElementById("botao2").style.visibility = "hidden";
                document.getElementById("form1").reset();
                cadastrar.classList.remove("show");
            });
            
        </script>       
    </body>
</html>
