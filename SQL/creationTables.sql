/*Creation de table classique. Nous avons expliqué plus en détail nos choix dans le rapport. Nous avons cependant ajouté une fonctionnalité "Cascade" 
pour ON DELETE pour que 
les questions et réponses associées à un quizz soient supprimées automatique si le quizz est supprimé (voir plus bas)*/

CREATE TABLE COMPTE (
    Login VARCHAR(15) PRIMARY KEY,
    Mdp VARCHAR(20),
    Type VARCHAR(10)
);

CREATE TABLE CATEGORIE (
    id_categorie VARCHAR(30) PRIMARY KEY
);

CREATE TABLE QUIZZ (
    Numquizz INTEGER(3) PRIMARY KEY,
    Image VARCHAR(30),
    Categorie_1 VARCHAR(30),
    Categorie_2 VARCHAR(30),
    Categorie_3 VARCHAR(30),
    Nomquizz VARCHAR(30),
    Description TEXT,
    FOREIGN KEY(Categorie_1) REFERENCES CATEGORIE(id_categorie),
    FOREIGN KEY(Categorie_2) REFERENCES CATEGORIE(id_categorie),
    FOREIGN KEY(Categorie_3) REFERENCES CATEGORIE(id_categorie)
);

CREATE TABLE QUESTION (
    Numquest INTEGER(2),
    Quizz INTEGER(3),
    Question TEXT,
    Typefacile VARCHAR(3),
    Typemoyen VARCHAR(3),
    Typedifficile VARCHAR(3),
    CONSTRAINT question_ibfk_1 FOREIGN KEY(Quizz) REFERENCES QUIZZ(NumQuizz) ON DELETE CASCADE,/*en rajoutant ici la contrainte on delete cascade*/
    PRIMARY KEY(Numquest,Quizz)
);

CREATE TABLE REPONSE (
    Numrep INTEGER(4) PRIMARY KEY,
    Quizz INTEGER(3),
    Quest INTEGER(2),
    Reponse TEXT,
    Bonnerep CHAR(3),
    CONSTRAINT reponse_ibfk_1 FOREIGN KEY(Quizz) REFERENCES QUIZZ(Numquizz) ON DELETE CASCADE/*et ici aussi*/
);

CREATE TABLE HISTORIQUE (
    Numhist INTEGER(5) PRIMARY KEY,
    Login CHAR(15),
    Quizz INTEGER(3),
    Difficulte VARCHAR(10),
    Score INTEGER(2),
    Temps INTEGER,
    Date DATE,
    CONSTRAINT historique_ibfk_1 FOREIGN KEY(Login) REFERENCES COMPTE(Login) ON DELETE CASCADE,
    CONSTRAINT historique_ibfk_2 FOREIGN KEY(Quizz) REFERENCES QUIZZ(NumQuizz) ON DELETE CASCADE
);

