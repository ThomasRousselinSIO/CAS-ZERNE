CREATE TABLE IF NOT EXISTS volontaire (
    id INTEGER PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    numeroBip VARCHAR(20) NOT NULL UNIQUE,
    nbGardes INTEGER NOT NULL DEFAULT 0 CHECK (nbGardes >= 0)
);

CREATE TABLE IF NOT EXISTS tranche (
    id INTEGER PRIMARY KEY CHECK (id BETWEEN 1 AND 4),
    libelle VARCHAR(30) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS periode_garde (
    idTranche INTEGER NOT NULL,
    datePeriode DATE NOT NULL,
    nbPompiers INTEGER NOT NULL CHECK (nbPompiers >= 0),
    PRIMARY KEY (idTranche, datePeriode),
    FOREIGN KEY (idTranche) REFERENCES tranche(id)
);

CREATE TABLE IF NOT EXISTS disponibilite (
    id INTEGER PRIMARY KEY CHECK (id BETWEEN 1 AND 3),
    libelle VARCHAR(20) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS avoir_activite (
    idVolontaire INTEGER NOT NULL,
    idTranche INTEGER NOT NULL,
    datePeriode DATE NOT NULL,
    idDisponibilite INTEGER NOT NULL,
    deGarde INTEGER NOT NULL DEFAULT 0 CHECK (deGarde IN (0, 1)),
    PRIMARY KEY (idVolontaire, idTranche, datePeriode),
    FOREIGN KEY (idVolontaire) REFERENCES volontaire(id),
    FOREIGN KEY (idTranche, datePeriode)
        REFERENCES periode_garde(idTranche, datePeriode),
    FOREIGN KEY (idDisponibilite) REFERENCES disponibilite(id)
);

INSERT INTO tranche (id, libelle) VALUES
    (1, '00h00 - 06h00'), (2, '06h00 - 12h00'),
    (3, '12h00 - 18h00'), (4, '18h00 - 00h00')
ON DUPLICATE KEY UPDATE libelle = VALUES(libelle);

INSERT INTO disponibilite (id, libelle) VALUES
    (1, 'Indisponible'), (2, 'Au travail'), (3, 'Disponible')
ON DUPLICATE KEY UPDATE libelle = VALUES(libelle);

INSERT INTO volontaire (id, nom, prenom, numeroBip) VALUES
    (1, 'Durand', 'François', '0600000001'),
    (2, 'Legall', 'Yasmina', '0600000002'),
    (3, 'Dubois', 'Yves', '0600000003')
ON DUPLICATE KEY UPDATE nom = VALUES(nom), prenom = VALUES(prenom);
