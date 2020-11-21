CREATE schema ubc_test_4;
USE ubc_test_4;

CREATE TABLE users (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(50),
    prenom VARCHAR(50),
    email VARCHAR(50) UNIQUE,
    email_recup VARCHAR(50),
    tel VARCHAR(10),
    dob DATE,
    password VARCHAR(255) NOT NULL,
    is_driver TINYINT(1),
    bio VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS `ville` (
  `id_ville` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `ville_departement` varchar(3) DEFAULT NULL,
  `ville_slug` varchar(255) DEFAULT NULL,
  `ville_nom` varchar(45) DEFAULT NULL,
  `ville_nom_simple` varchar(45) DEFAULT NULL,
  `ville_nom_reel` varchar(45) DEFAULT NULL,
  `ville_nom_soundex` varchar(20) DEFAULT NULL,
  `ville_nom_metaphone` varchar(22) DEFAULT NULL,
  `ville_code_postal` varchar(255) DEFAULT NULL,
  `ville_commune` varchar(3) DEFAULT NULL,
  `ville_code_commune` varchar(5) NOT NULL,
  `ville_arrondissement` smallint(3) unsigned DEFAULT NULL,
  `ville_canton` varchar(4) DEFAULT NULL,
  `ville_amdi` smallint(5) unsigned DEFAULT NULL,
  `ville_population_2010` mediumint(11) unsigned DEFAULT NULL,
  `ville_population_1999` mediumint(11) unsigned DEFAULT NULL,
  `ville_population_2012` mediumint(10) unsigned DEFAULT NULL COMMENT 'approximatif',
  `ville_densite_2010` int(11) DEFAULT NULL,
  `ville_surface` float DEFAULT NULL,
  `ville_longitude_deg` float DEFAULT NULL,
  `ville_latitude_deg` float DEFAULT NULL,
  `ville_longitude_grd` varchar(9) DEFAULT NULL,
  `ville_latitude_grd` varchar(8) DEFAULT NULL,
  `ville_longitude_dms` varchar(9) DEFAULT NULL,
  `ville_latitude_dms` varchar(8) DEFAULT NULL,
  `ville_zmin` mediumint(4) DEFAULT NULL,
  `ville_zmax` mediumint(4) DEFAULT NULL,
  PRIMARY KEY (`id_ville`),
  UNIQUE KEY `ville_code_commune_2` (`ville_code_commune`),
  UNIQUE KEY `ville_slug` (`ville_slug`),
  KEY `ville_departement` (`ville_departement`),
  KEY `ville_nom` (`ville_nom`),
  KEY `ville_nom_reel` (`ville_nom_reel`),
  KEY `ville_code_commune` (`ville_code_commune`),
  KEY `ville_code_postal` (`ville_code_postal`),
  KEY `ville_longitude_latitude_deg` (`ville_longitude_deg`,`ville_latitude_deg`),
  KEY `ville_nom_soundex` (`ville_nom_soundex`),
  KEY `ville_nom_metaphone` (`ville_nom_metaphone`),
  KEY `ville_population_2010` (`ville_population_2010`),
  KEY `ville_nom_simple` (`ville_nom_simple`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=36831 ;

CREATE TABLE universite(
   id_place INT NOT NULL AUTO_INCREMENT,
   nom_place VARCHAR(45) NOT NULL,
   desc_place VARCHAR(255) DEFAULT NULL,
   PRIMARY KEY(id_place)
);

CREATE TABLE trajet(
   id_trajet INT NOT NULL AUTO_INCREMENT,
   id_user INT,
   datetime_trajet DATETIME,
   partir_ub TINYINT(1),
   id_ville mediumint(8) unsigned,
   adresse VARCHAR(50),
   statut_trajet TINYINT(1),
   place_dispo INT,
   rayon_detour INT,
   com VARCHAR(255),
   id_place INT,
   PRIMARY KEY(id_trajet),
   FOREIGN KEY(id_place) REFERENCES universite(id_place),
   FOREIGN KEY(id_ville) REFERENCES ville(id_ville),
   FOREIGN KEY(id_user) REFERENCES users(id)
);

CREATE TABLE participe(
   id_user INT NOT NULL,
   id_trajet INT NOT NULL,
   com_passager VARCHAR(255),
   PRIMARY KEY(id_user, id_trajet),
   FOREIGN KEY(id_user) REFERENCES users(id),
   FOREIGN KEY(id_trajet) REFERENCES trajet(id_trajet)
);

CREATE TABLE messages(
   id_message INT NOT NULL AUTO_INCREMENT,
   id_user INT NOT NULL, 
   recepteur INT,
   contenu VARCHAR(255) NOT NULL,
   date_message DATETIME,
   PRIMARY KEY(id_message),
   FOREIGN KEY(id_user) REFERENCES users(id)
);

CREATE TABLE vehicule(
   id_vehicule INT NOT NULL AUTO_INCREMENT,
   id_user INT NOT NULL,
   place INT,
   marque VARCHAR(25) DEFAULT NULL,
   model VARCHAR(50) DEFAULT NULL,
   commentaire VARCHAR(150) DEFAULT NULL,
   PRIMARY KEY(id_vehicule),
   FOREIGN KEY(id_user) REFERENCES users(id)
);