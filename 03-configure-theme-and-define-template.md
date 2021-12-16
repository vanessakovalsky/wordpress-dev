# Exercice 3 - Configurer le theme et définir les templates

## Objectifs
Cet exercice a pour objectifs de : 
* Savoir définir des fonctions et les appelers
* De définir des fichiers supplémentaires dans le dossier inc pour garder le fichier fonctions.php propre
* De compléter le template du header et celui de l'index

## Définir des fonctions et les appeler
* Le fichier fonction permet de définir des fonctions à utiliser dans ses templates.
* Ajouter au début du fichier un commentaire précisant à quoi sert ce fichier : 
```php
<?php
/**
 * Kovalibre functions and definitions
 *
 * @package Kovalibre
 * @since Kovalibre 1.0
 */
```
* Cela décrit le contenu du fichier et permet de définir de la documentation sous forme de tag PHPDoc, voir plus d'information sur la [documentation en ligne](https://developer.wordpress.org/coding-standards/inline-documentation-standards/)
* Nous allons maintenant définir la variable globale *$content_width*, celle-ci permet de limiter la taille maximum du contenu de notre thème.
* Pour cela ajouter dans le fichier *functions.php* le code suivant :
```php
/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * @since Kovalibre 1.0
 */
if ( ! isset( $content_width ) )
    $content_width = 800; /* pixels */
```
* Nous allons maintenant créer une fonction qui défini des paramètre par défaut pour notre thème et permet le support de nombreuses fonctionnalités WordPress.
* Ajouter dans le fichier *functions.php* le code suivant : 
```php
if ( ! function_exists( 'kovalibre_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * @since Kovalibre 1.0
 */
function kovalibre_setup() {
 
    /**
     * Custom template tags for this theme.
     */
    require( get_template_directory() . '/inc/template-tags.php' );
 
    /**
     * Custom functions that act independently of the theme templates
     */
    require( get_template_directory() . '/inc/tweaks.php' );
 
    /**
     * Make theme available for translation
     * Translations can be filed in the /languages/ directory
     * If you're building a theme based on Kovalibre, use a find and replace
     * to change 'kovalibre' to the name of your theme in all the template files
     */
    load_theme_textdomain( 'kovalibre', get_template_directory() . '/languages' );
 
    /**
     * Add default posts and comments RSS feed links to head
     */
    add_theme_support( 'automatic-feed-links' );
 
    /**
     * Enable support for the Aside Post Format
     */
    add_theme_support( 'post-formats', array( 'aside' ) );
 
    /**
     * This theme uses wp_nav_menu() in one location.
     */
    register_nav_menus( array(
        'primary' => __( 'Primary Menu', 'kovalibre' ),
    ) );
}
endif; // kovalibre_setup
add_action( 'after_setup_theme', 'kovalibre_setup' );
```
* Détaillons un peu ce code : 
    * Nous appelons deux fichiers *inc/template-tags.php* et *inc/tweaks.php* pour inclure des fonctions que nous allons définir juste après
    * Nous utilisons la fonction *load_theme_textdomain()* qui permet de dire à WordPress de rendre notre thème traduisible et que les fichiers de traductions doivent se trouvé dans un dossier appelé *languages*.
    * Puis nous ajoutons le support pour les liens vers le flux RSS dans le header et pour [Aside Post Format](https://wordpress.org/support/article/post-formats/)
    * La dernière fonction enregistre le menu principal de notre site. 
    * *add_action()* permet de dire à WordPress d'exécuter notre fonction *kovalibre_setup()*

## Définir les fichiers à inclure

* Nous allons maintenant ajouter le code dans les fichiers inclus dans notre fichier *functions.php*
* Commençons avec le fichier *inc/template-tags.php*. Les [templates tags (Marqueur de modèle)](https://codex.wordpress.org/fr:Marqueurs_de_Modele) permettent d'afficher dynamiquement des informations depuis les templates de notre thème. De nombreux marqueurs sont disponibles et pourront être redéfinis dans ce fichier. Nous ajoutons au fichier une entête pour préciser ce qu'est ce fichier
```php
<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package Kovalibre
 * @since Kovalibre 1.0
 */
```
* Nous reviendrons par la suite définir des templates tags et les utiliser
* Nous allons maintenant créer le deuxième fichier *inc/tweaks.php*. Celui-ci permet de surcharger des fonctionnalités du coeur de WordPress. 
* Voici le contenu à mettre pour l'instant :
```php
<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package Kovalibre
 * @since Kovalibre 1.0
 */

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * @since Kovalibre 1.0
 */
function kovalibre_page_menu_args( $args ) {
    $args['show_home'] = true;
    return $args;
}
add_filter( 'wp_page_menu_args', 'kovalibre_page_menu_args' );
 
/**
 * Adds custom classes to the array of body classes.
 *
 * @since Kovalibre 1.0
 */
function kovalibre_body_classes( $classes ) {
    // Adds a class of group-blog to blogs with more than 1 published author
    if ( is_multi_author() ) {
        $classes[] = 'group-blog';
    }
 
    return $classes;
}
add_filter( 'body_class', 'kovalibre_body_classes' );
 
/**
 * Filter in a link to a content ID attribute for the next/previous image links on image attachment pages
 *
 * @since Kovalibre 1.0
 */
function kovalibre_enhanced_image_navigation( $url, $id ) {
    if ( ! is_attachment() && ! wp_attachment_is_image( $id ) )
        return $url;
 
    $image = get_post( $id );
    if ( ! empty( $image->post_parent ) && $image->post_parent != $id )
        $url .= '#main';
 
    return $url;
}
add_filter( 'attachment_link', 'kovalibre_enhanced_image_navigation', 10, 2 );
```
* Etudions un peu les fonctions que nous avons défini :
    * *kovalibre_page_menu_args()* permet d'ajouter des pages à notre menu principal (que nous acons définis dans *kovalibre_setup()*). Par défaut le menu liste les pages si aucun menu de navigation n'est configuré. Ici nous ajoutons un lien vers la page d'accueil à notre menu
    * *kovalibre_body_classes()* permet d'ajouter la classe CSS 'group-blog' en cas d'article écrit par plusieurs auteurs. Nous reparlerons de cette fonction un peu plus tard pour le template du header
    * *kovalibre_enhanced_image_navigation()* ajoute une anche '#main' sur les liens suivant / précédents des images sur les pages d'attachement (nous définirons ces pages plus tard dans un autre exercice). Souvenez-vous que '#main' est l'ID du div qui englobe notre contenu et la zone de widget. Cela permet aux gens lorsqu'il clique sur un lien d'image suivant / précédent de ne pas avoir à redescendre en bas de la page pour voir chaque image.   
* Nous avons terminé avec la configuration de notre thème pour l'instant.

## Compléter le template header

### La section Head

* Commençons par définir les métadonnées du fichier *header.php* :
```php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package Kovalibre
 * @since Kovalibre 1.0
 */
?><!DOCTYPE html>
```
* Ensuite nous définissons le tag *html* en ajoutant un *id* si on est sur IE8, cela nous permet de pouvoir gérer dans le CSS en utilisant l'ID plutôt que de définir une feuille de style spécifique: 
```html
<!--[if IE 8]>
<html id="ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 8) ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
```
* Nous définissons maintenant la section *head* 
```html
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<title><?php
/*
* Print the <title> tag based on what is being viewed.
*/
global $page, $paged;
 
wp_title( '|', true, 'right' );
 
// Add the blog name.
bloginfo( 'name' );
 
// Add the blog description for the home/front page.
$site_description = get_bloginfo( 'description', 'display' );
if ( $site_description && ( is_home() || is_front_page() ) )
echo " | $site_description";
 
// Add a page number if necessary:
if ( $paged >= 2 || $page >= 2 )
echo ' | ' . sprintf( __( 'Page %s', 'kovalibre' ), max( $paged, $page ) );
 
?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->
<?php wp_head(); ?>
</head>
 
<body <?php body_class(); ?>>
```
* Etudions un peu ces lignes : 
    * les balises *meta* définissen l'encodage et la taille du viewport comme la taille du périphérique utilisé. 
    * la balise *title* affiche le titre du site en haut de la fenêtre du navigateur. L'affichage du titre dépend de la page affichée :
        * Pour toute les pages, sauf la page d'accueil, nous voulons afficher le titre de la page courante. Pour cela nous utilisons la fonction de WordPress *wp_title()*. Nous ajoutons un séparateur *|* et sur la droite nous affichons le nom du site avec la fonction *blog_name()*
        * Sur la page d'accueil nous affichons les choses différement : le nom du site, un séparateur et la description du site. Pour obtenir la description, nous utilisons la méthode *get_bloginfo()* à laquelle nous demandons de nous afficher la données *description*
        * Enfin nous ajoutons le numéro de la page pour les pages listants les anciens articles
    * Les balises *link* permettent d'ajouter le support du [XFN](http://gmpg.org/xfn/) et la seconde permet de founir un lien de retour vers notre site (pingback)
    * Les lignes suivantes permettent aux vieilles version de navigateur, de charger les fichier JavaScript
    * Ensuite nous avons l'appel à *wp_head()* cela est requis, il permet aux plugins de WordPress de venir surcharger la section *head*
    * Enfin la balise *body* qui utilise la fonction *body_class()* pour récupérer les classes CSS qui doivent être associés au body. (Y-compris celle que l'on a défini dans notre fichier *tweaks.php*)

### La section header
* Nous allons maintenant ajouter le titre du site qui sera aussi un lien pour revenir à la page d'accueil, ainsi que la description du site et le menu.
* Remplacer les balises *hgroup* présentes dans le fichier *header.php* par le contenu suivant
```php
<hgroup>
     <h1 class="site-title"><a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
     <h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
</hgroup>
```
* Détaillons ces lignes
    * Nous utilisons dans le code ci-dessus un *Template Tag* appelée *home_url()*. Celui-cu permet de récupérer l'URL principal de votre site WordPress.
    * Pour obtenir le nom du site, nous utilisons *bloginfo()* que nous avions déjà utilisé. Celle-ci permet d'obtenir de [nombreuses informations sur le site](https://developer.wordpress.org/reference/functions/bloginfo/) 
    * Nous échappons le titre avec la fonction *esc_attr()* sur l'attribut titre

* Nous définissons la navigation, avec la balise *nav*
    * Nous ajoutons un lien d'évitement poru faciliter la vie à ceux qui utilise un lecteur d'écran et éviter la lecture du menu
    ```php
    <h1 class="assistive-text"><?php _e( 'Menu', 'kovalibre' ); ?></h1>
    <div class="assistive-text skip-link"><a href="#content" title="<?php esc_attr_e( 'Skip to content', '_s' ); ?>"><?php _e( 'Skip to content', 'kovalibre' ); ?></a></div>
    ```
    * Puis nous ajoutons le menu que nous avons défini dans le paramètrage : 
    ```php
    <?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>
    ```
* Nous allons maintenant ajouter une fonction dans notre fichier *functions.php* pour charger nos deuilles de style et fichiers JavaScript.
```php
/**
 * Enqueue scripts and styles
 */
function kovalibre_scripts() {
    wp_enqueue_style( 'style', get_stylesheet_uri() );
 
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
 
    wp_enqueue_script( 'navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );
 
    if ( is_singular() && wp_attachment_is_image() ) {
        wp_enqueue_script( 'keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20120202' );
    }
}
add_action( 'wp_enqueue_scripts', 'kovalibre_scripts' );
```
* Etudions ce code :
    * Dans cette fonction nous utilisons les méthodes [*wp_enqueue_style()*](https://developer.wordpress.org/reference/functions/wp_enqueue_style/) et [*wp_enqueue_script()*](https://developer.wordpress.org/reference/functions/wp_enqueue_script/) pour charger nos feuilles de styles et nos fichier JavaScript. C'est une bonne pratique de les utiliser pour charger les CSS et JavaScript dans un thème ou un plugins, plûtot que de les charger en dur dans le *header.php*.
    * A la fin de la fonction, nous ajoutons *kovalibre_scripts()* à *wp_enqueue_scripts()*, ce qui pemert d'ajouter dynamiquement les liens de vos feuilles de styles et scripts au header.
    * Nous chargeons le script (fourni par WordPress) 'comment-reply.js' que si les commentaires imbriqués sont activés
    * Les deux éléments suivants sont optionnels : 
        * Le premier permet d'afficher un menu burger en affichage mobile
        * Le second de naviguer avec le clavier lorsque l'on affiche une image en plein ecran. Ce script a besoin de jQuery pour fonctionner. Nous lui avons passer en paramètre.
* Il ne reste plus qu'à créer les deux fichiers JavaScript : 
    * Ajouter dans le dossier *js* le fichier *navigation.js* avec le contenu qui se trouve sur [cette page](https://github.com/Automattic/_s/blob/master/js/navigation.js)
    * Ajouter dans le dossier *js* le fichier *keyboard-image-navigation.js* avec le contenu qui se trouve sur [cette page](https://github.com/Automattic/_s/blob/430d379f4bc97e6f20d366ec4a06588c5c614abc/js/keyboard-image-navigation.js) 

## Compléter le template index


### Le Header de l'index
* Nous allons maintenant compléter le fichier index.php, commençons par ajouter le *header* avec la fonction *get_header()*. Celle-ci a pour effet d'inclure le fichier *header.php* que nous avons défini auparavant. Le code ci-dessous est à ajouter avant la structure *html* que nous avions défini précédemment
```php
<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Kovalibre
 * @since Kovalibre 1.0
 */
 
get_header(); ?>
```
### Le contenu de la page
* Nous allons ensuite étudier [la boucle](https://codex.wordpress.org/fr:La_Boucle) qui permet d'afficher le contenu de la page. Celle-ci va aller chercher l'ensemble des articles présent en base de données et les afficher. 
```php
<?php 
if ( is_search() ) : // Only display Excerpts for Search ?>
<div class="entry-summary">
     <?php the_excerpt(); ?>
</div><!-- .entry-summary -->
<?php else : ?>
<div class="entry-content">
     <?php the_content( __( 'Continue reading <span class="meta-nav">→</span>', 'kovalibre' ) ); ?>
     <?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'kovalibre' ), 'after' => '</div>' ) ); ?>
</div><!-- .entry-content -->
<?php endif; ?>
```
* Nous utilisons pour cela les fonctions :
    * *have_posts()* qui permet de s'assurer qu'il y a encore des article à afficher
    * *the_post()* qui permet de récupérer un article
    * [*the_content()*](https://developer.wordpress.org/reference/functions/the_content/) qui permet d'afficher le contenu d'un article
    * [*the_excerpt()*](https://developer.wordpress.org/reference/functions/the_excerpt/) qui permet d'afficher le résumé d'un article
    * *is_search() permet de tester si nous sommes sur la page qui affiche les résultats de recherche, de sorte ici à ce que les résumés ne soit affiché que dans le cas d'une recherche
* Ajoutons le titre de l'article au sein d'une balise *header*
```php
<header class="entry-header">
     <h1 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', '_s' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
</header><!-- .entry-header -->
```
* Ici nous avons deux nouvelles fonctions qui sont appelées : 
    * [*the_title()*](https://developer.wordpress.org/reference/functions/the_title/) qui affiche le titre de l'article
    * [*the_permalink()*](https://developer.wordpress.org/reference/functions/the_permalink/) qui affiche le lien permanent vers l'article
* Nous allons maintenant encapsuler tout ça dans une balise article et ajouter les tags, catégories et commentaires
```php
<?php /* The Loop — with comments! */ ?>
<?php while ( have_posts() ) : the_post() ?>
<?php /* Create an HTML5 article section with a unique ID thanks to the_ID() and semantic classes with post_class() */ ?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
     <header class="entry-header">
          <h1 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', '_s' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
 
          <?php if ( 'post' == get_post_type() ) : // Only display post date and author if this is a Post, not a Page. ?>
          <div class="entry-meta">
               <?php kovalibre_posted_on(); ?>
          </div><!-- .entry-meta -->
          <?php endif; ?>
     </header><!-- .entry-header -->
 
     <?php if ( is_search() ) : // Only display Excerpts on Search results pages ?>
     <div class="entry-summary">
          <?php the_excerpt(); ?>
     </div><!-- .entry-summary -->
     <?php else : ?>
     <div class="entry-content">
          <?php the_content( __( 'Continue reading <span class="meta-nav">→</span>', 'kovalibre' ) ); ?>
          <?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'kovalibre' ), 'after' => '</div>' ) ); ?>
     </div><!-- .entry-content -->
     <?php endif; ?>
 
<?php /* Show the post's tags, categories, and a comment link. */ ?>
     <footer class="entry-meta">
          <?php if ( 'post' == get_post_type() ) : // Hide category and tag text for Pages in Search results ?>
          <?php
               /* translators: used between list items, there is a space after the comma */
               $categories_list = get_the_category_list( __( ', ', 'kovalibre' ) );
               if ( $categories_list && kovalibre_categorized_blog() ) :
          ?>
          <span class="cat-links">
               <?php printf( __( 'Posted in %1$s', 'kovalibre' ), $categories_list ); ?>
          </span>
          <?php endif; // End if categories ?>
 
          <?php
               /* translators: used between list items, there is a space after the comma */
               $tags_list = get_the_tag_list( '', __( ', ', 'kovalibre' ) );
               if ( $tags_list ) :
          ?>
               <span class="sep"> | </span>
               <span class="tag-links">
                    <?php printf( __( 'Tagged %1$s', 'kovalibre' ), $tags_list ); ?>
               </span>
               <?php endif; // End if $tags_list ?>
          <?php endif; // End if 'post' == get_post_type() ?>
 
          <?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
          <span class="sep"> | </span>
          <span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'kovalibre' ), __( '1 Comment', 'kovalibre' ), __( '% Comments', 'kovalibre' ) ); ?></span>
          <?php endif; ?>
 
          <?php edit_post_link( __( 'Edit', 'kovalibre' ), '<span class="sep"> |   </span><span class="edit-link">', '</span>' ); ?>
     </footer><!-- .entry-meta -->
<?php /* Close up the article and end the loop. */ ?>
</article><!-- #post-<?php the_ID(); ?> -->
<?php endwhile; ?>
```
* Le code ci-dessus est un peu long, mais il prend en compte les différents cas de figure (0/1/plusieurs tags, idem pour les catégories, est ce que c'est un article ou un autre type de contenu comme une page ...)
* Voici la présentation des fonctions utilisées :
    * *the_ID()* est un shortcode qui permet d'afficher l'ID du contenu courant
    * *kovalibre_posted_on()* est une fonction que nous allons définir après pour affiché la date et l'auteur du contenu
    * *get_post_type()* permet de récupérer le type de contenu du contenu courant
    * *get_category_list()* permet d'obtenir la listes des catégories du contenu courant
    * *kovalibre_categorized_blog()* est une fonction que nous allons définir par la suite qui permettra de vérifier si le contenu a plus d'une catégorie
    * *get_the_thag_list()* permet d'obtenir la liste des tags sur le contenu courant
    * *post_password_required()* permet de vérifier si le contenu est protégé par mot de passe
    * *comments_open()* permet de vérifier si les commentaires sont ouverts sur ce contenu
    * *get_comments_number()* permet d'obtenir le nombre de commentaire sur le contenu
    * *comments_popup_link()* permet d'afficher la zone de commentaire
    * *edit_post_link()* affiche le lien pour modifier l'article (si l'utilisateur courant est autorisé à le modifier)

### Les fonctions spécifiques

* Nous allons définir les fonctions spéciques *kovalibre_posted_on()* et *kovalibre_categorized_blog()*. Celles-ci sont définies dans le fichier *inc/template-tags.php*
```php
if ( ! function_exists( 'kovalibre_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 *
 * @since Kovalibre 1.0
 */
function kovalibre_posted_on() {
    printf( __( 'Posted on <a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s" pubdate>%4$s</time></a><span class="byline"> by <span class="author vcard"><a class="url fn n" href="%5$s" title="%6$s" rel="author">%7$s</a></span></span>', 'kovalibre' ),
        esc_url( get_permalink() ),
        esc_attr( get_the_time() ),
        esc_attr( get_the_date( 'c' ) ),
        esc_html( get_the_date() ),
        esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
        esc_attr( sprintf( __( 'View all posts by %s', 'kovalibre' ), get_the_author() ) ),
        esc_html( get_the_author() )
    );
}
endif;
 
/**
 * Returns true if a blog has more than 1 category
 *
 * @since Kovalibre 1.0
 */
function kovalibre_categorized_blog() {
    if ( false === ( $all_the_cool_cats = get_transient( 'all_the_cool_cats' ) ) ) {
        // Create an array of all the categories that are attached to posts
        $all_the_cool_cats = get_categories( array(
            'hide_empty' => 1,
        ) );
 
        // Count the number of categories that are attached to the posts
        $all_the_cool_cats = count( $all_the_cool_cats );
 
        set_transient( 'all_the_cool_cats', $all_the_cool_cats );
    }
 
    if ( '1' != $all_the_cool_cats ) {
        // This blog has more than 1 category so kovalibre_categorized_blog should return true
        return true;
    } else {
        // This blog has only 1 category so kovalibre_categorized_blog should return false
        return false;
    }
}
 
/**
 * Flush out the transients used in kovalibre_categorized_blog
 *
 * @since Kovalibre 1.0
 */
function kovalibre_category_transient_flusher() {
    // Like, beat it. Dig?
    delete_transient( 'all_the_cool_cats' );
}
add_action( 'edit_category', 'kovalibre_category_transient_flusher' );
add_action( 'save_post', 'kovalibre_category_transient_flusher' );
```
* Détaillons ces fonctions : 
    * dans *kovalibre_posted_on()* nous utilisons des template tag pour récupérer et afficher la date, l'auteur, l'heure, l'url de l'auteur, et le permalien du contenu
    * dans *kovalibre_categorized_blog()* nous récupérons toutes les catégories qui ont au moins un post, et stockons cette liste dans une variable temporaire (appelée *transient*) sous forme de tableau. S'il y a plus d'une categorie, les catégories sont affichs dans les liens utiles de l'article. 
    * dans *kovalibre_transient_flusher()* nous supprimons la variable temporaire soit lors de la modification d'une catégorie soit à l'enregistrement d'un article. Cela permet de garder à jour la liste des catégories.

### Simplification du template Index
* Afin de pouvoir réutiliser le code de la boucle que nous avons écrit dans d'autres template, nous allons le déplacer dans un fichier spécifique qui sera inclu dans l'index.
* Créer un fichier *content.php* avec le contenu suivant : 
```php
<?php
/**
 * @package Kovalibre
 * @since Kovalibre 1.0
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
     <header class="entry-header">
          <h1 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', '_s' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
 
          <?php if ( 'post' == get_post_type() ) : // Only display post date and author if this is a Post, not a Page. ?>
          <div class="entry-meta">
               <?php kovalibre_posted_on(); ?>
          </div><!-- .entry-meta -->
          <?php endif; ?>
     </header><!-- .entry-header -->
 
     <?php if ( is_search() ) : // Only display Excerpts on Search results pages ?>
     <div class="entry-summary">
          <?php the_excerpt(); ?>
     </div><!-- .entry-summary -->
     <?php else : ?>
     <div class="entry-content">
          <?php the_content( __( 'Continue reading <span class="meta-nav">→</span>', 'kovalibre' ) ); ?>
          <?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'kovalibre' ), 'after' => '</div>' ) ); ?>
     </div><!-- .entry-content -->
     <?php endif; ?>
 
<?php /* Show the post's tags, categories, and a comment link. */ ?>
     <footer class="entry-meta">
          <?php if ( 'post' == get_post_type() ) : // Hide category and tag text for Pages in Search results ?>
          <?php
               /* translators: used between list items, there is a space after the comma */
               $categories_list = get_the_category_list( __( ', ', 'kovalibre' ) );
               if ( $categories_list && kovalibre_categorized_blog() ) :
          ?>
          <span class="cat-links">
               <?php printf( __( 'Posted in %1$s', 'kovalibre' ), $categories_list ); ?>
          </span>
          <?php endif; // End if categories ?>
 
          <?php
               /* translators: used between list items, there is a space after the comma */
               $tags_list = get_the_tag_list( '', __( ', ', 'kovalibre' ) );
               if ( $tags_list ) :
          ?>
               <span class="sep"> | </span>
               <span class="tag-links">
                    <?php printf( __( 'Tagged %1$s', 'kovalibre' ), $tags_list ); ?>
               </span>
               <?php endif; // End if $tags_list ?>
          <?php endif; // End if 'post' == get_post_type() ?>
 
          <?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
          <span class="sep"> | </span>
          <span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'kovalibre' ), __( '1 Comment', 'kovalibre' ), __( '% Comments', 'kovalibre' ) ); ?></span>
          <?php endif; ?>
 
          <?php edit_post_link( __( 'Edit', 'kovalibre' ), '<span class="sep"> |   </span><span class="edit-link">', '</span>' ); ?>
     </footer><!-- .entry-meta -->
<?php /* Close up the article and end the loop. */ ?>
</article><!-- #post-<?php the_ID(); ?> -->
```
* Il s'agit du contenu de la balise article de tout à l'heure qui n'a pas été modifié, seulement déplace.
* Dans le fichier index, nous remplaçons cette balise *article* et son contenu par un appel à notre nouveau template *content.php*
```php
<?php if ( have_posts() ) : ?>
     <?php /* Start the Loop */ ?>
     <?php while ( have_posts() ) : the_post(); ?>
 
          <?php
          /* Include the Post-Format-specific template for the content.
          * If you want to overload this in a child theme then include a file
          * called content-___.php (where ___ is the Post Format name) and    that will be used instead.
          */
          get_template_part( 'content', get_post_format() );
          ?>
     <?php endwhile; ?>
<?php endif; ?>
```
* Cela permet d'aller inclure le template *content.php* via la fonction [*get_template_part()*](https://developer.wordpress.org/reference/functions/get_template_part/) et donc ne changera rien en terme d'affichage. Par contre cela a réduit drastiquement la taille de notre fichier *index.php* et cela nous permet de réutiliser cette affichage d'un contenu dans d'autres templates

### Le format de Post Aside

* Nous avions défini un peu plus tôt que nous autorisions l'utilisation du format de post Aside. Un [format de Post](https://wordpress.org/support/article/post-formats/) est une pièce de métadonnée qui peut être utilisé par un thème pour personnaliser la présentation d'un post. 
* Le format *Aside* est prévu pour un affichage court. 
* Nous créons un nouveau fichier *content-aside.php* pour définir ce format d'affichage. Celui-ci contient :
```php
<?php
/**
 * The template for displaying posts in the Aside post format
 * @package Kovalibre
 * @since Kovalibre 1.0
 */
?>
 
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header">
        <h1 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'kovalibre' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
    </header><!-- .entry-header -->
 
    <?php if ( is_search() ) : // Only display Excerpts for Search ?>
    <div class="entry-summary">
        <?php the_excerpt(); ?>
    </div><!-- .entry-summary -->
    <?php else : ?>
    <div class="entry-content">
        <?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'kovalibre' ) ); ?>
        <?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'kovalibre' ), 'after' => '</div>' ) ); ?>
    </div><!-- .entry-content -->
    <?php endif; ?>
 
    <footer class="entry-meta">
        <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'kovalibre' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php echo get_the_date(); ?></a>
        <?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
        <span class="sep"> | </span>
        <span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'kovalibre' ), __( '1 Comment', 'kovalibre' ), __( '% Comments', 'kovalibre' ) ); ?></span>
        <?php endif; ?>
 
        <?php edit_post_link( __( 'Edit', 'kovalibre' ), '<span class="sep"> | </span><span class="edit-link">', '</span>' ); ?>
    </footer><!-- .entry-meta -->
</article><!-- #post-<?php the_ID(); ?> -->
```
* Cela ressemble au contenu de *content.php* sans le titre, le nom de l'auteur, les catégories et les tags.
* Notre template spécifique à notre format est maintenant défini.

### Navigation
* Nous aurons besoin d'un moyen de naviguer entre les articles - à la fois sur la page d'un seul article mais aussi sur la page des archives. 
* Pour la page des articles nous pouvons utilisers les deux templates tags : *next_posts_links()* et *previous_posts_links()* /!\ au nom des fonction qui sont inversé (next appel le post précédent, et previous le post suivant).
* Nous allons définir notre propre fonction dans le fichier *inc/template-tags.php*
```php
if ( ! function_exists( 'kovalibre_content_nav' ) ):
/**
 * Display navigation to next/previous pages when applicable
 *
 * @since Kovalibre 1.0
 */
function kovalibre_content_nav( $nav_id ) {
    global $wp_query, $post;
 
    // Don't print empty markup on single pages if there's nowhere to navigate.
    if ( is_single() ) {
        $previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
        $next = get_adjacent_post( false, '', false );
 
        if ( ! $next && ! $previous )
            return;
    }
 
    // Don't print empty markup in archives if there's only one page.
    if ( $wp_query->max_num_pages < 2 && ( is_home() || is_archive() || is_search() ) )
        return;
 
    $nav_class = 'site-navigation paging-navigation';
    if ( is_single() )
        $nav_class = 'site-navigation post-navigation';
 
    ?>
    <nav role="navigation" id="<?php echo $nav_id; ?>" class="<?php echo $nav_class; ?>">
        <h1 class="assistive-text"><?php _e( 'Post navigation', 'kovalibre' ); ?></h1>
 
    <?php if ( is_single() ) : // navigation links for single posts ?>
 
        <?php previous_post_link( '<div class="nav-previous">%link</div>', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'kovalibre' ) . '</span> %title' ); ?>
        <?php next_post_link( '<div class="nav-next">%link</div>', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'kovalibre' ) . '</span>' ); ?>
 
    <?php elseif ( $wp_query->max_num_pages > 1 && ( is_home() || is_archive() || is_search() ) ) : // navigation links for home, archive, and search pages ?>
 
        <?php if ( get_next_posts_link() ) : ?>
        <div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'kovalibre' ) ); ?></div>
        <?php endif; ?>
 
        <?php if ( get_previous_posts_link() ) : ?>
        <div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'kovalibre' ) ); ?></div>
        <?php endif; ?>
 
    <?php endif; ?>
 
    </nav><!-- #<?php echo $nav_id; ?> -->
    <?php
}
endif; // kovalibre_content_nav
```
* Cette fonction permet de définir le comportement de la navigation en fonction des cas (articles, pages, d'autres articles existent ou non, etc.)
* Appelons la fonction dans notre *index.php*.
    * ajouter avant la boucle
    ```php
    <?php kovalibre_content_nav( 'nav-above' ); ?>
    ```
    * ajouter après la boucle
    ```php
    <?php kovalibre_content_nav( 'nav-below' ); ?>
    ```
* La navigation est prête et la balise *nav* sera bien ajouté dans les cas où cela est nécessaire

### Ajouter la gestion de l'absence de résultat
* Créer un fichier *no-results.php* qui contiendra le comportement de notre affichage en cas d'absence de résultat sur notre boucle.
* Celui-ci contient le code suivant :
```php
<?php
/**
 * The template part for displaying a message that posts cannot be found.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Kovalibre
 * @since Kovalibre 1.0
 */
?>
 
<article id="post-0" class="post no-results not-found">
    <header class="entry-header">
        <h1 class="entry-title"><?php _e( 'Nothing Found', 'kovalibre' ); ?></h1>
    </header><!-- .entry-header -->
 
    <div class="entry-content">
        <?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>
 
            <p><?php printf( __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'kovalibre' ), admin_url( 'post-new.php' ) ); ?></p>
 
        <?php elseif ( is_search() ) : ?>
 
            <p><?php _e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'kovalibre' ); ?></p>
            <?php get_search_form(); ?>
 
        <?php else : ?>
 
            <p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'kovalibre' ); ?></p>
            <?php get_search_form(); ?>
 
        <?php endif; ?>
    </div><!-- .entry-content -->
</article><!-- #post-0 .post .no-results .not-found -->
```
* Il nous reste à inclure ce fichier dans notre *index.php* entre la fin de la navigation et la fin du if(*endif*) :
```php
<?php else : ?>
     <?php get_template_part( 'no-results', 'index' ); ?>
```

### Ajouter l'inclusion de la sidebar et du footer

* La toute dernière étape, on ajout dans notre fichier *index.php* les templates de la sidebar et du footer à la toute fin du fichier : 
```php
<?php get_sidebar(); ?>
<?php get_footer(); ?>
```

## A vous de jouer 
* Définir les fichiers *functions.php*, *header.php* et *index.php* qui permettent de mettre en place le contenu de la page comme présenter sur la maquette. 
* Pour l'affichage des articles dans les différents blocs, on va considérer pour l'instant que ce sont des articles,  de la catégorie Réalisation (vous pouvez créer cette catégorie à l'initialisation du thème avec la fonction qui permet l'[ajout de catagorie](https://developer.wordpress.org/reference/functions/wp_create_category/)) pour le bloc *Réalisation* et avec la catégorie 'Compétence' pour le bloc compétence. 
* Les bloc "Référente...", Contact et footer ne sont pas à faire pour l'instant.

