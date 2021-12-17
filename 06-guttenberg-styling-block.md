# Exercice 6 - Jouer avec Gutenberg

## Objectifs
Cet exercice a pour objectifs : 
* de manipuler Gutenberg
* de définir les styles des nos blocs et les alignements
* de créer des compositions de blocs via un plugin

## Manipuler Guttenberg

### Découvrir et utiliser les types de blocs
* Aller dans l'administration et créer une page
* Dans cette page, utiliser les blocs pour créer la page d'accueil de notre site à partir de la maquette présentée dans l'exercice 2
* Vous aller utiliser différents types de blocs : 
    * Paragraphe pour le bloc "Référente"
    * Boucle de requête poru les réalisations
    * Paragraphe pour les compétence
    * Paragraphe pour le formulaire (ou alors utiliser un plugin comme : [Contact Form 7](https://fr.wordpress.org/plugins/contact-form-7/))
* Vous savez maintenant composer un contenu avec les blocs guttenberg.
* Si vous le souhaitez vous avez une présentation détaillé des différents éléments de l'interface de Gutenberg ici : https://www.pourpasunrond.fr/mise-en-page/

### Utiliser les compositions de bloc

* Créer une autre page
* Lorsque vous cliquer sur le bouton plus pour ajouter un bloc, cliquer sur l'onglet Composition
* Etudier les différentes compositions disponibles et choisir celles qui vous parait la plus adapté pour une page qui liste les réalisations en accord avec le style de la maquette de la page d'accueil.
* Appliquer une composition et charger dedans la liste des articles réalisations.

## Définir les alignements et les styles de nos blocs

### Activer des alignements supplémentaire pour nos blocs.

* Commençons par ajouter le support des alignements supplémentaires dans notre thème.
* Ajouter la fonction suivante dans le fichier *functions.php* de votre thème (vérifier avant si cette fonction n'est pas déjà défini, dans ce cas là, ajouter seulement la ligne *add_theme_support()* dans votre fonction existante): 
```php
function kovalibre_setup() {
    add_theme_support('align-wide' );
}
add_action('after_setup_theme','kovalibre_setup' );
```
* Il ne vous reste plus qu'à définir les CSS associé à ces alignements, ajouter ces lignes dans votre fichier de style (ou un fichier dédié à ajouter avec *wp_enqueue_style()*) : 
```css
.site {
    max-width: 100vw;
}

.entry-content > *:not(.alignfull):not(.alignwide):not(.alignleft):not(.alignright) {
    margin-left: auto;
    margin-right: auto;
    max-width: 1024px;
}

.alignfull {
    width: 100%;
}

.alignwide {
    margin-left: auto;
    margin-right: auto;
    width: 80%;
}
/* Nous calculons l'espace restant autour du contenu et nous
 * divisons par 2 puisque le contenu est centré. */
.alignleft {
    max-width: calc(1024px / 2);
    margin-left: calc((100% - 1024px) / 2);
}

.alignright {
    max-width: calc(1024px / 2);
    margin-right: calc((100% - 1024px) / 2);
}
```
* Vous pouvez maintenant utiliser ces deux nouveaux alignements dans l'editeur du back-office

### Definir une feuille de style pour l'éditeur
* Pour définir une feuille de style pour l'éditeur, nous activons cela dans le thème.
* Ajouter dans la fonction *kovalibre_setup()* du fichier *functions.php* de votre thème les lignes suivantes
```php
add_theme_support( 'editor-styles' );
add_editor_style( 'editor-style.css' );
```
* Créer le fichier *editor-style.css* et déclarer vos css à l'intérieur.
* Celui-ci est maintenant utilisé lors de l'édition de vos blocs 

### Ajouter une variation de bloc

* Créer un nouveau plugin, nous l'appelons kovalibre_gutenberg_variation_style.
* Dans le fichier *kovalibre_gutenberg_variation_style.php* ajouter le code suivant : 
```php
<?php
/*
Plugin Name: Kovalibre Gutenberg Variation Style
*/
function myguten_enqueue() {
    wp_enqueue_script( 'myguten-script', plugins_url( 'myguten.js', __FILE__ ), array( 'wp-blocks' )
);
}
add_action( 'enqueue_block_editor_assets', 'myguten_enqueue' );
```
* Puis créer un fichier *myguten.js* avec le contenu suivant :
```js
wp.blocks.registerBlockStyle(
'core/quote', { 
    name: 'kovalibre-quote',
    label: 'Kovalibre Quote',
} );
```
* Dans l'interface vous pouvez utiliser votre style
* Et vous amuser à définir votre propre CSS pour ce style de variation de bloc.

## Créer des compositions de blocs via un plugin et les blocs réutilisables

* Installer le plugin Reusable Blocks Extends : https://fr.wordpress.org/plugins/reusable-blocks-extended/
```
composer require wpackagist-plugins/reusable-blocks-extended
```
* Activer le plugin dans l'interface
* Aller dans la page d'accueil que vous avez créé, et créer un nouveau bloc de texte avec le contenu de votre choix. Vous avez maintenant la possibilité de l'enregistrer comme bloc réutilisable, faite le.
* Dans le menu de Gauche, Blocs réutilisables à du apparaître, cliquer dessus.
* Le bloc que vous avez enregistré apparait.
* Sur la ligne de ce bloc, cliquer sur Convertir en composition, vous avez maintenant une nouvelle composition.
* Créer une nouvelle page, et appliquer votre nouvelle composition.


## Pour aller plus loin : 
* Définir sa propre composition : https://la-webeuse.com/compositions-gutenberg-patterns-motifs/
* Créer son propre type de bloc : https://code.tutsplus.com/tutorials/building-gutenberg-blocks-with-create-guten-block--cms-31519 
* Un exemple de plugin de bloc : https://github.com/ArmandPhilippot/post-types-query-block 
