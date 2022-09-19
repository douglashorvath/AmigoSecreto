<?php
    require("classes/Template.class.php");
    $tpl = new Template("html/atividades.html");
    
    
    if(isset($_POST['submit']))
    {
        if(isset($_POST['qtdparticipantes']))
        {
            $qtdParticipantes = $_POST['qtdparticipantes'];
            if($qtdParticipantes>2)
            {
                $tpl->ATIVIDADE = "Digite os dados dos participantes";
                $tpl->block("BLOCK_ATIVIDADE");
                $tpl->QTD_PARTICIPANTES = $qtdParticipantes;
                for($i=1;$i<=$qtdParticipantes;$i++)
                {
                    $tpl->PARTICIPANTE = "participante".$i;
                    $tpl->EMAIL = "email".$i;
                    $tpl->block("BLOCK_PARTICIPANTE");
                }
                 
            }
            else
            {
                $tpl->ATIVIDADE = "O número de participantes precisa ser 3 ou mais";
                $tpl->block("BLOCK_ATIVIDADE");
            }
        }
        $tpl->show();
    }
    else
    {
        header("Location: index.php");
    }
    
    
    
?>