<?php
   $host="localhost";
   $port="5432";
   $user="postgres";
   $pass="123456";
   $dbname="ejercicio";

   $conn_string = "host=localhost port=5432 dbname=ejercicio user=postgres password=123456 options='--client_encoding=UTF8'";
 
// establecemos una conexion con el servidor postgresSQL
   $dbconn = pg_connect($conn_string);

   //$connect = pg_connect("host=$host, port=$port, user=$user,pass=$pass, dbname=$dbname");

   if(!$dbconn) {
      echo "Error: No se ha podido conectar a la base de datos\n";
      } else {
      echo "Conexión exitosa\n";
      }
?>