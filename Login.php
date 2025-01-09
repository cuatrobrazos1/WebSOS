<?php
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(isset($_POST["correo"]) && isset($_POST["contraseña"])){
            $mail = $_POST["correo"];
            $pass = $_POST["contraseña"];
        }
    }

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "vitalsos";

    $mysqli = new mysqli($servername, $username, $password, $dbname);

    if (!$mysqli) {
        die("Ha fallado la conexion: " . mysqli_connect_error());
    }

    $new_pass = md5($pass);
    $sql = "SELECT nombre, telefono_emergencia
		FROM usuarios
		WHERE contrasena = '$new_pass' AND mail = '$mail'
		";

    $result = $mysqli->query($sql);

    $numRows = mysqli_num_rows($result);
    if($numRows == 0){
        echo "<p>El correo o la contraseña no son válidos.</p>";
    }else{
        $row = mysqli_fetch_assoc($result);
        $nom = $row["nombre"];
        $tel = $row["telefono_emergencia"];
        header("Location: PERSONAL.php?nombre=$nom&mail=$mail&tel=$tel");
    }
?>