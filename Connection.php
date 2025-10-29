<?php 
 try
 {
    $constring = "mysql:host=localhost;dbname=m4-store";
    $user = "root";
    $pass="";
    $con =new PDO($constring,$user,$pass);
//    echo "connect with pdo";

}
 catch(PDOException $e)
{

    exit ($e->getMessage() );
}


?>