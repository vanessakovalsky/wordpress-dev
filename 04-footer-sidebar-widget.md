# Exercice 4 - Zone de widgets, Template du footer et de la sidebar

## Objectifs : 
Cet exercice a pour objectifs :
* De créer une zone pour positionner des widgets dans la sidebar
* De créer le template de la sidebar
* De créer le template footer

## Créer la zone de widget dans la sidebar
* Nous allons commencer par créer une zone qui permettra aux utilisateurs de positionner des widgets.
* Pour cela on déclare une fonction dans notre fichier *functions.php*
```php
/**
 * Register widgetized area and update sidebar with default widgets
 *
 * @since Kovalibre 1.0
 */
function kovalibre_widgets_init() {
    register_sidebar( array(
        'name' => __( 'Primary Widget Area', 'kovalibre' ),
        'id' => 'sidebar-1',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h1 class="widget-title">',
        'after_title' => '</h1>',
    ) );
 
    register_sidebar( array(
        'name' => __( 'Secondary Widget Area', 'kovalibre' ),
        'id' => 'sidebar-2',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h1 class="widget-title">',
        'after_title' => '</h1>',
    ) );
}
add_action( 'widgets_init', 'kovalibre_widgets_init' );
```
* Cette fonction utilise la fonction [*register_sidebar()*](https://codex.wordpress.org/Customizing_Your_Sidebar) qui permet de déclarer sous forme d'un tableau avec différents paramètres une zone dans laquelle un utilisateur pourra positionner des widgets.
* Détaillons les paramètres du tableau : 
    * *name* : contient le nom de la zone (ici nous utilisons une variable traduisible)
    * *before_widget* permet de définir le code html qui marque le début de la zone de widget
    * *after_widget* permet de définir le code html qui marque la fin de la zone
    * *before_title* défini le code html qui est positionner avant le titre de chaque widget
    * *after_title* définit le code html qui est positionner après le titre de chaque widget
* N'oubliez pas d'ajouter le hook action correspondant pour que WordPress puisse connaitre vos zones de widgets avec le *add_action()*.

## Template de la sidebar

* Nous allons maintenant définir le template de notre sidebar qui va inclure nos deux zones de widgets.
* La première zone incluera des widgets par défaut dans le cas où l'utilisateur n'aura pas positionné de widget.
* Ajouter dans le fichier *sidebar.php* le code suivant : 
```php
<?php
/**
* The Sidebar containing the main widget areas.
*
* @package Shape
* @since Shape 1.0
*/
?>
<div id="secondary" class="widget-area" role="complementary">
    <?php do_action( 'before_sidebar' ); ?>
    <?php if ( ! dynamic_sidebar( 'sidebar-1' ) ) : ?>
 
        <aside id="search" class="widget widget_search">
            <?php get_search_form(); ?>
        </aside>
 
        <aside id="archives" class="widget">
            <h1 class="widget-title"><?php _e( 'Archives', 'shape' ); ?></h1>
            <ul>
                <?php wp_get_archives( array( 'type' => 'monthly' ) ); ?>
            </ul>
        </aside>
 
        <aside id="meta" class="widget">
            <h1 class="widget-title"><?php _e( 'Meta', 'shape' ); ?></h1>
            <ul>
                <?php wp_register(); ?>
                <li><?php wp_loginout(); ?></li>
                <?php wp_meta(); ?>
            </ul>
        </aside>
 
    <?php endif; // end sidebar widget area ?>
</div><!-- #secondary .widget-area -->
```
* Pour la zone *secondary* :
    * la fonction [*do_action()*](https://developer.wordpress.org/reference/functions/do_action/) permet d'appeler tous les hook attaché au hook donnée en paramètre
    * la fonction [*dynamic_sidebar()*](https://developer.wordpress.org/reference/functions/dynamic_sidebar/) permet de vérifier si la sidebar contient des widgets positionnés par l'utilisateur
    * la fonction [*get_search_form()*](https://developer.wordpress.org/reference/functions/get_search_form/) permet de récupérer le widget qui affiche le formulaire de recherche
    * la fonction [*wp_get_archives()*](https://codex.wordpress.org/index.php?title=Function_Reference/wp_get_archives&oldid=162172) permet d'afficher la liste des archives par date
    * la fonction [*wp_register()*](https://developer.wordpress.org/reference/functions/wp_register/) permet d'afficher le lien d'inscription ou un lien vers l'administration si l'utilisateur est connecté
    * la fonction [*wp_loginout()*](https://developer.wordpress.org/reference/functions/wp_loginout/) permet d'afficher un lien vers la page de connexion ou de déconnexion (en fonction de si l'utilisateur est connecté ou non) 
    * la fonction [*wp_meta()*](https://developer.wordpress.org/reference/functions/wp_meta/) est une action qui permet notamment de permettre de changer de thème (dans le cas où vous définirez plusieurs thèmes disponibles). Il sert surtout à définir un widget dans lequel on peut venir depuis une fonction ajoutée le contenu de son choix.
* Dans le cas où l'utilisateur a positionné des widgets dans cette zone, c'est bien les widgets choisis par l'utilisateur qui s'affiche.
* Passons maintenant au template de la deuxième zone : 
```php
<?php if ( is_active_sidebar( 'sidebar-2' ) ) : ?>
 
     <div id="tertiary" class="widget-area" role="supplementary">
      <?php dynamic_sidebar( 'sidebar-2' ); ?>
     </div><!-- #tertiary .widget-area -->
 
<?php endif; ?>
```
* Ici on utilise la fonction [*is_active_sidebar('nomdelazone')*](https://developer.wordpress.org/reference/functions/is_active_sidebar/) pour vérifier si la zone contient bien des widgets que l'on vient afficher avec la fonction *dynamic_sidebar()*.

## Template du footer

* Il nous reste à définir le template du footer dans le fichier du même nom : 
```php
<?php
/**
* The template for displaying the footer.
*
* Contains the closing of the id=main div and all content after
*
* @package Kovalibre
* @since Kovalibre 1.0
*/
?>
 
</div><!-- #main .site-main -->
 
<footer id="colophon" class="site-footer" role="contentinfo">
    <div class="site-info">
        <?php do_action( 'kovalibre_credits' ); ?>
        <a href="http://wordpress.org/" title="<?php esc_attr_e( 'A Semantic Personal Publishing Platform', 'kovalibre' ); ?>" rel="generator"><?php printf( __( 'Proudly powered by %s', 'kovalibre' ), 'WordPress' ); ?></a>
        <span class="sep"> | </span>
        <?php printf( __( 'Theme: %1$s by %2$s.', 'kovalibre' ), 'Kovalibre', '<a href="https://kovalibre.com/" rel="designer">VanessaKovalsky</a>' ); ?>
    </div><!-- .site-info -->
</footer><!-- #colophon .site-footer -->
</div><!-- #page .hfeed .site -->
 
<?php wp_footer(); ?>
 
</body>
</html>
```
* Pas de nouvelle fonction dans ce template, seulement les crédit et liens habituels

## A vous de jouer

* Ajouter une zone que l'on viendra positionner dans le footer, pour permettre l'affichage de notre widget réseau social dans le footer.
* Créer un widget (texte pour l'instant) et positionner le dans le footer pour correspondre à la maquette. 
* La prochaine étape va être de développer un plugin pour notre widget.