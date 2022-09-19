<?php
    require("classes/conexão.class.php");
    
    $serial = "VGVzdGVAcGVpZG91IzMw";
    
    $serial2 = base64_decode($serial);
    
    echo $serial2;
    
    
?>