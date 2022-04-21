<?php 
session_start();
unset($_SESSION["login"]);
unset($_SESSION["connexion"]);
unset($_SESSION["inscription"]);
unset($_SESSION["type"]);

$etat_serveur;

$db = @mysqli_connect("localhost", "root", "", "quiz");

if($db){
    if(!empty($_POST["login"])){

        $email = $_POST["email"];
        $email_c = $_POST["email_c"];

        if($email == $email_c){
            $login = $_POST["login"];
            $mdp = $_POST["mdp"];
            $mdp_hash = password_hash($mdp, PASSWORD_DEFAULT);

            $query = "SELECT COUNT(*) as 'doublon' FROM `user` WHERE `email` LIKE '".$email."';";
            $result = mysqli_fetch_assoc(mysqli_query($db,$query));
    
             if($result["doublon"]==0){
    
                 $query = "INSERT INTO `user`(`username`,`password`,`type`,`activated`,`email`,`dateInscription`) VALUES ('".$login."','".$mdp_hash."','"."membre"."','"."Y"."','".$email."', NOW());";
                 $result = mysqli_query($db, $query);

                 mysqli_close($db);

                 $_SESSION["login"] = $login;
                 $_SESSION["inscription"] = 1;
                 $_SESSION["type"] = "membre";

                 header("Status: 301 Moved Permanently");
                 header("location:../index.php");
                 exit();
    
             }else{
                 mysqli_close($db);
                 $inscription_message = "Un compte existant utilise déjà cette adresse email ❌";
             } 
    
        }else{
            $inscription_message = "Votre email doit être identique au mail de confirmation ❗";
        }

    }else{
        $inscription_message = "INSCRIPTION";
    }

}else{
    $etat_serveur="Connexion échoué ❌";
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <link rel="stylesheet" href="../css/style.css">
    <meta charset="UTF-8">
    <title>Inscription</title>
</head>
<body>
<header id="container_header"> 
                <p> <a href="connexion.php"> <img id="logo" src="../src/image/logo.png" alt="logo quiz"></a> </p>
                <p class="p_header"> Notre plateforme vous permet d'apprendre tout en vous amusant 🎉 </p>
        </header>
        <main id="container_main"> 
            <div class="main_content_inscription main_content">
    <?php 
if(!empty($inscription_message)){ ?>
 <p id="message_log"> <?= $inscription_message; ?> </p>
<?php } ?>

    <form action="inscription.php" method="post">

        <div>
            <label> Login </label>
            <input type="text" name="login" required>
        </div>

        <div>
            <label> Mot de passe </label>
            <input type="password" name="mdp" required>
        </div>

        <div>
            <label> Email </label>
            <input type="email" name="email" required>
        </div>

        <div>
            <label> Confirmation email </label>
            <input type="email" name="email_c" required>
        </div>

        <div>
            <input type="submit" value="S'inscrire" required>
        </div>
    
    </form>

<p>Vous avez déjà un compte ? <a href="connexion.php"> Connectez-vous</a></p>
   <div>
</main> 

<footer id="container_footer">
                <p> Copyright © Hamza Echamlali </p>
                <p> 
                    <a id="condition" href="#"> conditions d'utilisation </a>
                </p> 
        </footer>
</body>
</html>
