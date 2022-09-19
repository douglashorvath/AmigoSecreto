<?php

include "classes/PHPMailer-master/PHPMailerAutoload.php";

function conectaBanco()
{
    //LOCAL
    //$pdo = new PDO('mysql:host=localhost;dbname=amigo_secreto', 'root', '');
    
    //SERVER
    $pdo = new PDO('mysql:host=localhost;dbname=libertet_amigosecreto', 'libertet_amigosecreto', 'HcmZ[uCE6]X1');
    
    return $pdo;
}

function sendEmail($email, $nome, $content, $subject) {
    

    $mail = new PHPMailer();
    $mail->IsSMTP(true);
    $mail->Host = 'mail.liberteti.com'; // not ssl://smtp.gmail.com
    $mail->SMTPAuth = true;
    $mail->Username = 'amigosecreto@liberteti.com';
    $mail->Password = 'XvUpSDqzhlV6ySJQTN';
    $mail->Port = 465; // not 587 for ssl 
//    $mail->SMTPDebug = 2; 
    $mail->SMTPSecure = 'ssl';
    $mail->SetFrom('amigosecreto@liberteti.com', 'Amigo Secreto LiberteTI');
    $mail->AddAddress($email, $nome);
    $mail->CharSet = 'UTF-8';
    $mail->Subject = $subject;
    $mail->Body = $content;
    $mail->AltBody = $content;
    if (!$mail->Send()) {
        return false;
    } else {
        return true;
    }
    
}

function confereLogin()
{
        if (isset($_SESSION['login']) && isset($_SESSION['senha'])) {
            $login = $_SESSION['login'];
            $senha = $_SESSION['senha'];
            $medico = new MedicoOnline();
            $medico = $medico->conferirUsuario($login, $senha);
            if($medico->getAtivo() == true)
            {
                $nome = $medico->getNome();
                if ($nome != null) {
                    return $medico;
                }
                else
                {
                    //echo "caiu 1";
                    header('location: http://liberteti.com/gastrin/login.php');
                    return null;
                }
            }
            else
            {
                header('location: http://liberteti.com/gastrin/login.php');
                return null;
            }
        }
        else
        {
            //echo "caiu 3";
            header('location: http://liberteti.com/gastrin/login.php');
            return null;
        }

}

//function conferirUsuario($login, $senha) {
//    try {
//        $pdo = conectaBanco();
//    } catch (PDOException $e) {
//        throw new PDOException($e);
//    }
//    $sql = "SELECT * FROM GASTRIN_USERS WHERE EMAIL = ?";
//    $editar = $pdo->prepare($sql);
//    $editar->execute([$login]);
//    foreach ($editar as $edit => $e) {
//        if ($e['PASSWORD'] == $senha) {
//            return $e['EMAIL'];
//        }
//    }
//    return null;
//}

function createSerial($serial, $dataExpAtt) {
    try {
        $pdo = conectaBanco();
    } catch (PDOException $e) {
        throw new PDOException($e);
    }
    $sql = "INSERT INTO LICENCAS (SERIAL_KEY, DATA_INS, DATA_EXP_ATT) VALUES (?,?,?)";
    $editar = $pdo->prepare($sql);
    if($editar->execute(array($serial, date("Y-m-d"), $dataExpAtt)))
    {
        return true;
    }
    else
    {
        return false;
    }
}

function createCodigoAtivacao($serialNumber)
{
    $codExt = hash("md5", $serialNumber) . hash("sha1", $serialNumber) . hash("sha256", $serialNumber);
    $codigoAtivacao = ""
            . $codExt[33]
            . $codExt[2]
            . $codExt[30]
            . $codExt[68]
            . $codExt[35]
            . $codExt[58]
            . $codExt[32]
            . $codExt[69];
    return strtoupper($codigoAtivacao);
}

function createLicence($email, $nome) {
    
    include "classes/PHPMailer-master/PHPMailerAutoload.php";
    $randomString = time();
    if (hash("md5", $randomString) == null || hash("sha1", $randomString) == null || hash("sha256", $randomString) == null) {
        return null;
    } else {
        $serialNumberEncoded = hash("md5", $randomString) . hash("sha1", $randomString) . hash("sha256", $randomString);

        $serialNumber = ""
                . $serialNumberEncoded[12]
                . $serialNumberEncoded[56]
                . $serialNumberEncoded[102]
                . $serialNumberEncoded[78]
                . $serialNumberEncoded[47]
                . "-"
                . $serialNumberEncoded[2]
                . $serialNumberEncoded[99]
                . $serialNumberEncoded[66]
                . $serialNumberEncoded[32]
                . $serialNumberEncoded[20]
                . "-"
                . $serialNumberEncoded[30]
                . $serialNumberEncoded[91]
                . $serialNumberEncoded[24]
                . $serialNumberEncoded[59]
                . $serialNumberEncoded[15]
                . "-"
                . $serialNumberEncoded[7]
                . $serialNumberEncoded[68]
                . $serialNumberEncoded[41]
                . $serialNumberEncoded[88]
                . $serialNumberEncoded[71];
        //echo strtoupper($serialNumber);

        $serialNumber = strtoupper($serialNumber);

        $codigoAtivacao = createCodigoAtivacao($serialNumber);

        $mail = new PHPMailer();
        $mail->IsSMTP(true);
        $mail->Host = 'smtp.gmail.com'; // not ssl://smtp.gmail.com
        $mail->SMTPAuth = true;
        $mail->Username = 'libertemedicin@gmail.com';
        $mail->Password = 'KRO8aqTnM0';
        $mail->Port = 465; // not 587 for ssl 
//    $mail->SMTPDebug = 2; 
        $mail->SMTPSecure = 'ssl';
        $mail->SetFrom('libertemedicin@gmail.com', 'LiberteTI');
        $mail->AddAddress($email, $nome);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = 'LICENÇA GASTRIN';
        $mail->Subject = "LICENÇA GASTRIN";

        //$linkAtivacao = 'http://liberteti.com/LibSystem/createLicence.php?serial=' . $serialNumber . "&cod=" . $codigoAtivacao;
        $mail->Body = "Serial Gerado: <b>" . $serialNumber . '</b>'
                . '<br> Codigo de Ativação: <b>' . $codigoAtivacao . '</b>';
                //. '<br><br><a href="' . $linkAtivacao . '">Clique aqui para ativar o serial! </a>';
        $mail->AltBody = "Serial Gerado: " . $serialNumber;
        if (!$mail->Send()) {
            return null;
        } else {
            return $serialNumber;
        }
    }
}
