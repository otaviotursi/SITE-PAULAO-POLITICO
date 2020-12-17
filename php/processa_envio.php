<?php

    require "../php/src/Exception.php";
    require "../php/src/OAuth.php";
    require "../php/src/PHPMailer.php";
    require "../php/src/POP3.php";
    require "../php/src/SMTP.php";

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    // print_r($_POST);

    class Mensagem{
        private $nome = null;
        private $sobrenome = null;
        private $email = null;
        private $assunto = null;
        private $mensagem = null;

        public $status = array('codigo_status' => null, 'descricao_status' =>'');

        public function __get($atributo){
            return $this->$atributo;
        }
        public function __set($atributo, $valor){
            $this->$atributo = $valor;
        }
        public function mensagemValida(){
            if(empty($this->nome)|| empty($this-> sobrenome) || empty($this->email) || empty($this->assunto) || empty($this->mensagem)){
                return false;
            }
            return true;
        }
    }

    $mensagem = new Mensagem();

    $mensagem->__Set('nome', $_POST['nome']);
    $mensagem->__Set('sobrenome', $_POST['sobrenome']);
    $mensagem->__Set('email', $_POST['email']);
    $mensagem->__Set('assunto', $_POST['assunto']);
    $mensagem->__Set('telefone', $_POST['telefone']);
    $mensagem->__Set('mensagem', $_POST['mensagem']);

    // print_r($mensagem);

    if(!$mensagem->mensagemValida()){
        echo 'Mensagem não é valida, verifique se preencheu todos os campos';
        header('Location: ../contato.html');

    }

    $mail = new PHPMailer(true);
    try {
        $mail->SMTPDebug = false;
        $mail->isSMTP();// Set mailer to use SMTP
        $mail->CharSet = "utf-8";// set charset to utf8
        $mail->SMTPAuth = true;// Enable SMTP authentication
        $mail->SMTPSecure = 'tls';// Enable TLS encryption, `ssl` also accepted

        $mail->Host = 'br76.hostgator.com.br';// Specify main and backup SMTP servers
        $mail->Port = 587;// TCP port to connect to
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        $mail->isHTML(true);// Set email format to HTML
                                 // Enable SMTP authentication
        $mail->Username   = 'contato@paulaorenovacao.com.br';                     // SMTP username
        $mail->Password   = 'emailPaul@o28500';                               // SMTP password

        //Recipients
        $mail->setFrom('contato@paulaorenovacao.com.br', 'Contato Equipe Paulão');
        $mail->addAddress('contato@paulaorenovacao.com.br');     // Add a recipient
        // $mail->addReplyTo('otaviotursi@gmail.com');
        // $mail->addCC('otaviotursi@gmail.com');
        // $mail->addBCC('bcc@example.com');

        //Attachments
        // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

        //Content
        $mail->Subject = $mensagem->__get('assunto');
        // $mail->Body    = "meu nome: $mensagem->__get('nome'), meu sobrenome:$mensagem->__get('sobrenome')
        // $mail->Body    = $mensagem->__get('sobrenome');
        // $mail->Body    = $mensagem->__get('telefone');
        // $mail->Body    = $mensagem->__get('mensagem');

        //$mail->Body = 'Aqui entra o conteudo texto do email'; 
        $mail->Body   .= "Nome: "    .$_POST['nome']. "<br>";
        $mail->Body   .= "Sobrenome: ".$_POST['sobrenome']."<br>";
        $mail->Body   .= "E-mail: "  .$_POST['email']."<br>";
        $mail->Body   .= "telefone: ".$_POST['telefone']."<br>";
        $mail->Body   .= "Mensagem: ".$_POST['mensagem']."<br>";

        $mail->send();
        $mensagem ->status['codigo_status'] = 1;
        $mensagem ->status['descricao_status'] = 'Email enviado com sucesso';

        // echo 'Mensagem enviada com sucesso!';
        // header('Location: ../html/contato.html');
    } catch (Exception $e) {
        $mensagem ->status['codigo_status'] = 2;
        $mensagem ->status['descricao_status'] = 'Não foi possivel enviar este e-mail! Por favor tente novamente mais tarde.' . $mail->ErrorInfo;
        // echo 'Não foi possivel enviar este e-mail! Por favor tente novamente mais tarde.';
        // echo 'Erro: ' . $mail->ErrorInfo;
    }

?>

<html>
<head>
	<title>Paulão | Mensagem enviada</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="../styles/style.css">

	<link rel="shortcut icon" href="../imagens/paulaoIconeColorido.png">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<!-- Fontawesome CSS -->
	<script src="https://kit.fontawesome.com/24cc6c305c.js" crossorigin="anonymous"></script>
</head>
    <body>
        <div class="container">
            <div class="py-3 text-center">
                <img class="d-block mx-auto mb-2" src="../imagens/paulaoIconeColorido.png" alt="">
                <h2>Paulão é renovação</h2>
                <p class="lead">Escolha certa! Escolha Paulão!</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">

                <?php if($mensagem->status['codigo_status'] == 1) { ?>

                    <div class="container">
                        <h1 class="display-4 text-success">Sucesso!</h1>
                        <p><?php $mensagem->status['descricao_status'] ?>Mensagem enviada!</p>
                        <a href="../contato.html" class="btn btn-success btn-lg mt-5 text-white">Voltar</a>
                    </div>

                <?php } ?>

                <?php if($mensagem->status['codigo_status'] == 2) { ?>

                    <div class="container">
                        <h1 class="display-4 text-danger">Ops!</h1>
                        <p><?php $mensagem->status['descricao_status'] ?>Não foi possível enviar este e-mail! Por favor tente novamente mais tarde.</p>
                        <a href="../contato.html" class="btn btn-success btn-lg mt-5 text-white">Voltar</a>
                    </div>

                <?php } ?>

            </div>
        </div>
    </body>
</html>
