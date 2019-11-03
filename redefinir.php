<?php
require './config.php';
if(!empty($_GET['token'])){
    $token = addslashes($_GET['token']);
    $sql = $pdo->prepare("SELECT * FROM usuarios_token WHERE hash = :hash AND used = 0 AND expirado_em > NOW()");
    $sql->bindValue("hash",$token);
    $sql->execute();
    if($sql->rowCount() > 0){
        $sql = $sql->fetch();
        $id = $sql['id_usuario'];
        if(!empty($_POST['senha'])){
            $senha = md5(addslashes($_POST['senha']));
            //altera senha
            $sql = $pdo->prepare('UPDATE usuarios SET senha = :senha WHERE id = :id');
            $sql->bindValue(":senha",$senha);
            $sql->bindValue(":id",$id);
            $sql->execute();
            //invalida Token
            $sql = $pdo->prepare("UPDATE usuarios_token SET used = 1 WHERE hash = ?");
            $sql->execute(array($token));
            die("<h3>Senha alterado com sucesso!</h3><a href='./'>Voltar para Login</a>");
            
        }
    }else{
        die("Token Expirado ou Inválido");
    }
}else{
    die("Link Inválido");
}
?>
<form method="POST">
    Nova Senha:<br/>
    <input type="password" name="senha" required/><br/><br/>
    <input type="submit" value="Mudar Senha"/>
</form>