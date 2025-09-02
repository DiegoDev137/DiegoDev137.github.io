<?php
include("con_db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $name = mysqli_real_escape_string($conex, $_POST['name']);
    $email = mysqli_real_escape_string($conex, $_POST['email']);

    // Insertar datos en la base de datos
    $sql = "INSERT INTO usuarios (nombre, email) VALUES ('$name', '$email')";
    if (mysqli_query($conex, $sql)) {
        echo "Registro exitoso. ¡Bienvenido, $name!";
    } else {
        echo "Error al registrar: " . mysqli_error($conex);
    }
}

mysqli_close($conex);
?>