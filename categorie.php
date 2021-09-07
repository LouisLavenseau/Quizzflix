<?php
    session_start();
    require("includes/connect.php");
    include("includes/barre.php");
    $categorie=$_GET['categorie'];
?>

<html>
    <head>
        <title><?php echo $categorie;?></title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="style/styleAccueil.css"/>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    </head>

    <body>        
        <div id=grandConteneur>
            <h1> <?php echo $categorie;?> </h1>
            <div id='conteneur'>
                <?php                                        
                    //affichage des quizz
                    $requete = $BDD -> prepare("SELECT * FROM QUIZZ WHERE Categorie_1=? OR Categorie_2=? OR Categorie_3=?");
                    $requete->execute(array($categorie,$categorie,$categorie));
                    while ($ligne = $requete->fetch())
                    {
                        $nomQuizz = $ligne['Nomquizz'];
                        echo '<div class="quizz"><a href="DescrQuizz.php?Numquizz='.$ligne['Numquizz'].'"><input class="image" type="image" src=images/'.$ligne['Image'].' value='.$ligne['Numquizz'].' title="'.$nomQuizz.'" /></a></div>';
                    }
                ?>
            </div>
        </div>
    </body>
    <?php include("includes/footer.html");?>
</html>