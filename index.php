<?php 
// START SESSION
session_start();

if(!empty($_FILES["logo"])){
    $tmp = $_FILES["logo"]["tmp_name"];
    $src = getcwd()."/src/image/logo.png";

    if(move_uploaded_file($tmp, $src)){
        header("location: index.php");
    }
}else{

}


// VERIFICATION UTILISATEURS
if(empty($_SESSION["login"])){
    // REDIRECTION NON MEMBRES VERS CONNEXION
    header("Status: 301 Moved Permanently");
    header("location:php/connexion.php");
    exit();

}else{
    // ACCUEIL MEMBRES
    $login = $_SESSION["login"];
    if(!empty($_SESSION["connexion"])){
        $acueil = "Salut ".$login." 😉 Un plaisir de te revoir ! [".$_SESSION['type']."]";

    // ACCUEIL NOUVEAUX MEMBRES
    }else if(!empty($_SESSION["inscription"])){
        $acueil = "Bienvenu ".$login."🤠 Votre compte est désormais activé ✅[".$_SESSION['type']."]";
    }
}

// START CONNEXION DB
$db = @mysqli_connect("localhost","root","","quiz");

// VERIFICATION CONNEXION DB
if($db){
    $upload = "";
    // TRI PAR ORDRE
    if(!empty($_GET["tri"])){
        $upload = "hidden";
        $retour = "";
        $menu = "";
        $tri = $_GET["tri"];

        // AUTEUR ALPHABETIQUE
        if($tri == "ac"){
            $requete = "select";
            $message= "Les quiz sont triés par auteur et par ordre alphabétique";
            $query = "SELECT `user`.username, `titre`, `dateCreation` FROM `quiz` JOIN `user` ON `user_id` = `user`.id ORDER BY `user`.`username` ASC;";
       
        // AUTEUR ALPHABETIQUE INVERSE
        }else if($tri == "ad") {
            $requete = "select";
            $message="Les quiz sont triés par auteur et par ordre alphabétique inverse";
            $query = "SELECT `user`.username, `titre`, `dateCreation` FROM `quiz` JOIN `user` ON `user_id` = `user`.id ORDER BY `user`.`username` DESC;";
        
        // TITRE ALPHABETIQUE
        }else if($tri == "tc") {
            $requete = "select";
            $message="Les quiz sont triés par titre et par ordre alphabétique";
            $query = "SELECT `user`.username, `titre`, `dateCreation` FROM `quiz` JOIN `user` ON `user_id` = `user`.id ORDER BY `titre` ASC;";
        
        // TITRE ALPHABETIQUE INVERSE
        }else if($tri == "td") {
            $requete = "select";
            $message="Les quiz sont triés par titre et par ordre alphabétique inverse";
            $query = "SELECT `user`.username, `titre`, `dateCreation` FROM `quiz` JOIN `user` ON `user_id` = `user`.id ORDER BY `titre` DESC;";
        
        // DATE CROISSANT
        }else if($tri == "dc") {
            $requete = "select";
            $message="Les quiz sont triés par date et par ordre croissant";
            $query = "SELECT `user`.username, `titre`, `dateCreation` FROM `quiz` JOIN `user` ON `user_id` = `user`.id ORDER BY `dateCreation` ASC;";
        
        // DATE DECROISSANT
        }else{
            $requete = "select";
            $message="Les quiz sont triés par date et par ordre décroissant";
            $query = "SELECT `user`.username, `titre`, `dateCreation` FROM `quiz` JOIN `user` ON `user_id` = `user`.id ORDER BY `dateCreation` DESC;";
        }     

    // RECHERCHE PAR MOT CLEF
    }else if(!empty($_GET["mc_titre"])){
        $requete = "select";
        $upload = "hidden";
        $retour = "";
        $menu = "";
        $mc_titre = $_GET["mc_titre"]; 
        $message="Les quiz sont triés par titre avec le mot clef : ".$mc_titre;
        $query = "SELECT `user`.username, `titre`, `dateCreation` FROM `quiz` JOIN `user` ON `user_id` = `user`.id WHERE `titre` LIKE '%".$mc_titre."%'";
        
    // FILTRE PAR AUTEUR
    }else if(!empty($_GET["f_auteur"])){
        $requete = "select";
        $upload = "hidden";
        $retour = "";
        $menu = "";
        $f_auteur = $_GET["f_auteur"]; 
        $message="Les quiz sont triés avec le nom d'auteur : ".$f_auteur;
        $query = "SELECT `user`.username, `titre`, `dateCreation` FROM `quiz` JOIN `user` ON `user_id` = `user`.id WHERE `user`.username LIKE '%".$f_auteur."%'";
        
    // SELECTION QUIZ
    }else if(!empty($_GET["detail"])){
        $requete = "select";
        $upload = "hidden";
        $retour = "";
        $menu = "hidden";
        $detail = $_GET["detail"]; 
        $message="Détail du quiz : ".$detail;
        $query = "SELECT `quiz`.id, `user`.username, `titre`, `dateCreation`, `illustration`, `uq_evaluation`.evaluation, `uq_participation`.score FROM `quiz` JOIN `user` ON `user_id` = `user`.id JOIN `uq_evaluation` ON `quiz`.`user_id` = `uq_evaluation`.`user_id` JOIN `uq_participation` ON `quiz`.`user_id` = `uq_participation`.`user_id` WHERE `titre` LIKE '".$detail."';";
        
    // LANCEMENT QUIZ
    }else if(!empty($_GET["choix"])){
        $requete = "select";
        $upload = "hidden";
        $retour = "";
        $menu = "hidden";
        $choix = $_GET["choix"]; 
        $query = "SELECT `question`.titre, `question`.type, `question`.reponses FROM `question` JOIN `quiz_question` ON `question`.id = `question_id` JOIN `quiz` ON `quiz_id` = `quiz`.id WHERE `quiz`.id LIKE ".$choix.";";
        $message="QUIZ : ".$_GET["titre"];
    
    // REPONSE QUIZ
    }else if(!empty($_GET["respond"])){
        $requete = "select";
        $upload = "hidden";
        $retour = "";
        $menu = "hidden";
        $choix = $_GET["respond"];
        $query = "SELECT `question`.bonneReponse FROM `question` JOIN `quiz_question` ON `question`.id = `question_id` JOIN `quiz` ON `quiz_id` = `quiz`.id WHERE `quiz`.id LIKE ".$choix.";";
        $message = "RESULTAT";

    // EVALUATION
    
    }else if(!empty($_GET["evaluation"])){
        $requete = "update";
        $upload = "hidden";
        $retour = "";
        $menu = "hidden";
        $evaluation = $_GET["evaluation"];
        $message = "EVALUATION";
        $quiz = $_GET["jeu"];

        $query = "SELECT `evaluation` FROM `uq_evaluation` WHERE `user_id` = (SELECT `id` FROM `user` WHERE `username` = '".$login."');";
        $result = mysqli_fetch_assoc(mysqli_query($db,$query));

        if(isset($result["evaluation"])){
            
            $query = "UPDATE `uq_evaluation` SET `evaluation` = '$evaluation' WHERE `user_id` = (SELECT `id` FROM `user` WHERE `username` = '".$login."');";
            mysqli_query($db,$query);

        }else{
            $requete = "insert";
            $query = "SELECT `id` FROM `user` WHERE `username` = '".$login."';";
            $result = mysqli_fetch_assoc(mysqli_query($db,$query));

            $query = "INSERT INTO `uq_evaluation` (`user_id`, `quiz_id`, `evaluation`, `dateEvaluation`) VALUES(".$result['id'].",$quiz,$evaluation,NOW());";
            mysqli_query($db,$query);
        }

    // TRI PAR DEFAUT
    }else{
        $requete = "select";
        $upload = "";
        $retour = "hidden";
        $menu = "";
        $message="Les quiz sont triés par date et par ordre croissant";
        $query = "SELECT `user`.username, `titre`, `dateCreation` FROM `quiz` JOIN `user` ON `user_id` = `user`.id ORDER BY `dateCreation` ASC;";
    }

    // RESULTAT REQUETE
    if($requete == "select"){
            $result = mysqli_query($db,$query);
    while($row = mysqli_fetch_assoc($result)){
        $quiz[] = $row;
    }
    mysqli_free_result($result);
    }


    // LIBERER RESULTAT
    
    
    // RECUPERER AUTEURS
    $query = "SELECT DISTINCT `username` FROM `user` JOIN `quiz` ON `user`.id = `user_id`;";
    $result = mysqli_query($db,$query);
    while($row = mysqli_fetch_assoc($result)){
        $auteurs[] = $row;
    }

    // STOP CONNEXION DB
    mysqli_free_result($result);
    mysqli_close($db);

}else{
    // ERROR DB
    echo "La connexion à la base de donnée à échouer ❌";
}

    function reponse_verification($quiz){
        $compteur = 0;
        for($i = 0; $i< count($quiz); $i++){
            if($_GET[$i] == $quiz[$i]["bonneReponse"]){
                $compteur++;
            }
        }
        if($compteur == count($quiz)){
            $resultat = "Félicitation vous avez réussis ce quiz 🥳";
        }else{
            $resultat = "Dommage vous n'avez pas réussis ce quiz 😭";
        }

        return $resultat;
    }

    function reponse_transform($i, $quiz){
        $before = $quiz[$i]["reponses"];
        $after = str_replace(array('"',":","{","}","'","0"), "", $before);
        $after =  preg_replace('#[0-9 ]*#', '', $after);
        $reponse = "";
        
        for($e = 0; $e < strlen($after); $e++){
            if($after[$e] == ","){
                $tab[] = $reponse;
                $reponse = "";
        
            }else if($e == strlen($after)-1){
                $tab[] = $reponse;
                $reponse = "";
                
            }else{
                $reponse .= $after[$e];
            }
        }
        return $tab;
    }

