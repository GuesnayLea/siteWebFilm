-- Création de la base de données
CREATE DATABASE IF NOT EXISTS `Film_db`;
USE `Film_db`;

-- Table UTILISATEUR
CREATE TABLE `UTILISATEUR` (
    `id_utilisateur` INT AUTO_INCREMENT PRIMARY KEY,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `mot_de_passe_hash` VARCHAR(255) NOT NULL,
    `nom` VARCHAR(100) NOT NULL,
    `date_inscription` DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Table FILM
CREATE TABLE `FILM` (
    `id_film` INT AUTO_INCREMENT PRIMARY KEY,
    `titre` VARCHAR(255) NOT NULL,
    `annee` SMALLINT NOT NULL,
    `duree` SMALLINT NOT NULL COMMENT 'Durée en minutes',
    `synopsis` TEXT,
    `genre` VARCHAR(100),
    `prix_location_par_defaut` DECIMAL(5,2) NOT NULL,
    `chemin_affiche` VARCHAR(500) DEFAULT NULL
);

-- Table FAVORI
CREATE TABLE `FAVORI` (
    `id_favori` INT AUTO_INCREMENT PRIMARY KEY,
    `id_utilisateur` INT NOT NULL,
    `id_film` INT NOT NULL,
    `date_ajout` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`id_utilisateur`) REFERENCES `UTILISATEUR`(`id_utilisateur`) ON DELETE CASCADE,
    FOREIGN KEY (`id_film`) REFERENCES `FILM`(`id_film`) ON DELETE CASCADE,
    UNIQUE KEY `unique_favori` (`id_utilisateur`, `id_film`)
);

-- Table TARIF_DYNAMIQUE
CREATE TABLE `TARIF_DYNAMIQUE` (
    `id_tarif` INT AUTO_INCREMENT PRIMARY KEY,
    `jour_semaine` ENUM(
            'lundi',
            'mardi', 
            'mercredi', 
            'jeudi', 
            'vendredi', 
            'samedi', 
            'dimanche') NOT NULL,
    `pourcentage_reduction` DECIMAL(5,2) NOT NULL COMMENT 'Ex: -20.00 pour 20% de réduction',
    `actif` BOOLEAN DEFAULT TRUE
);

-- Table LOCATION
CREATE TABLE `LOCATION` (
    `id_location` INT AUTO_INCREMENT PRIMARY KEY,
    `id_utilisateur` INT NOT NULL,
    `id_film` INT NOT NULL,
    `date_location` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `date_retour_prevue` DATE NOT NULL,
    `prix_final` DECIMAL(5,2) NOT NULL,
    `statut` ENUM('loué', 'retourné') DEFAULT 'loué',
    FOREIGN KEY (`id_utilisateur`) REFERENCES `UTILISATEUR`(`id_utilisateur`) ON DELETE CASCADE,
    FOREIGN KEY (`id_film`) REFERENCES `FILM`(`id_film`) ON DELETE CASCADE
);

INSERT INTO `TARIF_DYNAMIQUE` (`jour_semaine`, `pourcentage_reduction`, `actif`) VALUES
('mardi', -20.00, TRUE),
('jeudi', -15.00, TRUE),
('dimanche',-10.00, TRUE);


