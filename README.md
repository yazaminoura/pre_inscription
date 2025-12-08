# Système de Préinscription - FSDM Fès

Ce projet est un système de préinscription pour la FST de l’USMBA.
 Fès, développé avec Laravel.

## Prérequis

Avant de commencer, assurez-vous d'avoir installé sur votre machine :

- **PHP** (version 8.1 ou supérieure)
- **Composer** (gestionnaire de dépendances PHP)
- **MySQL** ou **MariaDB** (base de données)
- **Node.js** et **npm** (pour les assets frontend)
- **Git** (pour cloner le projet)

## Installation

### 1. Cloner le projet

```bash
git clone [URL_DU_REPO]
cd preinscription2
```

### 2. Installer les dépendances PHP

```bash
composer install
```

### 3. Installer les dépendances Node.js

```bash
npm install
```

### 4. Configuration de l'environnement

Copiez le fichier d'environnement :

```bash
cp .env.example .env
```

Éditez le fichier `.env` avec vos paramètres de base de données :

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=preinscription_fsdm
DB_USERNAME=votre_username
DB_PASSWORD=votre_password
```

### 5. Générer la clé d'application

```bash
php artisan key:generate
```

### 6. Créer la base de données

Créez une base de données MySQL nommée `preinscription_fsdm` (ou le nom que vous avez choisi dans le .env).

### 7. Exécuter les migrations

```bash
php artisan migrate
```

### 8. Exécuter les seeders (optionnel)

```bash
php artisan db:seed
```

### 9. Compiler les assets frontend

```bash
npm run build
```

### 10. Créer le lien symbolique pour le stockage

```bash
php artisan storage:link
```

## Démarrage du serveur

### Option 1 : Serveur de développement Laravel

```bash
php artisan serve
```

Le projet sera accessible à l'adresse : `http://localhost:8000`

### Option 2 : Serveur de développement avec Vite (pour le développement)

```bash
npm run dev
```

Dans un autre terminal :

```bash
php artisan serve
```

## Structure du projet

### Pages principales

- **Page d'accueil** : `http://localhost:8000/` - Formulaire de préinscription pour les candidats
- **Dashboard administrateur** : `http://localhost:8000/dashboard` - Interface d'administration

### Fonctionnalités

#### Pour les candidats :
- Formulaire de préinscription en plusieurs étapes
- Upload de documents (CV, carte d'identité, baccalauréat, photo)
- Gestion des diplômes, expériences, stages et attestations
- Validation des données en temps réel

#### Pour les administrateurs :
- Gestion des candidats
- Gestion des formations
- Gestion des diplômes, expériences, stages et attestations
- Export des données
- Statistiques des formations

## Comptes par défaut

Si vous avez exécuté les seeders, un compte administrateur par défaut est créé :

- **Email** : admin@fsdm.ma
- **Mot de passe** : password

## Configuration du serveur de production

### Serveur web (Apache/Nginx)

Pour un serveur de production, configurez votre serveur web pour pointer vers le dossier `public/` du projet.

### Variables d'environnement importantes

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://votre-domaine.com
```

## Dépannage

### Problèmes courants

1. **Erreur de permissions** :
   ```bash
   chmod -R 755 storage bootstrap/cache
   ```

2. **Erreur de base de données** :
   - Vérifiez les paramètres dans le fichier `.env`
   - Assurez-vous que la base de données existe

3. **Erreur d'assets** :
   ```bash
   npm run build
   ```

4. **Erreur de cache** :
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan view:clear
   ```

## Technologies utilisées

- **Backend** : Laravel 10
- **Frontend** : HTML, CSS, JavaScript, Bootstrap
- **Base de données** : MySQL
- **Assets** : Vite, Tailwind CSS
- **Upload de fichiers** : Stockage local Laravel

## Support

Pour toute question ou problème, contactez l'équipe de développement.

---

**Faculté des Sciences Dhar El Mahraz - Fès**