?>

<!DOCTYPE html>

<html>
    <head>
        <link rel="stylesheet" href="css/style.css">
        <meta charset="utf-8">
        <title> Accueil : Quiz </title>
    </head>

    </body>
        <header id="container_header"> 
                <p> <a href="index.php"> <img id="logo" src="src/image/logo.png" alt="logo quiz"></a> </p>
                <p class="p_header"> Notre plateforme vous permet d'apprendre tout en vous amusant 🎉 </p>
        </header>

        <main id="container_main">
            <div>
             <!-- BOUTON DECONNEXION -->
             <div> <p><a id ="deconnexion" href="php/connexion.php"> Déconnexion </a></p>

             <?php if($_SESSION["type"]=="admin"){ ?>
                <form class="<?= $upload; ?>" enctype="multipart/form-data" action="index.php" method="post">
                <input type="hidden" name="MAX_FILE_SIZE" value="300000" /> 
                <label> Changer le logo (png/jpg/jpeg)</label>
                <input type="file" name="logo" accept="image/png, image/jpg, image/jpeg">
                <input type="submit">
                </form>
             <?php } ?>
            <form action=""></form> 
            </div>
        
<div class="<?= $menu;?>">
<!-- MESSAGE ACCUEIL -->
<?php 
if(!empty($acueil)){?>
    <p> <?= $acueil; ?> </p>
<?php } ?>

