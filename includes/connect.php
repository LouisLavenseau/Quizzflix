<?php
    try
    {
        $BDD=new PDO("mysql:host=localhost;
                    dbname=id12984956_quizzflix;
                    charset=utf8", 'id12984956_amandine','mdpquizzflix',
                    array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
    }
    catch(Exception $e){
        die('Erreur fatale : ' .$e->getMessage());
    }
?>