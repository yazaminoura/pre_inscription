@echo off
echo ========================================
echo Installation du Systeme de Preinscription
echo ========================================
echo.

echo 1. Installation des dependances PHP...
composer install
if %errorlevel% neq 0 (
    echo ERREUR: Impossible d'installer les dependances PHP
    pause
    exit /b 1
)

echo.
echo 2. Installation des dependances Node.js...
npm install
if %errorlevel% neq 0 (
    echo ERREUR: Impossible d'installer les dependances Node.js
    pause
    exit /b 1
)

echo.
echo 3. Copie du fichier d'environnement...
if not exist .env (
    copy .env.example .env
    echo Fichier .env cree. Veuillez le configurer avec vos parametres de base de donnees.
) else (
    echo Fichier .env existe deja.
)

echo.
echo 4. Generation de la cle d'application...
php artisan key:generate
if %errorlevel% neq 0 (
    echo ERREUR: Impossible de generer la cle d'application
    pause
    exit /b 1
)

echo.
echo 5. Creation du lien symbolique pour le stockage...
php artisan storage:link
if %errorlevel% neq 0 (
    echo ATTENTION: Impossible de creer le lien symbolique (peut etre deja existe)
)

echo.
echo 6. Compilation des assets frontend...
npm run build
if %errorlevel% neq 0 (
    echo ERREUR: Impossible de compiler les assets
    pause
    exit /b 1
)

echo.
echo ========================================
echo Installation terminee avec succes!
echo ========================================
echo.
echo ETAPES SUIVANTES:
echo 1. Configurez votre base de donnees dans le fichier .env
echo 2. Creez une base de donnees MySQL
echo 3. Executez: php artisan migrate
echo 4. Executez: php artisan db:seed (optionnel)
echo 5. Lancez le serveur: php artisan serve
echo.
echo Le projet sera accessible a: http://localhost:8000
echo.
pause 