<!-- TRI PAR ORDRE -->
<form action="index.php" method="get">
    <select name="tri">
        <option> Choisir un ordre </option>
        <option value="ac">Auteur par ordre alphabétique</option>
        <option value="ad">Auteur par ordre alphabétique inverse</option>
        <option value="tc">Titre par ordre alphabétique</option>
        <option value="td">Titre par ordre alphabétique inverse</option>
        <option value="dc">Date par ordre croissant</option>
        <option value="dd">Date par ordre décroissant</option>
    </select>

    <input type="submit" value="Trier">
</form>

<!-- RECHERCHE PAR MOT CLEF -->
<form action="index.php" method="get">
    <input type="text" name="mc_titre" placeholder="Inscrire un titre">
    <input type="submit" value="Chercher">
</form>

<!-- FILTRE PAR AUTEUR -->
<form action="index.php" method="get">
    <select name="f_auteur" >
        <option> Trouver un auteur </option>
        <?php for($i = 0; $i < count($auteurs); $i++){ ?>
            <option value="<?= $auteurs[$i]['username'];?>"> <?= $auteurs[$i]["username"];?></option>
        <?php } ?> 
    </select>

    <input type="submit" value="Filter">
</form>

<!-- MESSAGE TRI -->
<p><?= $message?></p>
        </div>

