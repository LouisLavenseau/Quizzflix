<?php
    session_start();      
    require("includes/connect.php");

    if(isset($_POST['login'])&isset($_POST['mdp'])&isset($_POST['mdp2']))
    {
        $login=$_POST['login'];
        $mdp=$_POST['mdp'];
        $mdp2=$_POST['mdp2'];
                
        //vérif que le login n'existe pas déjà
        $requete = $BDD -> prepare("SELECT COUNT(*) FROM COMPTE WHERE Login=?");
        $requete-> execute(array($login));
        $verif = $requete->fetch()[0];

        if($verif==0&$mdp==$mdp2)
        {
            $BDD -> exec("INSERT INTO COMPTE VALUES ('$login','$mdp','joueur')");

            $_SESSION['login']=$login;            
            $_SESSION['mdp']=$mdp;
            header('location:index.php');
        }
        else
        {
            if($mdp!=$mdp2)
            {
                $message = "<h5 class='text-danger'>Veuillez écrire le même mot de passe dans les deux zones.</h5>";
            }
            if($verif!=0)
            {
                $message = "<h5 class='text-danger'>Ce login est déjà pris, veuillez en choisir un autre.</h5>";
            }
        }               
    }
    include("includes/barre.php");
?>

<html>
    <head>
        <title>Inscription</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="style/styleConnexion.css"/>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    </head>

    <body>
        <div id=grandConteneur>
        <div id='conteneur'>
            <h3>Inscription sur QUIZZFLIX</h3><br/><br/>
            <form method="POST" action="inscription.php">
                <label for="login">Login</label>
                <input style="color:#222222;" type='text' name='login'/>
                <br/><br/>
                <label for="mdp">Mot de passe</label>
                <input style="color:#222222;" type='password' name='mdp'/>
                <br/><br/>
                <label for="mdp2">Confirmation</label>
                <input style="color:#222222;" type='password' name='mdp2'/>
                <br/><br/><br/>
                <div id=bouton><button type="submit" class="btn btn-default"  value="M'inscrire">M'inscrire</button></div>
            </form>
            <?php
                if(isset($message))
                {
                    echo $message;
                }
            ?>
        </div> 
        </div>      
    </body>
    <?php include("includes/footer.html");?>
</html>