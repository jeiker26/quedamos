<?php
	//conexion como global
	require("../utils/sql/mysqlConection.php");
        require('send_mail.php');

	$db = getConnet();

	function insertUser($name,$email,$password){
		global $db;
                $query_tester_name = "SELECT * FROM users WHERE user_name='$name'";
                if(!mysqli_query($db,$query_tester_name)){                    return "nombre libre";
                    /*$query_tester_email = "SELECT * FROM users WHERE user_email='$email'";
                    $result_tester_email = mysqli_query($db,$query_tester_email);
                    if($result_tester_email){
                        $code = createToken();
                        if ($result) {
                            $cuerpo = 'Hola '.$name.', para activar tu cuenta haz click en el siguiente link:
                            ' . "\n" . '
                            <a href="http://http://localhost/git/GoogleMaps/Mapa,buscarNombre/activar.html?code=' . $code . '">http://localhost/git/GoogleMaps/Mapa,buscarNombre/activar.html?code=' . $code . '</a>
                            O copia el siguiente link en la barra de direcciones de tu navegador:
                            ' . "\n" . '
                            http://localhost/git/GoogleMaps/Mapa,buscarNombre/php/manager/mapToro/activar/code=' . $code;

                           if(send_mail($email, $cuerpo)){
                                    $query_state_null = "UPDATE users SET user_state='$code' WHERE user_name='".$name."'";
                                    mysqli_query($db,$query_state_null);
                                    return "Mensaje de activacion enviado a su bandeja de entrada";
                            }else{ 
                                return "El email introducido no es válido.";
                            }
                        }
                        else
                            return "Error en el insert";
                    }else{
                        return "El email ya esta en uso";
                    }
                */}else{
                    return "El nombre ya esta en uso.";
                }
        }

	function logUser($email,$password){
		global $db;
		$query = "SELECT * FROM users WHERE user_email='$email' and user_password='$password'";
		$result = mysqli_query($db,$query);
		$row = mysqli_fetch_array($result);
                if($row['user_state'] == 'activate')
                    return "Bienvenido, ".$row['user_name'];
                else
                    return "El usuario ".$row['user_name'].", no ha sido activado.";
	}
        
        function activateUser($code){
                global $db;
		$query = "SELECT * FROM users WHERE user_state='$code'";
		$result = mysqli_query($db,$query);
                if($result) 
                    $row = mysqli_fetch_array($result);
                    $query_state_ok = "UPDATE users SET user_state='activate' WHERE user_name='".$row['user_name']."'";
                    mysqli_query($db,$query_state_ok);
                    return $row['user_name']." ha sido activado.";
        }

        function createToken(){
            //Se genera en funcion del nombre, no puede haber dos nombres iguales
            return sha1(mt_rand() . time() . mt_rand() . $_SERVER['REMOTE_ADDR']);
        }
        //insertUser('test','jesusgraficap@gmail.com','test');
        
        function get_data($campo,$var_campo){
            global $db;
            $query = "SELECT * FROM users WHERE $campo='$var_campo'";
            $result = mysqli_query($db,$query);
            $row = mysqli_fetch_array($result);
            echo $row[$campo];
        }
        if(get_data('user_name','Jesasdus')){
            echo "ok";
        }else{
            echo "no";
        }
	
?>