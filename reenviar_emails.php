<?php
    require("classes/Template.class.php");
    require("classes/functions.php");
    
    
    $tpl = new Template("html/sorteio_nomes.html");
    
    if(isset($_POST['submit']))
    {
        if(isset($_POST['codigo']))
        {
            $codigo = $_POST['codigo'];
            try {
                $pdo = conectaBanco();
            } catch (PDOException $e) {
                echo $e;
                throw new PDOException($e);
                
            }
            
            $sql = "SELECT * FROM sorteio WHERE HASH LIKE ?";
            $editar = $pdo->prepare($sql);
            $editar->execute([$codigo]);
            
            $participantes = array();
            $emails = array();
            $pegou_nome = array();
            $pegou_email = array();
            $qtdParticipantes =0;
            foreach ($editar as $edit => $e) {
                
                $participantes[$qtdParticipantes] = $e['PARTICIPANTE'];
                $emails[$qtdParticipantes] = $e['EMAIL'];
                $pegou_nome[$qtdParticipantes] = $e['SORTEIO_NOME'];
                $pegou_email[$qtdParticipantes] = $e['SORTEIO_EMAIL'];
                
                $qtdParticipantes++;
            }
            
            //echo "Iniciando gravação no banco de dados...";
            
            $tpl->ATIVIDADE = "<br/>Código do seu jogo: <br/> <b>".$codigo."</b><br/>";
            $tpl->block("BLOCK_ATIVIDADE");
                        
            for($i=0;$i<$qtdParticipantes;$i++)
            {
               
//                
                
                
                    //echo "<br/>Pronto!<br/>";
                    
                    $content = '<!doctype html>
<html>
<head>
	<title>HTML Editor - Full Version</title>
</head>
<body>
<p style="font-weight: 400; line-height: 1.2; color: rgb(51, 51, 51); font-family: sans-serif, Arial, Verdana, &quot;trebuchet ms&quot;; text-align: center;"><span style="font-size:16px;">Bem vindo ao Amigo Secreto da Galera. Nesse email voc&ecirc; poder&aacute; ver quem voc&ecirc; pegou! Veja o nome abaixo e n&atilde;o conte pra ningu&eacute;m!</span></p>

<h1 style="font-weight: 400; line-height: 1.2; color: rgb(51, 51, 51); font-family: sans-serif, Arial, Verdana, &quot;trebuchet ms&quot;; text-align: center;"><span style="font-size:24px;"><strong>'.$pegou_nome[$i].'</strong></span></h1>

<p style="font-weight: 400; line-height: 1.2; color: rgb(51, 51, 51); font-family: sans-serif, Arial, Verdana, &quot;trebuchet ms&quot;; text-align: center;"><span style="font-size:16px;">Se deseja realizar o reenvio de todos os emails, acesse o site e insira o c&oacute;digo abaixo:</span></p>

<h1 style="font-weight: 400; line-height: 1.2; color: rgb(51, 51, 51); font-family: sans-serif, Arial, Verdana, &quot;trebuchet ms&quot;; text-align: center;"><span style="font-size:24px;"><strong>'.$codigo.'</strong></span></h1>

<p style="font-weight: 400; line-height: 1.2; color: rgb(51, 51, 51); font-family: sans-serif, Arial, Verdana, &quot;trebuchet ms&quot;; text-align: center;"><span style="font-size:16px;">Obrigado por participar!</span></p>
</body>
</html>
';
                    //echo "CONTEUDO DA MENSAGEM: <br/><br/>".$content;
                
                    if(sendEmail($emails[$i], $participantes[$i], $content, "Amigo Secreto LiberteTI"))
                    {
                        
                        $tpl->ATIVIDADE = "<br/>Enviado email para ".$participantes[$i]."(".$emails[$i].") com seu amigo secreto.";
                        $tpl->block("BLOCK_ATIVIDADE");
                        //echo "<br/>Enviado email para ".$emails[$i]." com seu amigo secreto<br/>";
                    }
                    else
                    {
                        $tpl->ATIVIDADE = "<br/>Erro ao enviar email para ".$participantes[$i]."(".$emails[$i].").";
                        $tpl->block("BLOCK_ATIVIDADE");
                        //echo "<br/>Erro ao enviar email para ".$participantes[$i]."(".$emails[$i].")";
                    }
                    
                
                        
            }
        }
        else
        {
            $tpl->ATIVIDADE = "<br/>Erro ao encontrar amigo secreto. Código inexistente.";
            $tpl->block("BLOCK_ATIVIDADE");
        }
        
    }
    else
    {
        $tpl->ATIVIDADE = "<br/>Nenhum dado encontrado.";
        $tpl->block("BLOCK_ATIVIDADE");
    }
    
    
    
    $tpl->show();
    
    
?>