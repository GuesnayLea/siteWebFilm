-- =========================================================
-- 1) Création de la base de données
-- =========================================================

-- Créer la base en UTF-8 complet (si elle n'existe pas déjà)
CREATE DATABASE IF NOT EXISTS dinodb
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

-- Utiliser la base
USE dinos;

-- =========================================================
-- 2) Table dinosaures
-- =========================================================

DROP TABLE IF EXISTS dinosaures;

CREATE TABLE dinosaures (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nom           VARCHAR(80) NOT NULL,
  regime        ENUM(
                    'herbivore',
                    'carnivore',
                    'omnivore',
                    'piscivore',
                    'insectivore'
                 ) NOT NULL,
  ere           ENUM('Trias','Jurassique','Crétacé') NOT NULL,
  poids         INT UNSIGNED     NOT NULL,       -- en kg
  poids_tonnes  DECIMAL(7,3) GENERATED ALWAYS AS (poids / 1000) STORED,
  age           TINYINT UNSIGNED NULL,           -- en années (optionnel)
  notes         VARCHAR(255)     NULL,           -- commentaires libres
  created_at    TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,

  INDEX idx_nom    (nom),
  INDEX idx_regime (regime)
) ENGINE=InnoDB;

INSERT INTO dinosaures (nom, regime, poids, age, ere, notes) VALUES
  ('Tyrannosaurus rex', 'carnivore', 8000, NULL, 'Crétacé', 'Roi des lézards tyrans'),
  ('Triceratops', 'herbivore', 6000, NULL, 'Crétacé', 'Trois cornes imposantes'),
  ('Velociraptor', 'carnivore', 15, NULL, 'Crétacé', 'Bien plus petit qu’au cinéma'),
  ('Brachiosaurus', 'herbivore', 30000, NULL, 'Jurassique', 'Cou très long, géant placide'),
  ('Stegosaurus', 'herbivore', 5000, NULL, 'Jurassique', 'Plates dorsales iconiques'),
  ('Allosaurus', 'carnivore', 2300, NULL, 'Jurassique', 'Prédateur agile'),
  ('Spinosaurus', 'carnivore', 7000, NULL, 'Crétacé', 'Museau de crocodile, semi-aquatique'),
  ('Ankylosaurus', 'herbivore', 6000, NULL, 'Crétacé', 'Queue massue, cuirasse osseuse'),
  ('Pachycephalosaurus', 'herbivore', 450, NULL, 'Crétacé', 'Crâne épaissi pour les duels'),
  ('Plateosaurus', 'herbivore', 4000, NULL, 'Trias', 'Grand herbivore du Trias'),
  ('Diplodocus', 'herbivore', 15000, NULL, 'Jurassique', 'Très longue queue'),
  ('Apatosaurus', 'herbivore', 23000, NULL, 'Jurassique', 'Massif sauropode'),
  ('Camarasaurus', 'herbivore', 18000, NULL, 'Jurassique', 'Museau carré'),
  ('Compsognathus', 'carnivore', 3, NULL, 'Jurassique', 'Très petit chasseur'),
  ('Iguanodon', 'herbivore', 3000, NULL, 'Crétacé', 'Pouce épineux'),
  ('Parasaurolophus', 'herbivore', 2500, NULL, 'Crétacé', 'Crête tubulaire sonore'),
  ('Edmontosaurus', 'herbivore', 3500, NULL, 'Crétacé', 'Hadrosaure robuste'),
  ('Corythosaurus', 'herbivore', 3100, NULL, 'Crétacé', 'Crête en casque'),
  ('Maiasaura', 'herbivore', 2700, NULL, 'Crétacé', '“Bonne mère lézard”'),
  ('Pachyrhinosaurus', 'herbivore', 2000, NULL, 'Crétacé', 'Bossettes nasales'),
  ('Torosaurus', 'herbivore', 6500, NULL, 'Crétacé', 'Grande collerette perforée'),
  ('Styracosaurus', 'herbivore', 3000, NULL, 'Crétacé', 'Grande corne nasale'),
  ('Kentrosaurus', 'herbivore', 2000, NULL, 'Jurassique', 'Plaques et pointes'),
  ('Dryosaurus', 'herbivore', 80, NULL, 'Jurassique', 'Coureur rapide'),
  ('Ceratosaurus', 'carnivore', 1000, NULL, 'Jurassique', 'Cornes nasales'),
  ('Megalosaurus', 'carnivore', 1100, NULL, 'Jurassique', 'Premier dinosaure décrit'),
  ('Suchomimus', 'carnivore', 3500, NULL, 'Crétacé', 'Museau piscivore'),
  ('Baryonyx', 'carnivore', 1700, NULL, 'Crétacé', 'Griffe géante'),
  ('Therizinosaurus', 'omnivore', 3000, NULL, 'Crétacé', 'Griffes immenses, régime varié'),
  ('Deinonychus', 'carnivore', 80, NULL, 'Crétacé', 'Rapace vif'),
  ('Oviraptor', 'omnivore', 20, NULL, 'Crétacé', 'Nom trompeur, régime varié'),
  ('Troodon', 'omnivore', 50, NULL, 'Crétacé', 'Grandes orbites'),
  ('Gallimimus', 'omnivore', 200, NULL, 'Crétacé', 'Coureur autruche'),
  ('Ornithomimus', 'omnivore', 150, NULL, 'Crétacé', 'Bec sans dents'),
  ('Microceratus', 'herbivore', 10, NULL, 'Crétacé', 'Petit cératopsien'),
  ('Protoceratops', 'herbivore', 180, NULL, 'Crétacé', 'Col frangé'),
  ('Psittacosaurus', 'herbivore', 25, NULL, 'Crétacé', 'Bec de perroquet'),
  ('Saurolophus', 'herbivore', 2800, NULL, 'Crétacé', 'Crête solide'),
  ('Shantungosaurus', 'herbivore', 15000, NULL, 'Crétacé', 'Très grand hadrosaure'),
  ('Euoplocephalus', 'herbivore', 2500, NULL, 'Crétacé', 'Cuirasse et massue'),
  ('Nodosaurus', 'herbivore', 1800, NULL, 'Crétacé', 'Ankylosauridé sans massue'),
  ('Sauropelta', 'herbivore', 1500, NULL, 'Crétacé', 'Épines latérales'),
  ('Rhabdodon', 'herbivore', 500, NULL, 'Crétacé', 'Ornithopode européen'),
  ('Dreadnoughtus', 'herbivore', 50000, NULL, 'Crétacé', 'Titanosaure colossal'),
  ('Giganotosaurus', 'carnivore', 8200, NULL, 'Crétacé', 'Très grand théropode'),
  ('Carnotaurus', 'carnivore', 1500, NULL, 'Crétacé', 'Cornes au-dessus des yeux'),
  ('Gorgosaurus', 'carnivore', 2500, NULL, 'Crétacé', 'Tyrannosauridé gracile'),
  ('Albertosaurus', 'carnivore', 2000, NULL, 'Crétacé', 'Proche de Gorgosaurus'),
  ('Tarbosaurus', 'carnivore', 5000, NULL, 'Crétacé', 'Cousin asiatique du T. rex'),
  ('Mononykus', 'omnivore', 4, NULL, 'Crétacé', 'Bras courts, insectivore probable'),
  ('Gigantoraptor', 'omnivore', 1400, NULL, 'Crétacé', 'Oviraptorosaure géant'),
  ('Utahraptor', 'carnivore', 500, NULL, 'Crétacé', 'Grand droméosaure'),
  ('Acrocanthosaurus', 'carnivore', 5500, NULL, 'Crétacé', 'Voûtes neurales hautes'),
  ('Carcharodontosaurus', 'carnivore', 6500, NULL, 'Crétacé', 'Dents de requin'),
  ('Abelisaurus', 'carnivore', 1200, NULL, 'Crétacé', 'Théropode sud-américain'),
  ('Argentinosaurus', 'herbivore', 70000, NULL, 'Crétacé', 'Peut-être le plus massif'),
  ('Herrerasaurus', 'carnivore', 350, NULL, 'Trias', 'Théropode primitif'),
  ('Coelophysis', 'carnivore', 20, NULL, 'Trias', 'Grégaire et élancé'),
  ('Eoraptor', 'omnivore', 10, NULL, 'Trias', 'Très primitif'),
  ('Musaurus', 'herbivore', 70, NULL, 'Trias', '“Lézard souris” juvénile célèbre'),
  ('Massospondylus', 'herbivore', 1000, NULL, 'Jurassique', 'Prosauropode élancé'),
  ('Heterodontosaurus', 'omnivore', 20, NULL, 'Jurassique', 'Dentition variée'),
  ('Lesothosaurus', 'herbivore', 10, NULL, 'Jurassique', 'Petit ornithischien'),
  ('Ichthyovenator', 'piscivore', 2500, NULL, 'Crétacé', 'Spinosauridé asiatique, chasseur de poissons'),
  ('Siamosaurus', 'piscivore', 3000, NULL, 'Crétacé', 'Spinosauridé de Thaïlande au museau allongé'),
  ('Ceratosuchops', 'piscivore', 1100, NULL, 'Crétacé', 'Spinosauriné européen associé aux milieux aquatiques'),
  ('Riparovenator', 'piscivore', 1200, NULL, 'Crétacé', 'Spinosauriné britannique proche de Ceratosuchops'),
  ('Halszkaraptor', 'piscivore', 15, NULL, 'Crétacé', 'Théropode semi-aquatique au long cou, probable pêcheur'),
  ('Deinocheirus', 'omnivore', 6400, NULL, 'Crétacé', 'Énormes bras griffus, régime varié incluant plantes et poissons'),
  ('Mamenchisaurus', 'herbivore', 20000, NULL, 'Jurassique', 'Sauropode au cou exceptionnellement long'),
  ('Camptosaurus', 'herbivore', 900, NULL, 'Jurassique', 'Ornithopode de taille moyenne'),
  ('Dilophosaurus', 'carnivore', 500, NULL, 'Jurassique', 'Deux crêtes fines sur le crâne'),
  ('Cryolophosaurus', 'carnivore', 700, NULL, 'Jurassique', 'Crête frontale, découvert en Antarctique'),
  ('Shunosaurus', 'herbivore', 10000, NULL, 'Jurassique', 'Sauropode doté d’une massue caudale'),
  ('Metriacanthosaurus', 'carnivore', 1000, NULL, 'Jurassique', 'Théropode de taille moyenne européen'),
  ('Huayangosaurus', 'herbivore', 500, NULL, 'Jurassique', 'Petit stégosaurien chinois'),
  ('Therizinosaurus', 'omnivore', 3000, NULL, 'Crétacé', 'Griffes géantes, régime varié'),
  ('Yi qi', 'insectivore', 2, NULL, 'Jurassique', 'Petit dinosaure à membranes planantes découvert en Chine (2015)'),
  ('Kulindadromeus', 'herbivore', 5, NULL, 'Jurassique', 'Premier dinosaure à plumes non avien connu, trouvé en Sibérie (2014)'),
  ('Zuul crurivastator', 'herbivore', 2000, NULL, 'Crétacé', 'Ankylosaure canadien superbement conservé, découvert en 2017'),
  ('Dracoraptor', 'carnivore', 30, NULL, 'Jurassique', 'Théropode gallois du Jurassique inférieur décrit en 2016'),
  ('Murusraptor', 'carnivore', 2200, NULL, 'Crétacé', 'Théropode sud-américain de la famille des mégaraptoridés (2016)'),
  ('Austroraptor', 'carnivore', 400, NULL, 'Crétacé', 'Droméosaure patagonien à museau allongé, décrit en 2008'),
  ('Changyuraptor', 'carnivore', 5, NULL, 'Crétacé', 'Microraptor ailé à longue queue, découvert en 2014'),
  ('Zhenyuanlong', 'carnivore', 20, NULL, 'Crétacé', 'Droméosaure à plumes courtes découvert en Chine (2015)'),
  ('Beipiaosaurus', 'omnivore', 300, NULL, 'Crétacé', 'Thérizinosaure à plumes primitives, décrit en 1999 et confirmé en 2014'),
  ('Pegomastax', 'herbivore', 2, NULL, 'Jurassique', 'Petit ornithischien à bec, redécrit en 2012'),
  ('Tianyulong', 'herbivore', 4, NULL, 'Crétacé', 'Petit ornithopode à filaments cutanés, découvert en 2009'),
  ('Qianzhousaurus', 'carnivore', 2500, NULL, 'Crétacé', '« Pinocchio rex », tyrannosauridé au museau allongé décrit en 2014'),
  ('Llukalkan', 'carnivore', 1500, NULL, 'Crétacé', 'Abelisauridé sud-américain récemment identifié (2021)'),
  ('Natovenator', 'piscivore', 30, NULL, 'Crétacé', 'Théropode semi-aquatique aux côtes fuselées découvert en 2022');
