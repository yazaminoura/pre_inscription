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

# Générer la clé de l’application
php artisan key:generate

# Créer la base de données (ex. : preinscription)

# Lancer les migrations
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
