<?php 
session_start();
unset($_SESSION["login"]);
unset($_SESSION["connexion"]);
unset($_SESSION["inscription"]);
unset($_SESSION["type"]);

$db = @mysqli_connect("localhost","root","","quizz");
$message;


if($db){

    if(!empty($_POST["login"])){
        $login = $_POST["login"];
        $mdp = $_POST["mdp"];

        $query = "SELECT `password`, `type` FROM `user` WHERE `username` LIKE '".$login."';";
        $result = mysqli_fetch_assoc(mysqli_query($db, $query));
        
        if(!empty($result)){
            $hash = $result["password"];

        if(password_verify($mdp,$hash)){
            $_SESSION["login"] = $login;
            $_SESSION["connexion"] = 1;

            if($result["type"] == "admin"){
                $_SESSION["type"] = "admin";
            }else{
                $_SESSION["type"] = "membre";
            }

            mysqli_close($db);

            header("Status: 301 Moved Permanently");
            header("location:../index.php");
            exit();
        }else{
            $message = "Les informations de connexion sont érronées ❌";
        }

        }else{
            $message = "Les informations de connexion sont érronées ❌";

        }
        

    }else{
        $message = "CONNEXION";
    }

}else{

}
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <link rel="stylesheet" href="../css/style.css">
        <meta charset="UTF-8">
        <title>Connexion</title>
    </head>

    <body>

    <header id="container_header"> 
    <p> <a href="connexion.php"> <img id="logo" src="../src/image/logo.png" alt="logo quiz"></a> </p>
                <p class="p_header"> Notre plateforme vous permet d'apprendre tout en vous amusant 🎉 </p>
        </header>
        <main id="container_main"> 
            <div class="main_content_connexion main_content">

    <?php 
        if(!empty($message)){?>
        <p id="message_log"> <?= $message; ?> </p>
       <?php }else{
        }
        ?>

        <form action="connexion.php?" method="post">
            <div>
                <label> Login </label>
                <input type="text" name="login" required>
            </div>

            <div>
                <label> Mot de passe</label>
                <input type="password" name="mdp" required>
            </div>

            <input type="submit" value="Se connecter">
        </form> 

        <p>Vous n'avez pas de compte ? <a href="inscription.php"> Inscrivez-vous</a></p>
        </div>
    </main>
        <footer id="container_footer">
                <p> Copyright © Hamza Echamlali </p>
                <p> 
                    <a id="condition" href="#"> conditions d'utilisation </a>
                </p> 
        </footer>
    </body>
</html>