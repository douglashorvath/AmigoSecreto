<?php

    function gerarSerial($user,$controle,$dias)
    {
    $data = date("Y/m/d");
    $hash = $user."@".$controle."@".$data."@".$dias;
    
    $serial = base64_encode($hash);
    $check = base64_encode($serial);
    $serial = $serial."@".$check;
    return $serial;
    }
    
    function verificaSerial($serial)
    {
        $check = explode("@",$serial);
        if(count($check)==2)
        {
            $check[1]=base64_decode($check[1]);
            if($check[0]==$check[1]){
                return 1;
            }
            else
            {
                return 0;
            }
        }
        else
        {
            return 0;
        }
    }
    
    function getSerial($hash)
    {
        $check = explode("@",$hash);
        $check[1]=base64_decode($check[1]);
        if($check[0]==$check[1]){
            return $check[1];
        }
        else
        {
            return 0;
        }
    }    

?>
