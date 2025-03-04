-- Insertion des données dans la table employe
INSERT INTO signalements (date, heure, titre, resume, impact, recontact) VALUES
(CURRENT_DATE(), CURRENT_TIME(), 'Ceci est une très beau test', 'Ceci est un très beau test de resume', 2, TRUE),
(CURRENT_DATE(), CURRENT_TIME(), 'Deuxième titre', 'Deuxième résume', 0, FALSE);