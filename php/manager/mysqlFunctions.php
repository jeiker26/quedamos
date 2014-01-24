<?php
	//conexion como global
	require("../utils/sql/mysqlConection.php");
        require('send_mail.php');

	$db = getConnet();

	function insertUser($name,$email,$password){
		global $db;
                if(mysqli_num_rows(get_data('user_name',$name)) === 0){
                    if(mysqli_num_rows(get_data('user_email',$email)) === 0){
                        $query = "INSERT into users (user_name,user_email,user_password) values('$name','$email','$password')";
                        $result = mysqli_query($db,$query);
                        $code = createToken();
                        if ($result) {
                            $cuerpo = 'Hola '.$name.', para activar tu cuenta haz click en el siguiente link:
                            ' . "\n" . '
                            <a href="http://http://localhost/git/GoogleMaps/Mapa,buscarNombre/activar.html?code=' . $code . '">http://localhost/git/GoogleMaps/Mapa,buscarNombre/activar.html?code=' . $code . '</a>
                            O copia el siguiente link en la barra de direcciones de tu navegador:
                            ' . "\n" . '
                            http://localhost/git/quedamos/php/manager/mapToro/activate/' . $code;

                           if(send_mail($email, $cuerpo)){
                                    $query_state_null = "UPDATE users SET user_state='$code' WHERE user_name='".$name."'";
                                    mysqli_query($db,$query_state_null);
                                    return 1;
                            }else{ 
                                return "El email introducido no es válido.";
                            }
                        }
                        else
                            return "Error en el insert";
                    }else{
                        return "El email ya esta en uso";
                    }
                }else{
                    return "El nombre ya esta en uso.";
                }
        }

	function logUser($email,$password){
		global $db;
		$query = "SELECT * FROM users WHERE user_email='$email' and user_password='$password'";
		$result = mysqli_query($db,$query);
                $row = mysqli_fetch_array($result);
                $user_state = $row['user_state'];
                if(mysqli_num_rows($result) != 0){
                    if($user_state == 'activate')
                        return "Bienvenido, ".$row['user_name'];
                    else
                        return 2;//Error: El usuario no esta activo.
                }else{
                    return 3;//Error: Los datos no son correctos.
                }
	}
        
        function activateUser($code){
                global $db;
		$query = "SELECT * FROM users WHERE user_state='$code'";
		$result = mysqli_query($db,$query);
                if($result) 
                    $row = mysqli_fetch_array($result);
                    $query_state_ok = "UPDATE users SET user_state='activate' WHERE user_name='".$row['user_name']."'";
                    mysqli_query($db,$query_state_ok);
                    header('Location: /git/quedamos/');
                    return $row['user_name']." ha sido activado.";
        }

        function createToken(){
            //Se genera en funcion del nombre, no puede haber dos nombres iguales
            return sha1(mt_rand() . time() . mt_rand() . $_SERVER['REMOTE_ADDR']);
        }
        
        
        function get_data($campo,$var_campo){
            global $db;
            $query = "SELECT * FROM users WHERE $campo='$var_campo'";
            $result = mysqli_query($db,$query);
            return $result;
        }
        
        function forgetPass($mail){
            if(mysqli_num_rows(get_data('user_email',$mail)) === 0){
                return "No hay ningún usuario registrado con ese email.";
            }else{
                $content_user_data = mysqli_fetch_array(get_data('user_email',$mail));
                $cuerpo = "Hola tu contraseña es".$content_user_data['user_password'];
                send_mail($mail, $cuerpo);
            }
        }


        //insertUser('test','jesusgraficap@gmail.com','test');
	
?>