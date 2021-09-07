<?php
    session_start();   
    require("includes/connect.php");
    if(isset($_POST['login'])&isset($_POST['mdp']))
    {
        //vérification de l'existance du compte
        $requete = $BDD -> prepare("SELECT * FROM COMPTE WHERE Login=? AND Mdp=?");
        $requete -> execute(array($_POST['login'],$_POST['mdp']));
        $verif = $requete->fetch();

        if (isset($verif[0]))
        {
            $_SESSION['login']=$_POST['login'];            
            $_SESSION['mdp']=$_POST['mdp'];

            if($verif['Type']=='joueur')
            {
                if(isset($_GET['Numquizz'])) // si la personne voulait lancer un quizz on lance le quizz
                {
                    $difficulte=$_GET['difficulte'];
                    header('location:question.php?Numquizz='.$_GET['Numquizz'].'&difficulte='.$difficulte.'&Numquest=1&score=0');
                }
                else // sinon on va à l'accueil
                {
                    header('location:index.php');
                }
            }
            elseif($verif['Type']=='admin')
            {
                header('location:accueilAdmin.php');
            }
            
        }
        else
        {
            $message = "<h5 class='text-danger'>Ce compte n'existe pas</h5>";
        }
                
    }
    include("includes/barre.php");
            
    if (isset($_GET['difficulte']))
    {
        $difficulte = $_GET['difficulte'];
    }
?>

<html>
    <head>
        <title>Connexion</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="style/styleConnexion.css"/>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    </head>

    <body>
        <div id=grandConteneur>
        <div id=conteneur>
            <?php
                include("includes/fonctions.php"); 
                if(isset($_GET['deco']))
                {
                    if($_GET['deco']=="oui")
                    {
                        deconnexion();    
                        echo "<br/><h5 class='text-muted'>Vous n'êtes pas connecté, veuillez vous connecter pour accéder aux quizz.</h5><br/></br>"; 
                    }   
                }   
                elseif(empty($_SESSION['login']))
                {
                    echo "<br/><h5 class='text-muted'>Vous n'êtes pas connecté, veuillez vous connecter pour accéder aux quizz.</h5></br></br>"; 
                }                       
            ?>
    
    
            <form method="POST" action="connexion.php<?php if(isset($_GET['Numquizz'])){echo "?Numquizz=".$_GET['Numquizz']."&difficulte=".$_GET['difficulte'];} ?>">
                <label for="login">Login</label>
                <input style="color:#222222;" type='text' name='login'/>
                <br/><br/>
                <label for="mdp">Mot de passe</label>
                <input style="color:#222222;" type='password' name='mdp'/>
                <br/><br/><br/>
                <div id=bouton><button type="submit" class="btn btn-default" value="Se connecter">Se connecter</button></div>
            </form>

            <?php
                if(isset($message))
                {
                    echo $message;
                }
            ?>

            <h6>
                Pas de compte ? 
                <a href="inscription.php">Inscrivez vous</a>
            </h6>
                
            
        </div>
        </div>
    </body>
    <?php include("includes/footer.html");?>
</html>