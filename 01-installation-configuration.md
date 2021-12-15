# Exercice 1 - Installer et configurer son environnement

## Objectifs : 
Cet exercice a pour objectifs : 
* d'installer un environnement wordpress et son serveur web
* de configurer son environnement de développement

## Pré-requis
* un serveur web installé (serveur web comme Apache ou Nginx + PHP + une BDD comme MariaDB) (par exemple https://www.apachefriends.org/fr/index.html ) ou docker et docker-compose installé 
* avoir un editeur de code (exemple https://code.visualstudio.com/)
* avoir un navigateur web (exemple https://www.mozilla.org/fr/firefox/new/ )
* Composer (https://getcomposer.org/)
* Git (https://git-scm.com/book/fr/v2/D%C3%A9marrage-rapide-Installation-de-Git)

## Installation de Wordpress

Vous connaissez probablement déjà en tant que webmaster l'installation manuelle de WordPress telle que décrite dans la [documentation officielle](https://fr.wordpress.org/txt-install/). Ici nous allons installer wordpress afin de pouvoir l'utiliser dans un environnement de développement nous permettant notamment de versionner notre code et par la suite d'automatiser le déploiement de notre Wordpress.

### Initialiser son projet avec composer et git sur 

* Il est nécessaire de créer une base de données et un utilisateur avec les droits sur cette base de données avant de lancer l'installation
* Nous allons devoir créer à la main quelques fichiers et dossiers pour pouvoir utiliser Composer et Git avec wordpress. 
* L'arborescence de notre dossier de ressemblera à celle-ci : 
```
votresite.test
|- .git
|-- wp (le « Noyau » de WordPress)
|----- wp-admin
|----- wp-content (ce dossier est inutile, mais il sera installé)
|----- wp-includes
|----- les autres fichiers WordPress
|-- wp-content (notre dossier content)
|----- languages
|----- plugins
|----- themes
|----- uploads
|----- vendor
|-- index.php
|-- wp-config.php
|- .gitignore
|- composer.json
```
* Nous allons avoir besoin de créer 3 fichiers, les autres seront créé par Composer ou par git : 
    * index.php
    * wp-config.php
    * composer.json
    * .gitignore

* Voici le contenu du fichier *index.php*
```php
<?php
/**
 * Define WP Blog Header location..
 *
 * @package WordPress
 */

define( 'WP_USE_THEMES', true );
require './wp/wp-blog-header.php';
```
* Voici le contenu du fichier *wp-config.php*. N'oubliez pas de l'adapter aux paramètres de votre environnement (URL, chaine de connexion notamment)
```php
<?php
/**
 * The base configuration for WordPress.
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @package WordPress
 * @see https://codex.wordpress.org/Editing_wp-config.php
 */

// Composer autoloader
require __DIR__ . '/wp-content/vendor/autoload.php';

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'database_name_here' );

// MySQL database username.
define( 'DB_USER', 'username_here' );

// MySQL database password.
define( 'DB_PASSWORD', 'password_here' );

// MySQL hostname.
define( 'DB_HOST', 'localhost' );

// Database Charset to use in creating database tables.
define( 'DB_CHARSET', 'utf8' );

// The Database Collate type. Don't change this if in doubt.
define( 'DB_COLLATE', '' );

/*
// @+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY', 'put your unique phrase here' );
define( 'SECURE_AUTH_KEY', 'put your unique phrase here' );
define( 'LOGGED_IN_KEY', 'put your unique phrase here' );
define( 'NONCE_KEY', 'put your unique phrase here' );
define( 'AUTH_SALT', 'put your unique phrase here' );
define( 'SECURE_AUTH_SALT', 'put your unique phrase here' );
define( 'LOGGED_IN_SALT', 'put your unique phrase here' );
define( 'NONCE_SALT', 'put your unique phrase here' );

// #@-

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/*
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DISABLE_FATAL_ERROR_HANDLER', false );
define( 'WP_DEBUG', false );
define( 'WP_DEBUG_LOG', false );
define( 'WP_DEBUG_DISPLAY', false );
define( 'SAVEQUERIES', false );

/*
 * Custom WordPress Install Path.
 */
// Sets the site's admin location and the site's location, respectively.
define( 'WP_SITEURL', 'https://yourdomainname.com/wp' );
define( 'WP_HOME', 'https://yourdomainname.com' );
// Sets the content location, related to what's defined on composer.json file.
define( 'WP_CONTENT_DIR', dirname( __FILE__ ) . '/wp-content' );
// Sets the plugins location, related to what's defined on composer.json file.
define( 'WP_CONTENT_URL', WP_HOME . '/wp-content' );
define( 'WP_PLUGIN_URL', WP_CONTENT_URL . '/plugins' );
// Disables the embebeded editor.
define( 'DISALLOW_FILE_EDIT', true );
define( 'DISALLOW_FILE_MODS', true );
define( 'RELOCATE', true );
// Disables automatic update functions.
define( 'AUTOMATIC_UPDATER_DISABLED', true );
define( 'WP_AUTO_UPDATE_CORE', false );

/*
 * SSL
 * You might want to force SSL on the admin page
 */
define( 'FORCE_SSL_ADMIN', true );

// That's all, stop editing! Happy publishing.

// Absolute path to the WordPress directory.
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
```
* Quelques changements ont été apportés à ce fichier par rapport à un fichier *wp-config.php* standard
    * indication du chemin vers le fichier autoload de Composer
    * configuration de la base de données
    * définition des clés unique d’authentification et du salage.
    * désactivation des mises à jour automatiques et du menu mise à jour
    * désactivation de l’édition des fichiers thèmes et plugins
    * définition des chemins d’installation pour le « Noyau », les thèmes et les plugins. 

* Il nous reste à créer le fichier *composer.json* avec le contenu suivant :
```json 
{
  "name": "vanessakovalsky/wordpress",
  "description": "WordPress installation with Composer",
  "keywords": [
    "wordpress", "composer"
  ],
  "version": "1.0.0",
  "license": "GPL-2.0-or-later",
  "authors": [
    {
      "name": "Vanessa Kovalsky",
      "homepage": "https://www.kovalibre.com/"
    }
  ],
  "type": "project",
  "require": {
    "composer/installers": "~1.0",
    "wordpress/wordpress": "5.8.2",
    "koodimonni-language/core-fr_fr": "*",
    "wpackagist-theme/twentytwentyone": "*",
    "wpackagist-plugin/akismet": "^4.2.1"
  },
  "repositories": [
    {
      "type": "composer",
      "url": "https://wpackagist.org"
    },
    {
      "type": "package",
      "package": {
        "name": "wordpress/wordpress",
        "type": "webroot",
        "version": "5.8.2",
        "source": {
          "url": "https://github.com/WordPress/WordPress.git",
          "type": "git",
          "reference": "5.8.2"
        },
        "require": {
          "fancyguy/webroot-installer": "^1.0.0"
        }
      }
    },
    {
      "type": "composer",
      "url": "https://wp-languages.github.io"
    }
  ],
  "config": {
    "vendor-dir": "wp-content/vendor"
  },
  "extra": {
    "installer-paths": {
      "wp-content/plugins/{$name}/": [
        "type:wordpress-plugin"
      ],
      "wp-content/themes/{$name}/": [
        "type:wordpress-theme"
      ]
    },
    "webroot-dir": "wp",
    "webroot-package": "wordpress/wordpress",
    "wordpress-install-dir": "wp",
    "dropin-paths": {
      "wp-content/languages/": [
        "vendor:koodimonni-language"
      ],
      "wp-content/languages/plugins/": [
        "vendor:koodimonni-plugin-language"
      ],
      "wp-content/languages/themes/": [
        "vendor:koodimonni-theme-language"
      ]
    }
  }
}
```
* Ce fichier effectue plusieurs actions : 
    * Téléchargement de Wordpress depuis le dépôt Git officiel
    * Récupération depuis wppackagist du theme twentytwentyon et du plugin akismet
    * Téléchargement et installation des traductions en français
    * Indication de l'emplacement du dossier vendor où les différentes bibliothèques et dépendances seront installées   
    * Définition des chemins d'instalations pour les différents types dans la propriété *extra*

* Enfin pour éviter de versionner des fichiers de contenu, nous indiquons dans le fichier .gitignore les différents répertoire et fichiers à ne pas suivre en version :
```
# Ignorer les dépendances
**/node_modules/
**/vendor/

# Ignorer le répertoire wp
wp/

# Ne pas ignorer le répertoire wp-content mais ignorer son contenu
!wp-content/
wp-content/*

# Ne pas ignorer le répertoire themes mais ignorer son contenu
!wp-content/themes
wp-content/themes/*

# Ne pas ignorer ce thème
wp-content/themes/your-custom-theme

# Ne pas ignorer le répertoire plugins mais ignorer son contenu
!wp-content/plugins
wp-content/plugins/*

# Ne pas ignorer ces plugins
!wp-content/plugins/your-custom-plugin1
!wp-content/plugins/your-custom-plugin2

# Ne pas ignorer le répertoire mu-plugins mais ignorer son contenu
!wp-content/mu-plugins
!wp-content/mu-plugins/*

# Ne pas ignorer ces mu-plugins
!wp-content/mu-plugins/your-custom-mu-plugin1.php
!wp-content/mu-plugins/your-custom-mu-plugin2.php

```
* Le contenu de ce fichier sera a adapter lors de la création de plugins et de thèmes pour permettre de versionner correctement votre environnement et surtout vos plugins et vos thèmes

### Installer son projet sur un serveur local 

* Lancer votre serveur web, et copier les fichiers que nous venons de créer dans le dossier web (htdocs ou www ou html ou spécifique selon votre configuration)
* Dans une console, lancer les deux commandes suivantes : 
```
git init
composer install
```
* Votre projet est alors prêt à être installé, ouvrez un navigateur et suivre les étapes d'installation

### Avec docker-compose

* Il est possible de créer un environnement de développement rapidement en utilisant docker compose
* Créer un fichier docker-compose.yml avec le contenu suivant :
```yml
version: "3.9"
    
services:
  db:
    image: mysql:5.7
    volumes:
      - db_data:/var/lib/mysql
    restart: always
    environment:
      MYSQL_ROOT_PASSWORD: somewordpress
      MYSQL_DATABASE: wordpress
      MYSQL_USER: wordpress
      MYSQL_PASSWORD: wordpress
    
  wordpress:
    depends_on:
      - db
    image: wordpress:latest
    volumes:
      - wordpress_data:/var/www/html
    ports:
      - "8000:80"
    restart: always
    environment:
      WORDPRESS_DB_HOST: db:3306
      WORDPRESS_DB_USER: wordpress
      WORDPRESS_DB_PASSWORD: wordpress
      WORDPRESS_DB_NAME: wordpress
volumes:
  db_data: {}
  wordpress_data: {}
```
* Créer un dossier au même niveau que le fichier *docker-compose.yml* et copier le contenu du dossier créé à l'étape 1
* Puis lancer la commande : 
```sh
docker-compose up -d
```
* Votre wordpress est alors accessible sur http://localhost:8000
* Il ne vous reste plus qu'à suivre les étapes d'installation

## Configurer et sécuriser son site 

* Nous allons commencer par compléter le fichier .htaccess (à créé s'il n'existe pas) avec quelques règles pour sécuriser notre site : 
```
#Sécurisons d'abord notre site :
#Blocage de la visibilité du fichier wp-config.php
<Files wp-config.php>
order allow,deny
deny from all
</Files>
#Fin du blocage
#Interdiction de visualisation des repertoires du site :
Options All -Indexes
# Masquer les informations relatives au serveur :
ServerSignature Off
# Protéger .htaccess et .htpasswds
<Files ~ "^.*\.([Hh][Tt][AaPp])">
order allow,deny
deny from all
satisfy all
</Files>
# Masquer l'identification d'un auteur
<IfModule mod_rewrite.c>
RewriteCond %{QUERY_STRING} ^author=([0-9]*)
RewriteRule .* - [F]
</IfModule>
# Éviter le spam de commentaires
<IfModule mod_rewrite.c>
RewriteCond %{REQUEST_METHOD} POST
RewriteCond %{REQUEST_URI} .wp-comments-post\.php*
RewriteCond %{HTTP_REFERER} !.monsite.com.* [OR]
RewriteCond %{HTTP_USER_AGENT} ^$
RewriteRule (.*) ^http://%{REMOTE_ADDR}/$ [R=301,L]
</IfModule>
#Limiter l'accès au site à certains utilisateurs - Il faudra remplacer xxx.xxx.xxx.xxx par l'adresse IP
<Limit GET POST>
order allow,deny
deny from xxx.xxx.xxx.xxx
allow from all
</Limit>
#Optimisons les éléments favorables au référencement naturel
#Retirer l'expression "category" de vos urls
RewriteRule ^category/(.+)$ https://www.yourblog.com/$1 [R=301,L]
#Autoriser l'utilisation du cache
<Ifmodule mod_expires.c>  
<filesmatch "\.(jpg|gif|png|css|js)$">
ExpiresActive on
ExpiresDefault "access plus 1 year"
</filesmatch> </ifmodule>
#Rediriger les internautes vers une page de maintenance - Lorsque vous faîtes des modifications
RewriteEngine on
RewriteCond %{REQUEST_URI} !/maintenance.html$
RewriteCond %{REMOTE_ADDR} !^123\.123\.123\.123
RewriteRule $ /maintenance.html [R=302,L]
#Installer une redirection 301
Redirect 301 /www.monsite.com/monanciennepage.com /
 https.monsite.com/manouvellepage.com
```
* Dans l'interface d'administration, n'oubliez pas de faire ces réglages : 
  * Général :
    * inscription pour tous à désactiver
  * Lecture :
    * Tronquer le flux RSS
    * Laissez les moteurs de recherche tranquilles
  * Discussion :
    * Modérez les commentaires
    * Ne pas diviser les commentaires en sous-pages
    * Ne pas activer les commentaires imbriqués
  * Permaliens :
    * le nom de l’article uniquement
* Toujours dans l'interface, un peu de nettoyage s'impose :
  * Supprimer les articles, pages, commentaire par défaut
  * Allez vérifier aussi du côté des plugins et des thèmes si rien n'est installé qui n'est pas utile, et n'hésitez pas à supprimer des choses
* Créer un nouveau compte avec les droits d'administrateur et supprimer celui-crée lors de l'installation (plus d'informations ici)
* Quelques plugins très utile :
  * Pour la sécurité : https://fr.wordpress.org/plugins/better-wp-security/ 
  * Pour la sauvegarde des fichiers :https://fr.wordpress.org/plugins/backwpup/
  * Pour la sauvegarde de la base de données : https://fr.wordpress.org/plugins/wp-dbmanager/ 
  * Pour le SEO : https://fr.wordpress.org/plugins/wordpress-seo/ 
  * Pour le cache : https://fr.wordpress.org/plugins/w3-total-cache/ 
* Pour installer ces plugins passer par la commande composer : 
```
composer require wordpress/nomduplugin
```

## Paramètrer son environnement de développement 

* Il nous reste à configurer notre éditeur de code
* Installer les extensions suivantes dans VSCode : 
  *  WordPress Core Snippets for Visual Studio Code 
  * Wordpress Development Toolkit
  * WPCS Whitelist Flags
  * Wordpress VS Code Extension Pack
  * WordPress Toolbox
* Apprendre à deboguer WordPress dans VSCode : https://sethreid.co.nz/debugging-wordpress-with-visual-studio-code/ 
* Configurer les standards de code de WordPress dans VSCode : https://sridharkatakam.com/set-wordpress-coding-standards-visual-studio-code/ 

* Sur wordpress, n'hésitez pas à rajouter le plugin Debug bar : https://wordpress.org/plugins/debug-bar/ 



