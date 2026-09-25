#  Installation Rapide – Système de Préinscription

## Installation Manuelle

### ✅ Prérequis
- PHP 8.1+
- Composer
- MySQL 
- Node.js + npm

### 📦 Étapes
```bash
# Installer les dépendances PHP
composer install

# Installer les dépendances JavaScript
npm install

# Copier et configurer l’environnement
cp .env.example .env
# -> Modifier les infos MySQL dans .env
# -> Sur votre PC : APP_ENV=local et APP_DEBUG=true

# Générer la clé de l’application
php artisan key:generate

# Créer la base de données (ex. : preinscription)

# Créer les tables + le compte admin + les données de démonstration
php artisan migrate

# Compiler les fichiers front
npm run build

# Lier le dossier de stockage
php artisan storage:link
```

###  Lancer le projet
```bash
php artisan serve
```

Accès : **http://localhost:8000**

---

## 🔐 Compte Admin
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

---

## 🎓 Données de démonstration
Le premier `php artisan migrate` remplit aussi le site pour que vous puissiez l'essayer tout de suite :
- une école fictive, **Université Horizon** (à remplacer dans Paramètres > Établissement) ;
- **14 formations** (Licence, Master, DUT, BTS, cycle ingénieur, doctorat…), traduites en anglais et en arabe ;
- **24 candidats** avec leurs pièces (PDF factices), diplômes, stages et expériences ;
- des candidatures dans tous les statuts (en attente, en cours, acceptée, refusée, liste d'attente, dossier incomplet), déposées sur les 14 derniers jours.

Tous les emails se terminent par `@example.com` : aucun vrai candidat ne reçoit de message.

Les données ne sont créées que si la base n'a encore aucune formation : un nouveau `migrate` ne crée pas de doublons.

- **Démarrer avec une base vide** : mettez `DEMO_DONNEES=false` dans `.env` avant le premier `migrate`.
- **Tout recommencer à zéro** (⚠️ efface toute la base) : `php artisan migrate:fresh`
- **Recharger les données** dans une base existante : `php artisan db:seed`

---

## 📁 Pages Importantes
- Formulaire Candidats : `/`
- Interface Admin : `/dashboard`

---

## 🚀 Mise en production et sauvegardes
Voir `GUIDE_INSTALLATION.md` : `APP_DEBUG=false`, tâches planifiées et sauvegarde quotidienne.

---

## 🧯 Problèmes fréquents

### Droits d’accès
```bash
chmod -R 755 storage bootstrap/cache
```

### Erreurs MySQL
- Vérifiez `.env`
- Démarrez MySQL
- Créez la base de données

### Problèmes d’assets
```bash
npm run build
```

---

## 💬 Besoin d’aide ?
Consultez le fichier `GUIDE_INSTALLATION.md`.
