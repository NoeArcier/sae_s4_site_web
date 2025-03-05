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
	id_employe CHAR(7) NOT NULL
) CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `signalements`
  ADD CONSTRAINT `fk_employe` FOREIGN KEY (`id_employe`) REFERENCES `login` (`id_employe`);