<!-- LISTE QUIZ -->
<ul>
    <?php 
    if(!empty($quiz)){
     
        if(!empty($detail)){ ?> 
        <?php for($i=0; $i < count($quiz); $i++){ ?>
            <li> <?= $quiz[$i]["titre"] ?> 
                <ul> 
                    <li> Auteur : <?= $quiz[$i]["username"]; ?> </li>
                    <li> Publication : <?= $quiz[$i]["dateCreation"]; ?> </li> 
                    <li> Score : <?= $quiz[$i]["score"]; ?> </li>
                    <li> Evaluation : <?= $quiz[$i]["evaluation"]; ?> </li>
                </ul> 
                <p>
                    <img style="border-radius: 7px; width: 250px; height: auto" src="<?= $quiz[$i]['illustration']; ?>" alt="<?= $quiz[$i]['titre']; ?>">
                </p>

                <form action="index.php" method="get">
                    <input hidden type="text" name="choix" value="<?= $quiz[$i]['id']; ?>">  
                    <input hidden type="text" name="titre" value="<?= $quiz[$i]['titre']; ?>">  
                    <input type="submit" value="JOUER">
                </form>
            </li>
            <?php } ?>
            <p><a href="index.php" class="retour"> retour </a></p>
            <?php }else if(!empty($_GET["choix"])){ ?>
                <form action="index.php" method="get">
                <?php for($i=0; $i < count($quiz); $i++){ ?>
               <li>  <?= $quiz[$i]["titre"]; ?>
                    <ul>
                                <?php $tab_reponse = reponse_transform($i, $quiz);
                                for($e = 0; $e < count($tab_reponse); $e++){ ?>
                                    <li>
                                        <label><?= $tab_reponse[$e]; ?> </label>
                                        <input type="radio" name="<?= $i; ?>" value="<?= $e; ?>">
                                    </li> 
                                <?php } ?> 
                    </ul>
                </li>
                <?php } ?>
                <input type="hidden" name="respond" value="<?= $choix;?>">
                <input type="submit" value="Envoyer">
                            </form>
                            <p><a href="index.php" class="retour"> retour </a></p>
                            <?php } else if(!empty($_GET["respond"])){ ?>
                                <?php $point = reponse_verification($quiz); ?>
                                <p><?= $point; ?></p>
                                <p>EVALUATION</p>
                                <p><a href="index.php?evaluation=1&jeu=1"> ⭐ </a>
                                <a href="index.php?evaluation=2&jeu=1"> ⭐ </a>
                                <a href="index.php?evaluation=3&jeu=1"> ⭐ </a>
                                <a href="index.php?evaluation=4&jeu=1"> ⭐ </a>
                                <a href="index.php?evaluation=5&jeu=1"> ⭐ </a></p>
                                <p><a href="index.php" class="retour"> retour </a></p>

                                <?php } else if(!empty($_GET["evaluation"])){ ?>
                                <p> Votre évaluation de <?= $evaluation;?>/5  a été enregistrer 🌟 </p>
                                <p><a href="index.php" class="retour"> retour </a></p>
                                
            
        <?php } else { ?>
            <?php for($i=0; $i < count($quiz); $i++){ ?>
            <li> 
                <a href="index.php?detail=<?= $quiz[$i]['titre'];?>"><?= $quiz[$i]["titre"] ?></a> 
                <ul> 
                    <li> Auteur : <?= $quiz[$i]["username"]; ?> </li>
                    <li> Publication : <?= $quiz[$i]["dateCreation"]; ?> </li> 
                </ul> 
            </li>
        <?php }?>
        <p><a href="index.php" class="<?= $retour; ?>"> retour </a></p>
        <?php }?>
</ul>

<!-- MESSAGE QUIZ NON TROUVER -->
<?php }else{ ?>
    <p> ❌ Aucuns quiz correspondant n'a été trouver! </p>
    <p><a href="index.php" class="retour"> retour </a></p>
<?php } ?>         
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