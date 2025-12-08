#  Installation Rapide – Système de Préinscription FST

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

# Créer la base de données (ex. : preinscription_fsdm)

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

## 🔐 Compte Admin (par défaut)
- Email : `adminfst20252@fsdm.ma`
- Mot de passe : `fst11231123`

---

## 📁 Pages Importantes
- Formulaire Candidats : `/`
- Interface Admin : `/dashboard`

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
Consultez le fichier `README.md` ou contactez le support.
