DROP TABLE IF EXISTS signalements;

-- Table signalements
CREATE TABLE IF NOT EXISTS signalements (
	id INT(10) AUTO_INCREMENT PRIMARY KEY,
	date DATE NOT NULL,
	heure TIME NOT NULL,
	titre VARCHAR(50) NOT NULL,
	resume VARCHAR(200) NOT NULL,
	impact INT(1) NOT NULL,
	recontact BOOLEAN NOT NULL,
	id_reservation CHAR(7) NOT NULL
) CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `signalements`
  ADD CONSTRAINT `fk_reservation` FOREIGN KEY (`id_reservation`) REFERENCES `reservation` (`id_reservation`);
  
-- Insertion des données dans la table employe
INSERT INTO signalements (date, heure, titre, resume, impact, recontact, id_reservation) VALUES
(CURRENT_DATE(), CURRENT_TIME(), 'Ceci est une très beau test', 'Ceci est un très beau test de resume', 2, TRUE, 'R000001'),
(CURRENT_DATE(), CURRENT_TIME(), 'Oui', 'Non', 3, FALSE, 'R000019'),
(CURRENT_DATE(), CURRENT_TIME(), 'Deuxième titre', 'Deuxième résume', 0, FALSE, 'R000011');