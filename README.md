# Cas ZERNE

Implémentation objet PHP MVC du cas fourni dans le PDF `2 TP2 ZERNE`.

## Architecture MVC

- `Models/` contient les classes métier (`Pompier`, `Periode`, `Caserne` et `Collection`).
- `Controllers/` prépare les données et orchestre les actions.
- `Views/` contient l'affichage HTML, sans logique métier.
- `public/index.php` est l'unique point d'entrée de l'application.
- `BDD/` contient le script de création de la base de données.

## Fonctionnement

- `Collection` reprend les opérations de l’annexe D.
- `Periode` maintient les collections `enMission`, `auTravail` et `disponible`.
- `selectEquipe` prend d’abord les pompiers disponibles, puis ceux au travail.
- `missionner` déplace le pompier vers `enMission`.
- `Caserne::appelEquipe` vérifie l’appartenance à la caserne, missionne chaque pompier et appelle son bip.
- `BDD/schema.sql` reprend les cinq tables du sujet et toutes les clés étrangères de `AVOIR_ACTIVITE`.

## Exécution

### Avec XAMPP

1. Copier le dossier `ZERNE` dans `C:\xampp\htdocs\`.
2. Démarrer **Apache** dans le panneau de contrôle XAMPP.
3. Ouvrir [http://localhost/ZERNE/](http://localhost/ZERNE/).

Le fichier `.htaccess` redirige automatiquement Apache vers `public/index.php`, qui est le point d'entrée MVC.

### En ligne de commande

Avec PHP 8 installé :

```bash
php -S localhost:8000 -t ZERNE/public
```

La démonstration demande deux pompiers pour une période qui contient un pompier disponible et un pompier au travail. Elle montre donc la priorité attendue et les statuts après mobilisation (`m`).
