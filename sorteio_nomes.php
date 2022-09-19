<?php
    require("classes/Template.class.php");
    require("classes/functions.php");
    
    
    $tpl = new Template("html/sorteio_nomes.html");
    
    if(isset($_POST['submit']))
    {
        if(isset($_POST['qtdparticipantes']))
        {
            
            $sequencia = false;
            if(isset($_POST['sequenciar']))
            {
                $sequencia = true;
            }

            $qtdParticipantes = $_POST['qtdparticipantes'];
            $participantes = array();
            $emails = array();
            $sorteio = array();
            $qtdParticipantes = $_POST['qtdparticipantes'];
            for($i=1;$i<=$qtdParticipantes;$i++)
            {
                if(isset($_POST['participante'.$i]))
                {
                    $participantes[] = strtoupper($_POST['participante'.$i]);
                    $emails[] = strtolower($_POST['email'.$i]);
                    
                }
            }
            
            $ok = false;
            $iteracoes = 0;
            while(!$ok)
            {
                $iteracoes++;
                //echo "Iniciando sorteios... <br/>";
                unset($sorteio);
                $sorteio = array();
                for($i = 0;$i<$qtdParticipantes;$i++)
                {
                    $random = rand(0, $qtdParticipantes-1);
                    $sorteado = $emails[$random];
                    while(in_array($sorteado, $sorteio))
                    {
                        $random = rand(0, $qtdParticipantes-1);
                        $sorteado = $emails[$random];
                    }
                    if($sorteado == $emails[$i])
                    {
                        $random = rand(0, $qtdParticipantes-1);
                        $sorteado = $emails[$random];
                    }
                    $sorteio[] = $sorteado;
                }
                
                $ok = true;
                
                //echo "Conferindo se não houve quem pegou o próprio nome <br/>";
                for($i = 0;$i<$qtdParticipantes;$i++)
                {
                    if($emails[$i] == $sorteio[$i])
                    {
                        $ok = false;
                    }
                }
                
                if($ok)
                {
                    if($sequencia)
                    {
                        //echo "Encontrando travamento na sequência de presentes <br/>";
                        $proximo = $emails[0];
                        $jaFoi = array();
                        for($i = 0;$i<$qtdParticipantes;$i++)
                        {
                            $needle = array_search($proximo, $sorteio);
                            $proximo = $emails[$needle];
                            if(in_array($proximo, $jaFoi))
                            {
                                $ok=false;
                            }
                            else
                            {
                                $jaFoi[] = $proximo;
                            }
                        }
                    }
                }
                
                
            }
            
            $hash = md5(uniqid(rand(), true));
            
            // sorteio
            
            //echo "<br/> Iteracões totais: ".$iteracoes."<br/>";
            
//            for($i=0;$i<$qtdParticipantes;$i++)
//            {
//                echo $participantes[$i]."(".$emails[$i].") pegou ".$sorteio[$i];
//                echo "<br/>";
//            }
//            
//            echo "<br/>Hash da sessão: ".$hash."<br/>";
            
            try {
                $pdo = conectaBanco();
            } catch (PDOException $e) {
                echo $e;
                throw new PDOException($e);
                
            }
            //echo "Iniciando gravação no banco de dados...";
            
            $tpl->ATIVIDADE = "<br/>Código do seu jogo: <br/> <b>".$hash."</b><br/>";
            $tpl->block("BLOCK_ATIVIDADE");
                        
            for($i=0;$i<$qtdParticipantes;$i++)
            {
               
//                
                
                $needle = array_search($sorteio[$i],$emails);
                $pegou = $participantes[$needle];
                
                //echo "<br/>Gravando no banco de dados...<br/>";
                $sql = "INSERT INTO sorteio (PARTICIPANTE,EMAIL,SORTEIO_NOME,SORTEIO_EMAIL,HASH) VALUES (?,?,?,?,?)";
                $editar = $pdo->prepare($sql);
                if($editar->execute(array($participantes[$i],$emails[$i],$pegou,$sorteio[$i],$hash)))
                {
                    //echo "<br/>Pronto!<br/>";
                    
                    $content = '<!doctype html>
<html>
<head>
	<title>HTML Editor - Full Version</title>
</head>
<body>
<p style="font-weight: 400; line-height: 1.2; color: rgb(51, 51, 51); font-family: sans-serif, Arial, Verdana, &quot;trebuchet ms&quot;; text-align: center;"><span style="font-size:16px;">Bem vindo ao Amigo Secreto da Galera. Nesse email voc&ecirc; poder&aacute; ver quem voc&ecirc; pegou! Veja o nome abaixo e n&atilde;o conte pra ningu&eacute;m!</span></p>

<h1 style="font-weight: 400; line-height: 1.2; color: rgb(51, 51, 51); font-family: sans-serif, Arial, Verdana, &quot;trebuchet ms&quot;; text-align: center;"><span style="font-size:24px;"><strong>'.$pegou.'</strong></span></h1>

<p style="font-weight: 400; line-height: 1.2; color: rgb(51, 51, 51); font-family: sans-serif, Arial, Verdana, &quot;trebuchet ms&quot;; text-align: center;"><span style="font-size:16px;">Se deseja realizar o reenvio de todos os emails, acesse o site e insira o c&oacute;digo abaixo:</span></p>

<h1 style="font-weight: 400; line-height: 1.2; color: rgb(51, 51, 51); font-family: sans-serif, Arial, Verdana, &quot;trebuchet ms&quot;; text-align: center;"><span style="font-size:24px;"><strong>'.$hash.'</strong></span></h1>

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
                else
                {
                    $tpl->ATIVIDADE = "<br/>Erro ao inserir ".$participantes[$i]." no amigo secreto.";
                    $tpl->block("BLOCK_ATIVIDADE");
//                    echo "<br/>Erro ao inserir!<br/>";
//                    echo "<br/>ERRO:".$editar->errorInfo();
                }
                        
            }
        }
        else
        {
            $tpl->ATIVIDADE = "<br/>Erro ao criar amigo secreto. Mínimo de 3 participantes.";
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