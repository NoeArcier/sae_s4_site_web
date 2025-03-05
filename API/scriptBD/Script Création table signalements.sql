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