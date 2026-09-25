# Guide d'Installation Rapide - Système de Préinscription

## 🚀 Installation Automatique (Recommandée)

### Windows
1. Double-cliquez sur le fichier `setup.bat`
2. Suivez les instructions à l'écran

### Linux/Mac
1. Ouvrez un terminal dans le dossier du projet
2. Exécutez : `chmod +x setup.sh && ./setup.sh`

## 📋 Installation Manuelle

### Étape 1 : Prérequis
Assurez-vous d'avoir installé :
- PHP 8.1+ 
- Composer
- MySQL/MariaDB
- Node.js et npm

### Étape 2 : Installation
```bash
# 1. Installer les dépendances PHP
composer install

# 2. Installer les dépendances Node.js
npm install

# 3. Copier le fichier d'environnement
cp .env.example .env

# 4. Configurer la base de données dans .env
# Éditez le fichier .env avec vos paramètres MySQL

# 5. Générer la clé d'application
php artisan key:generate

# 6. Créer la base de données MySQL
# Nom suggéré : preinscription

# 7. Créer les tables + le compte admin + les données de démonstration (voir README)
php artisan migrate

# 8. Compiler les assets
npm run build

# 9. Créer le lien de stockage
php artisan storage:link
```

### Étape 3 : Démarrer le projet
```bash
php artisan serve
```

Le projet sera accessible à : **http://localhost:8000**

## 🔑 Compte Administrateur
`php artisan migrate` crée un compte par défaut (si aucun compte n'existe) :
- Email : `admin@gmail.com`
- Mot de passe : `password`

**Changez ce mot de passe dès la première connexion.** Créer un autre compte (le mot de passe vous est demandé) :
```bash
php artisan admin:creer
```
Changer l'email ou le mot de passe d'un compte (même sans l'ancien mot de passe) :
```bash
php artisan admin:modifier
```

## 📁 Structure du projet
- **Page d'accueil** : http://localhost:8000/ (Formulaire candidats)
- **Dashboard admin** : http://localhost:8000/dashboard (Interface administration)

## 🌐 Mise en production

Dans le `.env` du serveur :
```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://votre-domaine
LOG_LEVEL=warning
```
Avec `APP_DEBUG=true`, la moindre erreur affiche aux visiteurs le code, les chemins et les réglages du serveur. Le tableau de bord affiche un avertissement rouge tant qu'il est activé sur un serveur en ligne.

Puis :
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Tâches planifiées
La sauvegarde (2 h) et le nettoyage des dossiers abandonnés (3 h) ont besoin de `php artisan schedule:run` chaque minute :
- **Linux** (`crontab -e`) : `* * * * * cd /chemin/du/projet && php artisan schedule:run >> /dev/null 2>&1`
- **Windows** : Planificateur de tâches > Créer une tâche, déclencheur toutes les minutes, action `php` avec l'argument `artisan schedule:run` et le dossier du projet comme « Commencer dans ».

## 💾 Sauvegardes

Chaque nuit, `php artisan sauvegarde:creer` crée un fichier `preinscription_AAAA-MM-JJ_HHMMSS.zip` qui contient :
- `base.sql` : toute la base de données ;
- `fichiers/` : les pièces des candidats et le logo de l'établissement.

Les 14 dernières sont gardées. Réglages facultatifs dans `.env` :
```
SAUVEGARDE_DOSSIER=D:/Sauvegardes/preinscription   # idéalement un autre disque ou un dossier synchronisé
SAUVEGARDE_GARDER=14
SAUVEGARDE_MYSQLDUMP="C:/Program Files/MySQL/MySQL Server 8.4/bin/mysqldump.exe"   # si mysqldump n'est pas dans le PATH
```
Sans `SAUVEGARDE_DOSSIER`, les fichiers vont dans `storage/app/sauvegardes`, sur le même disque que le site : copiez-les régulièrement ailleurs.

Lancer une sauvegarde à la main : `php artisan sauvegarde:creer`

### Restaurer
1. Décompressez le `.zip`.
2. Base : `mysql -u root -p nom_de_la_base < base.sql`
3. Fichiers : copiez `fichiers/dossiers` vers `storage/app/dossiers` et `fichiers/public/etablissement` vers `storage/app/public/etablissement`.

## ❗ Problèmes courants

### Erreur de permissions
```bash
chmod -R 755 storage bootstrap/cache
```

### Erreur de base de données
- Vérifiez les paramètres dans `.env`
- Assurez-vous que MySQL est démarré
- Créez la base de données

### Erreur d'assets
```bash
npm run build
```

## 📞 Support
En cas de problème, vérifiez le fichier `README.md` pour plus de détails. 
