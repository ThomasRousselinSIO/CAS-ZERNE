CREATE TABLE volontaire (
    id INTEGER PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    numeroBip VARCHAR(20) NOT NULL UNIQUE,
    nbGardes INTEGER NOT NULL DEFAULT 0 CHECK (nbGardes >= 0)
);

CREATE TABLE tranche (
    id INTEGER PRIMARY KEY CHECK (id BETWEEN 1 AND 4),
    libelle VARCHAR(30) NOT NULL UNIQUE
);

CREATE TABLE periode_garde (
    idTranche INTEGER NOT NULL,
    datePeriode DATE NOT NULL,
    nbPompiers INTEGER NOT NULL CHECK (nbPompiers >= 0),
    PRIMARY KEY (idTranche, datePeriode),
    FOREIGN KEY (idTranche) REFERENCES tranche(id)
);

CREATE TABLE disponibilite (
    id INTEGER PRIMARY KEY CHECK (id BETWEEN 1 AND 3),
    libelle VARCHAR(20) NOT NULL UNIQUE
);

CREATE TABLE avoir_activite (
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
    (1, 'Nuit'), (2, 'Matinée'), (3, 'Après-midi'), (4, 'Soirée');

INSERT INTO disponibilite (id, libelle) VALUES
    (1, 'Indisponible'), (2, 'Au travail'), (3, 'Disponible');
