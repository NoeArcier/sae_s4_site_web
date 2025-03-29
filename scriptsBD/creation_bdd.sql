-- DROP TABLE IF EXISTS reservation_pret_louer;
-- DROP TABLE IF EXISTS reservation_autre;
-- DROP TABLE IF EXISTS reservation_entretien;
-- DROP TABLE IF EXISTS reservation_formation;
-- DROP TABLE IF EXISTS reservation_reunion;
-- DROP TABLE IF EXISTS reservation;
-- DROP TABLE IF EXISTS activite;
-- DROP TABLE IF EXISTS salle;
-- DROP TABLE IF EXISTS login;
-- DROP TABLE IF EXISTS employe;
-- DROP TABLE IF EXISTS type_utilisateur;
-- Créer la base de données si elle n'existe pas
CREATE DATABASE IF NOT EXISTS `StatisalleBD` DEFAULT CHARACTER SET utf8mb4 COLLATE
utf8mb4_general_ci;
USE `StatisalleBD`;
-- Créer l'utilisateur 'application'
CREATE USER IF NOT EXISTS 'application'@'%' IDENTIFIED BY '@ppl1cat1on123';
-- Attribuer des privilèges restreint à cet utilisateur
GRANT SELECT, INSERT, UPDATE, DELETE, LOCK TABLES, SHOW VIEW ON `StatisalleBD`.* TO
'application'@'%';
-- Créer l'utilisateur 'admin'
CREATE USER IF NOT EXISTS 'admin'@'%' IDENTIFIED BY '@dm1n123!';
-- Attribuer tous les privilèges à l'utilisateur 'admin' pour gérer entièrement la base
GRANT ALL PRIVILEGES ON `StatisalleBD`.* TO 'admin'@'%';
-- Appliquer les modifications de privilèges
FLUSH PRIVILEGES;
-- Table type_utilisateur
CREATE TABLE IF NOT EXISTS type_utilisateur (
id_type INT AUTO_INCREMENT PRIMARY KEY,
nom_type VARCHAR(50) NOT NULL
) CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
-- Table employe
CREATE TABLE IF NOT EXISTS employe (
id_employe CHAR(7) PRIMARY KEY,
nom VARCHAR(50) NOT NULL,
prenom VARCHAR(50) NOT NULL,
telephone CHAR(10)
) CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table login
CREATE TABLE IF NOT EXISTS login (
id_login INT AUTO_INCREMENT PRIMARY KEY,
login VARCHAR(25) NOT NULL,
mdp VARCHAR(255) NOT NULL,
id_type INT,
id_employe CHAR(7),
FOREIGN KEY (id_type) REFERENCES type_utilisateur(id_type),
FOREIGN KEY (id_employe) REFERENCES employe(id_employe)
) CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
-- Table salle
CREATE TABLE IF NOT EXISTS salle (
id_salle INT AUTO_INCREMENT PRIMARY KEY,
nom VARCHAR(50) NOT NULL,
capacite INT NOT NULL,
videoproj BOOLEAN,
ecran_xxl BOOLEAN,
ordinateur INT DEFAULT 0,
type VARCHAR(50),
logiciels VARCHAR(255),
imprimante BOOLEAN
) CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
-- Table activite
CREATE TABLE IF NOT EXISTS activite (
id_activite CHAR(8) PRIMARY KEY,
nom_activite VARCHAR(50) NOT NULL
) CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
-- Table reservation
CREATE TABLE IF NOT EXISTS reservation (
id_reservation CHAR(7) PRIMARY KEY,
id_salle INT NOT NULL,
id_employe CHAR(7) NOT NULL,
id_activite CHAR(8) NOT NULL,
date_reservation DATE NOT NULL,
heure_debut TIME NOT NULL,
heure_fin TIME NOT NULL,
FOREIGN KEY (id_salle) REFERENCES salle(id_salle),
FOREIGN KEY (id_employe) REFERENCES employe(id_employe),
FOREIGN KEY (id_activite) REFERENCES activite(id_activite)
) CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
-- Table reservation_reunion
CREATE TABLE IF NOT EXISTS reservation_reunion (

id_reservation CHAR(7) PRIMARY KEY,
objet VARCHAR(100),
FOREIGN KEY (id_reservation) REFERENCES reservation(id_reservation)
) CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
-- Table reservation_formation
CREATE TABLE IF NOT EXISTS reservation_formation (
id_reservation CHAR(7) PRIMARY KEY,
sujet VARCHAR(100),
nom_formateur VARCHAR(50),
prenom_formateur VARCHAR(50),
num_tel_formateur CHAR(10),
FOREIGN KEY (id_reservation) REFERENCES reservation(id_reservation)
) CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
-- Table reservation_entretien
CREATE TABLE IF NOT EXISTS reservation_entretien (
id_reservation CHAR(7) PRIMARY KEY,
nature VARCHAR(100),
FOREIGN KEY (id_reservation) REFERENCES reservation(id_reservation)
) CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
-- Table reservation_autre
CREATE TABLE IF NOT EXISTS reservation_autre (
id_reservation CHAR(7) PRIMARY KEY,
description VARCHAR(100),
FOREIGN KEY (id_reservation) REFERENCES reservation(id_reservation)
) CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
-- Table reservation_pret_louer
CREATE TABLE IF NOT EXISTS reservation_pret_louer (
id_reservation CHAR(7) PRIMARY KEY,
nom_organisme VARCHAR(50),
nom_interlocuteur VARCHAR(50),
prenom_interlocuteur VARCHAR(50),
num_tel_interlocuteur CHAR(10),
type_activite VARCHAR(100),
FOREIGN KEY (id_reservation) REFERENCES reservation(id_reservation)
) CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;