# Cas ZERNE

Implémentation objet PHP MVC avec persistance MySQL pour gérer les disponibilités
des pompiers par date et par créneau de six heures.

## Architecture MVC

- `Models/` contient les classes métier (`Pompier`, `Periode`, `Caserne` et `Collection`).
- `Controllers/` prépare les données et orchestre les actions.
- `Views/` contient l'affichage HTML, sans logique métier.
- `public/index.php` est l'unique point d'entrée de l'application.
- `BDD/` contient le script de création de la base de données.
- `Models/Database.php` initialise automatiquement la base `zerne`.
- `Models/PompierRepository.php` enregistre chaque statut dans `avoir_activite`.

## Fonctionnement

- `Collection` reprend les opérations de l’annexe D.
- L'interface propose quatre créneaux fixes : 00h-06h, 06h-12h, 12h-18h et 18h-00h.
- Un statut peut être enregistré pour une date passée, actuelle ou future.
- Les noms de fonctions d'accès sont explicites : `afficherPlanning`,
  `enregistrerDisponibilite`, `trouverParDateEtCreneau` et `enregistrerStatut`.
- `BDD/schema.sql` crée les cinq tables et leurs clés étrangères.

## Exécution

### Avec XAMPP

1. Copier le dossier `ZERNE` dans `C:\xampp\htdocs\`.
2. Démarrer **Apache** et **MySQL** dans le panneau de contrôle XAMPP.
3. Vérifier que l'extension `pdo_mysql` est activée dans `php.ini`.
4. Ouvrir [http://localhost/ZERNE/](http://localhost/ZERNE/).

Par défaut, l'application utilise MySQL sur `127.0.0.1:3306`, avec l'utilisateur
`root` sans mot de passe. Ces valeurs peuvent être remplacées par les variables
`ZERNE_DB_HOST`, `ZERNE_DB_PORT`, `ZERNE_DB_NAME`, `ZERNE_DB_USER` et
`ZERNE_DB_PASSWORD`. La base `zerne` est créée automatiquement.

Le fichier `.htaccess` redirige automatiquement Apache vers `public/index.php`, qui est le point d'entrée MVC.

### En ligne de commande

Avec PHP 8 et MySQL installés :

```bash
php -S localhost:8000 -t ZERNE/public
```

La page permet de choisir une date et un créneau, puis d'enregistrer directement
le statut de chaque pompier. Les données restent disponibles lorsque l'on revient
sur la même date et le même créneau.
