function mod(clicked)
{
    cadastrar.classList.add("show");
    if (event.target == cadastrar) {
        cadastrar.classList.remove("show");
    }
    var nome = document.getElementById(clicked.toString().concat("2")).innerHTML;
    var valor = document.getElementById(clicked.toString().concat("3")).innerHTML;
    if(!(valor.includes(".")))
    {
        valor = valor.concat(".00");
    }
    var valor_c = document.getElementById(clicked.toString().concat("4")).innerHTML;
    if(!(valor_c.includes(".")))
    {
        valor_c = valor_c.concat(".00");
    }
    var quantidade = document.getElementById(clicked.toString().concat("5")).innerHTML;
    document.getElementById("titulo").innerHTML = "Atualização do Produto";
    document.getElementById("nome").value = nome;
    document.getElementById("quantidade").value = quantidade;
    document.getElementById("valor").value = valor.slice(3);
    document.getElementById("valor_c").value = valor_c.slice(3);
    document.getElementById("botao1").style.visibility = "hidden";
    document.getElementById("botao2").style.visibility = "visible";
    createCookie("md", clicked, "10");
}

function check()
{
    var cook = (document.cookie.match(/^(?:.*;)?\s*md\s*=\s*([^;]+)(?:.*)?$/)||[,null])[1]
    if (cook == null)
    {
        
    }
    else
    {
        var nome = document.getElementById("nome").value;
        var valor = document.getElementById("valor").value;
        var valor_c = document.getElementById("valor_c").value;
        var quantidade = document.getElementById("quantidade").value;
        createCookie("nome", nome, "10");
        createCookie("valor", valor, "10");
        createCookie("valor_c", valor_c, "10");
        createCookie("quant", quantidade, "10");
        createCookie("check", 1, "10");
        document.getElementById("titulo").innerHTML = "Cadastro de Produto";
        document.getElementById("botao1").style.visibility = "visible";
        document.getElementById("botao2").style.visibility = "hidden";
        document.getElementById("form1").reset();
        cadastrar.classList.remove("show");
        location.reload();
    }
}
function check_2()
{
    document.getElementById("titulo").innerHTML = "Cadastro de Produto";
    document.getElementById("botao2").style.visibility = "hidden";
    document.getElementById("form1").reset();
}
function remover_j(clicked)
{
    
    const resposta = confirm("Tem certeza que deseja deletar?");
    if (resposta) {
        createCookie("rm", clicked, "10");
        cook = (document.cookie.match(/^(?:.*;)?\s*rm\s*=\s*([^;]+)(?:.*)?$/)||[,null])[1]
        console.log(cook);
    }
    else
    {

    }
}

function createCookie(name, value, days) { 
    const d = new Date();
    d.setTime(d.getTime() + (days*24*60*60*1000));
    let expires = "expires="+ d.toUTCString();
    document.cookie = name + "=" + value + ";" + expires + ";path=/";
}
function showPage(evt, nome)
{
    var i, conte, nave;

    conte = document.getElementsByClassName("conteudo");
    for (i = 0; i < conte.length; i++) {
        conte[i].style.display = "none";
    }

    
    nave = document.getElementsByClassName("navi");
    for (i = 0; i < nave.length; i++) {
        nave[i].className = nave[i].className.replace(" active", "");
    }

    
    document.getElementById(nome).style.display = "block";
    evt.currentTarget.className += " active";
}