-- Insertion de 50 films avec URLs TMDB valides
INSERT INTO `FILM` (`titre`, `annee`, `duree`, `synopsis`, `genre`, `prix_location_par_defaut`, `chemin_affiche`) VALUES
('Moonlight', 2016, 111, 'Un jeune homme Afro-Américain découvre son identité à Miami.', 'Drame', 3.99, 'https://image.tmdb.org/t/p/w500/qJeU7KM4nT2C1WpOrwPcSDGFUWE.jpg'),
('Parasite', 2019, 132, 'Une famille pauvre s infiltre dans le foyer d une famille riche.', 'Thriller', 4.25, 'https://image.tmdb.org/t/p/w500/7IiTTgloJzvGI1TAYymCfbfl3vT.jpg'),
('Black Panther', 2018, 134, 'T Challa devient roi du Wakanda et affronte un ennemi caché.', 'Action', 4.50, 'https://image.tmdb.org/t/p/w500/uxzzxijgPIY7slzFvMotPv8wjKA.jpg'),
('Everything Everywhere All at Once', 2022, 139, 'Une blanchisseuse voyage dans le multivers pour sauver sa famille.', 'Comédie', 4.75, 'https://image.tmdb.org/t/p/w500/w3LxiVYdWWRvEVdn5RYq6jIqkb1.jpg'),
('Roma', 2018, 135, 'La vie d une domestique indigène au Mexique des années 70.', 'Drame', 3.75, 'https://image.tmdb.org/t/p/w500/6jV6b2Qx4EoCUQZ3p1kEGNt5J5.jpg'),
('Coco', 2017, 105, 'Un jeune Mexicain voyage au pays des morts durant le Dia de los Muertos.', 'Animation', 3.50, 'https://image.tmdb.org/t/p/w500/gPXKTs2wKrHKKbMiQpMoZqP8K4B.jpg'),
('The Farewell', 2019, 100, 'Une famille chinoise cache un diagnostic à leur grand-mère.', 'Comédie dramatique', 3.25, 'https://image.tmdb.org/t/p/w500/x3NQ5rVoiuN0X8LJNcclrcX8GT6.jpg'),
('Minari', 2020, 115, 'Une famille coréenne tente de réaliser le rêve américain en Arkansas.', 'Drame', 3.50, 'https://image.tmdb.org/t/p/w500/5M1H2M1MGZlcAAJvkJRJvG2hjCb.jpg'),
('Spider-Man: Into the Spider-Verse', 2018, 117, 'Miles Morales devient Spider-Man et rencontre d autres versions.', 'Animation', 4.25, 'https://image.tmdb.org/t/p/w500/iiZZdoQBEYBv6id8su7ImL0oCbD.jpg'),
('Hidden Figures', 2016, 127, 'Trois mathématiciennes Afro-Américaines à la NASA dans les années 60.', 'Histoire', 3.75, 'https://image.tmdb.org/t/p/w500/6cbIDZLfwUTmttXTmNi8Mp3Rnmg.jpg'),
('Get Out', 2017, 104, 'Un jeune homme Afro-Américain découvre un secret terrifiant chez sa copine.', 'Horreur', 3.99, 'https://image.tmdb.org/t/p/w500/tFXcEccSQMf3lfhfXKSU9iRBpa3.jpg'),
('Crazy Rich Asians', 2018, 120, 'Une professeure américaine découvre la riche famille de son fiancé à Singapour.', 'Comédie romantique', 3.50, 'https://image.tmdb.org/t/p/w500/1XxL4LJ5WHdrcYcihEZUCgNCpAW.jpg'),
('The Shape of Water', 2017, 123, 'Une femme muette tombe amoureuse d une créature aquatique.', 'Fantaisie', 3.75, 'https://image.tmdb.org/t/p/w500/k4FwHlMhuRRpBETR4Ck9aEYnWnF.jpg'),
('Nomadland', 2020, 108, 'Une femme âgée voyage à travers l Amérique après la Grande Récession.', 'Drame', 3.25, 'https://image.tmdb.org/t/p/w500/6jV6b2Qx4EoCUQZ3p1kEGNt5J5.jpg'),
('BlackKklansman', 2018, 135, 'Un policier Afro-Américain infiltre le Ku Klux Klan dans les années 70.', 'Biographie', 3.99, 'https://image.tmdb.org/t/p/w500/pbEkj6LQLuFys5e51MfWn5cVNLw.jpg'),
('If Beale Street Could Talk', 2018, 119, 'Un couple Afro-Américain séparé par une fausse accusation dans les années 70.', 'Drame', 3.25, 'https://image.tmdb.org/t/p/w500/6z6ZdY6pRE0qgVKqHkf8qj6pFNL.jpg'),
('La La Land', 2016, 128, 'Une romance musicale entre un pianiste de jazz et une actrice à Los Angeles.', 'Musical', 3.75, 'https://image.tmdb.org/t/p/w500/uDO8zWDhfWwoFdKS4fzkUJt0Rf0.jpg'),
('Dune', 2021, 155, 'Paul Atreides voyage sur une planète désertique pour protéger l épice.', 'Science-Fiction', 4.50, 'https://image.tmdb.org/t/p/w500/d5NXSklXo0qyIYkgV94XAgMIckC.jpg'),
('The Last Black Man in San Francisco', 2019, 120, 'Un homme tente de récupérer la maison victorienne de son enfance.', 'Drame', 3.25, 'https://image.tmdb.org/t/p/w500/4LgIfJcPcvFdCFTnl6h7hDYl8zv.jpg'),
('Booksmart', 2019, 102, 'Deux lycéennes surdouées décident de rattraper une soirée avant la fin des études.', 'Comédie', 3.50, 'https://image.tmdb.org/t/p/w500/5vY7z7B4Dp3Zn8O2eY1a5XgSIL3.jpg'),
('CODA', 2021, 111, 'Une adolescente entendante dans une famille sourde rêve de chanter.', 'Drame', 3.75, 'https://image.tmdb.org/t/p/w500/BzVjmm8l23rPsijLiNLUzuQtyd.jpg'),
('Turning Red', 2022, 100, 'Une adolescente se transforme en panda rouge géant quand elle est émue.', 'Animation', 3.99, 'https://image.tmdb.org/t/p/w500/qsdjk9oAKSQMWs0Vt5Pyfh6O4GZ.jpg'),
('Encanto', 2021, 102, 'Une famille colombienne dotée de pouvoirs magiques, sauf une jeune fille.', 'Animation', 3.75, 'https://image.tmdb.org/t/p/w500/4j0PNHkMr5ax3IA8tjtxcmPU3QT.jpg'),
('Raya and the Last Dragon', 2021, 107, 'Une guerrière recherche le dernier dragon pour sauver son royaume.', 'Animation', 3.50, 'https://image.tmdb.org/t/p/w500/lPsD10PP4rgUGiGR4CCXA6iY0QQ.jpg'),
('Soul', 2020, 100, 'Un professeur de musique explore la vie avant la mort après un accident.', 'Animation', 3.75, 'https://image.tmdb.org/t/p/w500/hm58Jw4Lw8OIeECIq5qyPYhAeRJ.jpg'),
('The Mitchells vs. The Machines', 2021, 114, 'Une famille dysfonctionnelle sauve le monde d une révolte de robots.', 'Animation', 3.99, 'https://image.tmdb.org/t/p/w500/mI2SoaXkGlcQN6tWfJQq5L9kqQ8.jpg'),
('Luca', 2021, 95, 'Un monstre marin vit une amitié estivale sur la côte italienne.', 'Animation', 3.25, 'https://image.tmdb.org/t/p/w500/jTswp6KyDYKtvC52GbHagrZbGvD.jpg'),
('The Hate U Give', 2018, 133, 'Une adolescente témoin d une violence policière trouve sa voix.', 'Drame', 3.50, 'https://image.tmdb.org/t/p/w500/2icwBom0t4mMrO6oEfZQdF7iBqM.jpg'),
('Us', 2019, 116, 'Une famille confrontée à leurs doubles maléfiques lors de vacances.', 'Horreur', 3.75, 'https://image.tmdb.org/t/p/w500/ux2dU1jQ2ACIMShzB3yP93Udpzc.jpg'),
('Little Women', 2019, 135, 'Les sœurs March naviguent entre amour et ambition après la Guerre de Sécession.', 'Drame', 3.50, 'https://image.tmdb.org/t/p/w500/yn5ihODtZ7ofn8pDYfxCmxh8AXI.jpg'),
('Jojo Rabbit', 2019, 108, 'Un jeune nazi découvre que sa mère cache une jeune fille juive.', 'Comédie dramatique', 3.75, 'https://image.tmdb.org/t/p/w500/7GsM4mtM0worCtIVeiQt28HieeN.jpg'),
('A Quiet Place Part II', 2020, 97, 'Une famille tente de survivre dans un monde où les sons tuent.', 'Horreur', 3.99, 'https://image.tmdb.org/t/p/w500/4q2hz2m8hubgvijz8Ez0T2Os2Yv.jpg'),
('Tenet', 2020, 150, 'Un agent manipule le flux du temps pour prévenir la Troisième Guerre mondiale.', 'Science-Fiction', 4.25, 'https://image.tmdb.org/t/p/w500/k68nPLbIST6NP96JmTxmZijEvCA.jpg'),
('The Power of the Dog', 2021, 126, 'Un ranchier cruel dans le Montana des années 1920.', 'Western', 3.50, 'https://image.tmdb.org/t/p/w500/kEy48iCzGnp0ao1cZbNeWR6yIhC.jpg'),
('West Side Story', 2021, 156, 'Une adaptation moderne de la rivalité entre gangs à New York.', 'Musical', 4.00, 'https://image.tmdb.org/t/p/w500/tgKBV4t3hQmYrQuv3Fd0QRY8YrB.jpg'),
('In the Heights', 2021, 143, 'Les résidents de Washington Heights à New York pendant une canicule.', 'Musical', 3.75, 'https://image.tmdb.org/t/p/w500/9WoKTZ6Db6LPoE2f2eH3lP4MqYl.jpg'),
('Shang-Chi and the Legend of the Ten Rings', 2021, 132, 'Un maître d arts martiaux affronte son passé et l organisation des Dix Anneaux.', 'Action', 4.25, 'https://image.tmdb.org/t/p/w500/1BIoJGKbXjdFDAqUEiA2VHqkK1Z.jpg'),
('The French Dispatch', 2021, 107, 'Les dernières éditions d un magazine américain dans une ville française fictive.', 'Comédie', 3.50, 'https://image.tmdb.org/t/p/w500/52YgW2aBENY4xNqQmkxL3Q7pw4q.jpg'),
('Belfast', 2021, 98, 'Un jeune garçon durant les troubles en Irlande du Nord dans les années 60.', 'Drame', 3.25, 'https://image.tmdb.org/t/p/w500/1g0fYJbFm6SYa3p6JWwOc2pUzLh.jpg'),
('Cruella', 2021, 134, 'Les origines de la célèbre méchante de Disney dans le Londres des années 70.', 'Comédie', 3.75, 'https://image.tmdb.org/t/p/w500/rTh4K5uw9HypmpGslcKd4QfHl93.jpg'),
('The Green Knight', 2021, 130, 'Le neveu du roi Arthur affronte le Chevalier Vert.', 'Fantaisie', 3.50, 'https://image.tmdb.org/t/p/w500/if4hw3Ou5Sav9Em7WWHj66mnywp.jpg'),
('Last Night in Soho', 2021, 116, 'Une jeune designer plonge dans le Londres des années 60.', 'Thriller', 3.50, 'https://image.tmdb.org/t/p/w500/iukvRgF3lTkfyWjG3SBnX6Q5N7d.jpg'),
('The Batman', 2022, 176, 'Batman affronte le Sphinx à Gotham City.', 'Action', 4.50, 'https://image.tmdb.org/t/p/w500/seyWFgGInaLqW7nOZvu0V95T3u9.jpg'),
('Top Gun: Maverick', 2022, 130, 'Pete Mitchell retourne à l école de pilotes pour une mission dangereuse.', 'Action', 4.75, 'https://image.tmdb.org/t/p/w500/62HCnUTziyWcpDaBO2i1DX17ljH.jpg'),
('Elvis', 2022, 159, 'La vie et la carrière d Elvis Presley à travers les yeux de son manager.', 'Biographie', 4.25, 'https://image.tmdb.org/t/p/w500/qBOKWqAFbizZb9ib3R7wEMzMxx7.jpg'),
('Nope', 2022, 130, 'Des habitants d un ranch affrontent un phénomène étrange dans le ciel.', 'Horreur', 3.99, 'https://image.tmdb.org/t/p/w500/AcKVlWaNVVVFQwro3nLXqPljcYA.jpg'),
('The Woman King', 2022, 135, 'Les guerrières Agojie du royaume du Dahomey au 19ème siècle.', 'Action', 4.00, 'https://image.tmdb.org/t/p/w500/438QXt1E3WJWb3PqNniK0tAE5c1.jpg'),
('Triangle of Sadness', 2022, 147, 'Un couple de mannequins sur un yacht de luxe qui tourne au désastre.', 'Comédie noire', 3.75, 'https://image.tmdb.org/t/p/w500/k9eY9a1YcIBJq6bq7pGqPq7ZfE6.jpg'),
('The Banshees of Inisherin', 2022, 114, 'Deux amis sur une île irlandaise voient leur amitié se briser sans raison.', 'Comédie dramatique', 3.50, 'https://image.tmdb.org/t/p/w500/4yFG6cSPaCaPhyJ1vtGOtMD1lgh.jpg'),
('Aftersun', 2022, 101, 'Une femme se remémore des vacances avec son père dans les années 90.', 'Drame', 3.25, 'https://image.tmdb.org/t/p/w500/jeZCcQnQZM6hRoLmHWyL0W8Es1J.jpg'),
('The Menu', 2022, 107, 'Un couple dîne dans un restaurant exclusif sur une île isolée.', 'Thriller', 3.75, 'https://image.tmdb.org/t/p/w500/fPtUgMcI7vHrWk6W9Lwnt9tJQp9.jpg'),
('Tár', 2022, 158, 'Une cheffe d orchestre renommée voit sa vie s effondrer.', 'Drame', 3.50, 'https://image.tmdb.org/t/p/w500/dRV8yVBIQicjGfRrBol9t1ZprnS.jpg');
