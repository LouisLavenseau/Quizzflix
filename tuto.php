<?php
    session_start();
    require("includes/connect.php");
    include("includes/barre.php");
?>
<html>
    <head>
        <title>Aide</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="style/styleTuto.css"/>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    </head>

    <body>
        <div id="conteneur">
            <h2>Comment ça marche Quizzflix ?</h2>
            <div>
                <h3>1. Inscription ou connexion</h3>
                <div id="section1">
                    <div id="text1"><p>
                        En arrivant sur Quizzflix tu peux déjà consulter le catalogue de quizz disponibles.
                        <br/>
                        Néanmoins si tu veux commencer un quizz nous allons te demander de te <b><?php if(empty($_SESSION['login'])){echo'<a class="rouge" href="connexion.php">connecter</a>';} else echo'connecter';?></b>, ou bien de <b><?php if(empty($_SESSION['login'])){echo"<a class='rouge' href='inscription.php'>t'inscire</a>";} else echo"t'inscrire";?></b> si tu es nouveau ici.
                    </p></div>
                    <div><img src="images/tuto/connexion.png" alt="connexion"/></div>
                </div>
            </div>
            <div>
                <h3>2. Sélection d'un quizz</h3>
                <p>
                    Depuis <b><a href="index.php">l'accueil</a></b> tu vas avoir accès aux quizz les plus <b>populaires</b> de Quizzflix, aux quizz <b>tendances</b> en ce moment, puis aux différentes catégories de séries au fur et à mesure que tu scroll down.
                    <br/><br/>
                    <div class="centrer"><img src="images/tuto/accueil1.png" alt="accueil" id="imgaccueil"/></div>
                    <br/><br/>                                         
                    Tu retrouveras ces différentes <b>catégories</b> dans la barre <b><a href="#barre">en haut</a></b>, et aussi tout <b><a href="#foot">en bas</a></b>, quelle que soit la page du site sur laquelle tu te trouves. Cela te permettra de filtrer les quizz plus susceptibles de t'interresser.                     
                    <br/><br/>
                    <div class="centrer"><img src="images/tuto/categories.png" alt="categories"/></div>              
                    <br/><br/>
                    En cliquant sur l'image d'un quizz tu te retrouves sur une page de présentation du quizz.
                    <br/><br/>
                    <div class="centrer"><img src="images/tuto/description.png" alt="description" id="imgdescr"/></div>
                    <br/>Si tu décides que tu veux réaliser ce quizz, tu n'as qu'à choisir la difficulté que tu souhaites et lancer le quizz !
                    <br/>
                    Attention ! Quand tu lances le quizz, un <b>chronomètre</b> invisible se déclenche... Essaie de répondre aux questions le plus vite possible !
                </p>
            </div>
            <div>
                <br/><h3>3. Déroulement du quizz</h3>
                <p>
                    En général un quizz se compose d'environ 10 questions.
                    <br/>
                    Trois types de questions peuvent t'être posé : 
                </p>
                <ul>
                    <li>
                        <p>les <b>QCM</b>
                        <br/>En général il n'y a qu'une seule réponse possible, néanmoins il se peut que deux réponses soient attendues quelques fois, dans ce cas ce sera précisé dans l'énoncé de la question.</p>
                        <div class="centrer">
                            <img class="quest" src="images/tuto/QCM.png" alt="QCM"/>
                            <img class="quest" src="images/tuto/QCM2.png" alt="QCM2"/>
                        </div>
                    </li><br/>
                    <li>
                        <p>les <b>VRAI / FAUX</b>
                        <br/>Il te suffit de choisir si l'affirmation est vraie ou fausse.</p>
                        <div class="centrer"><img class="quest" src="images/tuto/VF.png" alt="VF"/></div>
                    </li><br/>
                    <li>
                        <p>les <b>questions ouvertes</b>
                        <br/>Tu dois répondre à la question à l'aide de ton clavier dans la zone de réponse. Attention aux fautes d'orthographe...</p>
                        <div class="centrer"><img class="quest" src="images/tuto/questouv.png" alt="questouv"/></div>
                    </li><br/>
                </ul>
                <p>Dans tous les cas, une fois que tu as choisi ou écrit ta réponse, il te faut valider.</p>
                <p>Directement après tu vas savoir si tu as eu bon, ou pas... Et tu vas pouvoir passer à la question suivante !</p>
                <div class="centrer">
                    <img class="quest" src="images/tuto/bonnerep.png" alt="bonneReponse"/>
                    <img class="quest" src="images/tuto/mauvaiserep.png" alt="mauvaiseReponse"/>
                </div><br/>
                <p>A la fin du quizz ton score et ton chrono vont s'afficher comme ci-dessous</p>
                <div class="centrer"><img class="quest" src="images/tuto/score.png" alt="score"/></div>
            </div> 
            <div>
                <br/><h3>4. Ton profil</h3>
                <p>Dans ton <b><?php if(isset($_SESSION['login'])){echo '<a href="historique.php">profil</a>';}else echo'profil';?></b> tu vas pouvoir retrouver tes <b>meilleurs scores</b>, ainsi que tes <b>dernières parties</b> réalisées.</p>
                <div id="profil">
                    <img src="images/tuto/meilleurscore.png" alt="mailleurscore"/>
                    <img src="images/tuto/historique.png" alt="historique"/>
                </div>
            </div> 
        </div>
    </body>
    <?php include("includes/footer.html");?>
</html>