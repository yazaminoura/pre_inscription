# Guide d'Installation Rapide - Système de Préinscription FSDM

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
# Nom suggéré : preinscription_fsdm

# 7. Exécuter les migrations
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

## 🔑 Compte Administrateur par défaut
- **Email** : admin@fsdm.ma
- **Mot de passe** : password

## 📁 Structure du projet
- **Page d'accueil** : http://localhost:8000/ (Formulaire candidats)
- **Dashboard admin** : http://localhost:8000/dashboard (Interface administration)

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