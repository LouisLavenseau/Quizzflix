<?php
    session_start();
    require("includes/connect.php");
    include("includes/barre.php");    
    include("includes/fonctions.php");
?>

<!DOCTYPE html>
<html>

    <head>
        <title>Quizzflix</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="style/styleAccueil.css"/>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    </head>

    <body>
        <div id=grandConteneur>
                <?php 
                    //récup de tous les quizz dans le tableau $lignes
                    $requete = "SELECT * FROM QUIZZ";
                    $resultat = $BDD -> query($requete);
                    $k=0;
                    while ($ligne = $resultat->fetch())
                    {
                        $lignes[$k]=$ligne;
                        $k++;                       
                    }
                    
                    //nombre de quizz
                    $nbQuizz=count($lignes);

                    //occurences de chacun des quizz
                    for($i=0;$i<$nbQuizz;$i++)
                    {
                        $requete = $BDD -> prepare("SELECT COUNT(*) FROM HISTORIQUE WHERE Quizz=?");
                        $requete -> execute(array($i+1));
                        $occurences[$i]=$requete->fetch()[0];   
                    }

                    $quizzPopulaires = tableauQuizzRecurents($occurences,$nbQuizz,$lignes);
                ?>

                <h3>Les plus gros succès de Quizzflix</h3><br/>
                <div id="carouselPopulaire" class="carousel slide" data-pause="">
                    <div class="carousel-inner" role="listbox">
                        <?php

                            $i=0;
                            echo '<div class="item active"><div class="serie">';
                                for($n=0;$n<4;$n++)
                                {
                                    $quizz=$quizzPopulaires[$i];
                                    $i++;
                                    echo '<a href="DescrQuizz.php?Numquizz='.$quizz['Numquizz'].'"><input class="image" type="image" src=images/'.$quizz['Image'].' value='.$quizz['Numquizz'].' title="'.$quizz['Nomquizz'].'" /></a>';
                                }
                                echo'</div></div>';
                            while($i<8)
                            {
                                echo '<div class="item"><div class="serie">';
                                for($n=0;$n<4;$n++)
                                {
                                    $quizz=$quizzPopulaires[$i];
                                    $i++;
                                    echo '<a href="DescrQuizz.php?Numquizz='.$quizz['Numquizz'].'"><input class="image" type="image" src=images/'.$quizz['Image'].' value='.$quizz['Numquizz'].' title="'.$quizz['Nomquizz'].'" /></a>';
                                }
                                echo'</div></div>';
                            }
                        ?>
                    </div>
                    <a class="left carousel-control" href="#carouselPopulaire" data-slide="prev">
                        <span class="glyphicon glyphicon-chevron-left"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="right carousel-control" href="#carouselPopulaire" data-slide="next">
                        <span class="glyphicon glyphicon-chevron-right"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div><br/><br/>

                

                <h3>Tendances actuelles</h3><br/>
                <?php
                    //date d'il y a une semaine
                    $date=(date("Y-m-d", strtotime("-1 weeks")));

                    //occurences de chacun des quizz réalisés cette semaine                   
                    for($i=0;$i<$nbQuizz;$i++)
                    {
                        $requete = $BDD -> prepare("SELECT COUNT(*) FROM HISTORIQUE WHERE Quizz=? AND Date BETWEEN ? AND CURDATE()");
                        $requete -> execute(array($i+1,$date));
                        $occurences[$i]=$requete->fetch()[0];  
                    }

                    $quizzTendances = tableauQuizzRecurents($occurences,$nbQuizz,$lignes);
                ?>
                <div id="carouselTendance" class="carousel slide" data-pause="">
                    <div class="carousel-inner" role="listbox">
                    <?php
                        $i=0;
                        echo '<div class="item active"><div class="serie">';
                        for($n=0;$n<4;$n++)
                        {
                            $quizz=$quizzTendances[$i];
                            $i++;
                            echo '<a href="DescrQuizz.php?Numquizz='.$quizz['Numquizz'].'"><input class="image" type="image" src=images/'.$quizz['Image'].' value='.$quizz['Numquizz'].' title="'.$quizz['Nomquizz'].'" /></a>';
                        }
                        echo'</div></div>';
                        while($i<8)
                        {
                            echo '<div class="item"><div class="serie">';
                            for($n=0;$n<4;$n++)
                            {
                                $quizz=$quizzTendances[$i];
                                $i++;
                                echo '<a href="DescrQuizz.php?Numquizz='.$quizz['Numquizz'].'"><input class="image" type="image" src=images/'.$quizz['Image'].' value='.$quizz['Numquizz'].' title="'.$quizz['Nomquizz'].'" /></a>';
                            }
                            echo'</div></div>';
                        }
                    ?>
                    </div>
                    <a class="left carousel-control" href="#carouselTendance" data-slide="prev">
                        <span class="glyphicon glyphicon-chevron-left"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="right carousel-control" href="#carouselTendance" data-slide="next">
                        <span class="glyphicon glyphicon-chevron-right"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div><br/><br/>
            
                <?php
                // création tableau avec les noms des catégories
                $requete = "SELECT id_categorie FROM CATEGORIE";
                $resultat = $BDD -> query($requete);
                while($categorie = $resultat->fetch())
                {
                    $categorie = $categorie[0];
                    echo "<h3>".$categorie."</h3><br/>";                    
                    echo '<div id="carousel'.$categorie.'" class="carousel slide" data-pause="">
                    <div class="carousel-inner" role="listbox">';
                    
                    // récupération des quizz de la catégorie dans un tableau
                    $requete2 = $BDD -> prepare("SELECT * FROM QUIZZ WHERE Categorie_1=? OR Categorie_2=? OR Categorie_3=?");
                    $requete2 -> execute(array($categorie,$categorie,$categorie));
                    $n=0;
                    while ($quizz=$requete2->fetch())
                    {
                        $quizzCategorie[$n]=$quizz;
                        $n++;   
                    }

                    if(count($quizzCategorie)<=4)
                    {
                        $L=count($quizzCategorie);
                        echo '<div class="item active"><div class="serie">';
                        for ($i=0;$i<$L;$i++)
                        {
                            $quizz=$quizzCategorie[0];                           
                            echo '<a href="DescrQuizz.php?Numquizz='.$quizz['Numquizz'].'"><input class="image" type="image" src=images/'.$quizz['Image'].' value='.$quizz['Numquizz'].' title="'.$quizz['Nomquizz'].'" /></a>';  
                            array_shift($quizzCategorie);     
                        }
                        for($i=0;$i<4-$L;$i++)
                        {
                            echo '<img class="image" src="images/image_noire.png" alt="image noire" />';
                        }
                        echo'</div></div>';
                    }
                    elseif(count($quizzCategorie)>4)
                    {
                        echo '<div class="item active"><div class="serie">';
                        for($i=0;$i<4;$i++)
                        {
                            $quizz=$quizzCategorie[0];                           
                            echo '<a href="DescrQuizz.php?Numquizz='.$quizz['Numquizz'].'"><input class="image" type="image" src=images/'.$quizz['Image'].' value='.$quizz['Numquizz'].' title="'.$quizz['Nomquizz'].'" /></a>';
                            array_shift($quizzCategorie);
                        }
                        echo'</div></div>';
                        while(count($quizzCategorie)>4)
                        {
                            echo '<div class="item"><div class="serie">';
                            for($i=0;$i<4;$i++)
                            {
                                $quizz=$quizzCategorie[0];                               
                                echo '<a href="DescrQuizz.php?Numquizz='.$quizz['Numquizz'].'"><input class="image" type="image" src=images/'.$quizz['Image'].' value='.$quizz['Numquizz'].' title="'.$quizz['Nomquizz'].'" /></a>';                               
                                array_shift($quizzCategorie);
                            } 
                            echo'</div></div>';
                        }
                        echo '<div class="item"><div class="serie">';
                        $L=count($quizzCategorie);
                        for ($i=0;$i<$L;$i++)
                        {
                            $quizz=$quizzCategorie[0]; 
                            echo '<a href="DescrQuizz.php?Numquizz='.$quizz['Numquizz'].'"><input class="image" type="image" src=images/'.$quizz['Image'].' value='.$quizz['Numquizz'].' title="'.$quizz['Nomquizz'].'" /></a>';     
                            array_shift($quizzCategorie);
                        }
                        for($i=0;$i<4-$L;$i++)
                        {
                            echo '<img class="image" src="images/image_noire.png" alt="image noire" />';
                        }
                        echo'</div></div>'; 
                    }
                    echo '</div>
                    <a class="left carousel-control" href="#carousel'.$categorie.'" data-slide="prev">
                        <span class="glyphicon glyphicon-chevron-left"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="right carousel-control" href="#carousel'.$categorie.'" data-slide="next">
                        <span class="glyphicon glyphicon-chevron-right"></span>
                        <span class="sr-only">Next</span>
                    </a>
                    </div><br/><br/>';
                }
                ?>
            
        </div>
    </body>

    <?php include("includes/footer.html") ; ?>
</html>