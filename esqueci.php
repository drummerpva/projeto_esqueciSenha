<?php
require './config.php';
if(!empty($_POST['email'])){
    $email = addslashes($_POST['email']);
    $sql = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
    $sql->bindValue(":email",$email);
    $sql->execute();
    if($sql->rowCount() > 0){
        $sql = $sql->fetch();
        $id = $sql['id'];
        $token = md5(time().rand(0,99999).rand(0,99999));
        $sql = $pdo->prepare("INSERT INTO usuarios_token (id_usuario, hash, expirado_em) VALUE(?,?,?)");
        $sql->execute(array($id,$token,date("Y-m-d H:i:s", strtotime('+2 months'))));
        $link = "<a href='./redefinir.php?token=".$token."'>Redefinir</a>";
        $mensagem = "<h4>Clique no link para redefinir sua senha.</h4>".$link;
        $assunto = "Redefinição de Senha";
        $headers = "From: douglas.poma@registrallogistica.com.br"."\r\n".
                    "X-Mailer: PHP/".phpversion();
        //mail($email,$assunto,$mensagem,$headers);
        echo $mensagem;
        die();
    }
}
?>
<form method="POST">
    Qual seu e-mail?<br/>
    <input type="email" name="email" /><br/><br/>
    <input type="submit" value="Enviar"/>
</form>