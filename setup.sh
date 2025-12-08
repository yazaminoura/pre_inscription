#!/bin/bash

echo "========================================"
echo "Installation du Systeme de Preinscription"
echo "========================================"
echo

echo "1. Installation des dependances PHP..."
composer install
if [ $? -ne 0 ]; then
    echo "ERREUR: Impossible d'installer les dependances PHP"
    exit 1
fi

echo
echo "2. Installation des dependances Node.js..."
npm install
if [ $? -ne 0 ]; then
    echo "ERREUR: Impossible d'installer les dependances Node.js"
    exit 1
fi
echo
echo "3. Copie du fichier d'environnement..."
if [ ! -f .env ]; then
    cp .env.example .env
    echo "Fichier .env cree. Veuillez le configurer avec vos parametres de base de donnees."
else
    echo "Fichier .env existe deja."
fi

echo
echo "4. Generation de la cle d'application..."
php artisan key:generate
if [ $? -ne 0 ]; then
    echo "ERREUR: Impossible de generer la cle d'application"
    exit 1
fi

echo
echo "5. Creation du lien symbolique pour le stockage..."
php artisan storage:link
if [ $? -ne 0 ]; then
    echo "ATTENTION: Impossible de creer le lien symbolique (peut etre deja existe)"
fi

echo
echo "6. Compilation des assets frontend..."
npm run build
if [ $? -ne 0 ]; then
    echo "ERREUR: Impossible de compiler les assets"
    exit 1
fi

echo
echo "========================================"
echo "Installation terminee avec succes!"
echo "========================================"
echo
echo "ETAPES SUIVANTES:"
echo "1. Configurez votre base de donnees dans le fichier .env"
echo "2. Creez une base de donnees MySQL"
echo "3. Executez: php artisan migrate"
echo "4. Executez: php artisan db:seed (optionnel)"
echo "5. Lancez le serveur: php artisan serve"
echo
echo "Le projet sera accessible a: http://localhost:8000"
echo 