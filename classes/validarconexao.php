<?php
            session_start();
            require 'classes/conexao.class.php';
            
            $user = "";
            $senha = "";
            if(isset($_SESSION['senha']))
                $senha = $_SESSION['senha'];
            if(isset($_SESSION['usuario']))
                $user = $_SESSION['usuario'];
            $c = $pdo->prepare("SELECT login, senha FROM sys_admin WHERE login = '$user' AND senha = '$senha'");
            $c->execute();

            return $c;
?>
