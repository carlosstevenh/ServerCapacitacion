<?php
    $conn_string = "host=localhost port=5432 dbname=ejercicio user=postgres password=123456 options='--client_encoding=UTF8'";
    $dbconn = pg_connect($conn_string);

    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
    header("Allow: GET, POST, OPTIONS, PUT, DELETE");
    $method = $_SERVER['REQUEST_METHOD'];
    if($method == "OPTIONS") {
        die();
    }
    
    require_once 'vendor/autoload.php';
    $app = new \Slim\Slim();

    $app -> get("/pruebas", function()use($app){
        echo "Hola mundo aaa";
    });

    //Registrar usuarios
    $app -> post("/usuarios", function()use($app, $dbconn){
        $json = $app->request->post('json');
        //var_dump($app->request);
        $data = json_decode($json,true);
        $result = array(
            'status' => 'success',
            'code' => 200,
            'messege' => 'Usuario registrado'
        );
        $query = "INSERT INTO usuarios (tipoIdentificacion,identificacion,nombre,apellido,fechaNacimiento,genero) 
            VALUES ('{$data['tipoIdentificacion']}','{$data['identificacion']}','{$data['nombre']}','{$data['apellido']}','{$data['fechaNacimiento']}','{$data['genero']}')";
        
        $insert = pg_exec($dbconn,$query);
        if(!$insert){
            $result = array(
                'status' => 'error',
                'code' => 400,
                'messege' => 'Usuario no registrado'
            );
        };
        echo json_encode($result);
    });

    //Listar Usuarios
    $app -> get("/usuarios", function()use($app, $dbconn){
        $query = "SELECT * FROM USUARIOS";
        $select = pg_query($dbconn,$query);

        $usuarios = array();
        while ($row = pg_fetch_row($select)) {
            
            $usuario = array(
                'tipoIdentificacion' => $row[1],
                'identificacion' => $row[2],
                'nombre' => $row[3],
                'apellido' => $row[4],
                'fechaNacimiento' => $row[5],
                'genero' => $row[6]
            );
            
            $usuarios[] = $usuario;
        }
        $result = array(
            'status' => 'success',
            'code' => 200,
            'data' => $usuarios
        );
        echo json_encode($result);
    });

    //Buscar Usuarios
    $app -> get("/usuario/:ide", function($ide)use($app, $dbconn){
        $query = "SELECT * FROM USUARIOS WHERE identificacion = '".$ide."'";
        $select = pg_query($dbconn,$query);
        $ContactNum = pg_NumRows($select);

        $result = array(
            'status' => 'error',
            'code' => 404,
            'message' => 'No se econtraron datos'
        );
        
        if($ContactNum == 1){
            $row = $row = pg_fetch_assoc($select);
            $result = array(
                'status' => 'success',
                'code' => 200,
                'data' => $row
            );
        }
        
        echo json_encode($result);
    });

     //Registrar usuarios
     $app -> post("/save", function()use($app, $dbconn){
        $json = $app->request->getBody();
        $data = json_decode($json,true);
        $result = array(
            'status' => 'success',
            'code' => 200,
            'messege' => 'Usuario registrado'
        );
        $query = "INSERT INTO usuarios (tipoIdentificacion,identificacion,nombre,apellido,fechaNacimiento,genero) 
            VALUES ('{$data['tipoIdentificacion']}','{$data['identificacion']}','{$data['nombre']}','{$data['apellido']}','{$data['fechaNacimiento']}','{$data['genero']}')";
        
        $insert = pg_exec($dbconn,$query);
        if(!$insert){
            $result = array(
                'status' => 'error',
                'code' => 400,
                'messege' => 'Usuario no registrado'
            );
        }
        echo json_encode($result);
    });



    $app -> run();
?>