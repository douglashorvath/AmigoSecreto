<?php
            
            require 'classes/conexao.class.php';

            
            $c = $pdo->prepare("SELECT * FROM abre_caixa WHERE fechou =0");
            $c->execute();

            return $c;
?>
