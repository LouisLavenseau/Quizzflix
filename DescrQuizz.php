<?php
    session_start();
    require("includes/connect.php");
    $Numquizz=$_GET['Numquizz'];
    
    if (isset($_POST['LancerQuizz'])) // si on a appuyé sur lancer le quizz                                        
    {
        if(isset($_POST['difficulte'])) // et qu'on a bien choisi une difficulté
        {
            $difficulte=$_POST['difficulte'];
            if(isset($_SESSION['login'])) // et qu'on est connecté, alors on peut commencer le quizz
            { 
                header('location:question.php?Numquizz='.$Numquizz.'&Numquest=1&score=0&difficulte='.$difficulte);
            } 
            else // sinon il faut se connecter avant
            {
                header('location:connexion.php?Numquizz='.$Numquizz.'&difficulte='.$difficulte);
            }
        }                   
        else // et si on a pas choisi de difficulté on reste sur la page et il faudra écrire un message d'erreur
        {
            $message = "<h5 class='text-danger'>Veuillez sélectionner un niveau de difficulté pour lancer le quizz.</h5>";
        }
    }
    include("includes/barre.php");
?>


<html>
    <head>
        <link rel="stylesheet" href="style/styleDescrQuizz.css"/>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>        
        <?php
            // affichage Nom du quizz           
            $requete = $BDD -> prepare("SELECT * FROM QUIZZ WHERE Numquizz=?");
            $requete -> execute(array($Numquizz));
            $ligne=$requete->fetch();
            $nomQuizz = $ligne['Nomquizz'];
            echo "<title>".$ligne['Nomquizz']."</title>";
        ?>
    </head>
    
    <body>
            <div id=conteneur>
                <br/>
                <div class='elmt' id='image'><img class='image' src= images/<?php echo $ligne['Image'];?> alt='<?php echo $nomQuizz;?>' /></div>
                <div class='elmt' id='descr'><p><h3><?php echo $ligne['Description'];?></p></h3></div>
                    <?php if(isset($message)) echo $message; ?>
                    <form method="post" action="DescrQuizz.php<?php echo'?Numquizz='.$Numquizz;?>">
                        <div id="choix"><h4>
                            <input type="radio" name="difficulte" id="facile" value="facile"/>
                            <label for ="facile">Facile</label>
                            <input type="radio" name="difficulte" id="moyen" value="moyen"/>
                            <label for ="moyen">Moyen</label>
                            <input type="radio" name="difficulte" id="difficile" value="difficile"/>
                            <label for ="difficile">Difficile</label>
                        </h4></div>  
                        <div id="bouton">
                            <button type="submit" class="btn btn-default" name="LancerQuizz" value="Lancer le quizz">Lancer le quizz</button>
                        </div>
                    </form>      
            </div>         
    </body> 
    <?php include("includes/footer.html");?>    
</html>