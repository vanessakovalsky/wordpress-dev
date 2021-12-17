# Exercice 5 - Création d'un plugin pour afficher un Widget

## Objectifs 
Cet exercice a pour objectifs : 
* De créer un premier plugin
* Ce plugin permettra de paramètrer un widget avec des liens vers différents réseaux sociaux dans l'administration
* De fournir un affichage par défaut à notre plugin

## Créer un premier plugin

* Commençons par créer le dossier dans *wp-content/plugins*, on crée un dossier pour notre plugin *kovalibre-social-network-widget*
* Nous avons maintenant besoin de créer un fichier qui porte le nom de notre plugin (et le nom de notre dossier) : *kovalibre-social-network-widget.php*
* Commençons par définir le plugin avec un commentaire comme pour la création du thème : 
```php
<?php
 
/*
 
Plugin Name: Kovalibre Social Network Widget
 
Plugin URI: https://kovalibre.com/
 
Description: Plugin to show a widget with link to social netwokr.
 
Version: 1.0
 
Author: Vanessa Kovalsky
 
Author URI: https://github.com/vanessakovalsky
 
License: GPLv2 or later
 
Text Domain: kovalibre
 
*/
```
* Si vous allez dans l'administration, sur le menu Plugins vous verrez votre Plugin apparaitre, vous pouvez dès à présent l'activer
* Pour définir notre plugin, nous allons définir une classe en utilisant [*l'API Widget*](https://codex.wordpress.org/Widgets_API) de WordPress
* Ajoutons la classe ci-dessous à notre fichier :
```php
class KovalibreSocialNetwork_Widget extends WP_Widget {
     
  // widget constructor
  public function __construct(){
     
  }
    
  public function widget( $args, $instance ) {
    // outputs the content of the widget
  }
    
  public function form( $instance ) {
    // creates the back-end form
  }
    
  // Updating widget replacing old instances with new
  public function update( $new_instance, $old_instance ) {
    // processes widget options on save
  }
   
}
```
* Celle-ci contient quatre méthodes :
    * *__construct()* est la méthode qui déclare le widget à Wordpress
    * *widget()* est la méthode responsable de l'affichage du widget
    * *update()* traite les options du widget lors de l'enregistrement de celui-ci dans l'administration. Cette fonction prend deux paramètres :
        * *$new_instance* : valeur qui ont été envoyé pour être enregistré
        * *$old_instance* : valeurs sauvegardées avant en base de données
    * *form()* est la méthode qui permet de définir le formulaire du widget dans le back-office. Cette méthode prend le paramètre suivant :
        * *$instance* : valeurs sauvegardées avant en base de données 
* Nous allons maintenant remplir les fonctions de notre classe
```php
public function __construct(){
     
    parent::__construct(
        'kovalibresocialnetwork_widget',
        __( 'Kovalibre Social Network Widget', 'kovalibresocialnetwork' ),
        array(
            'classname'   => 'kovalibresocialnetwork_widget',
            'description' => __( 'A widget to display links to social network.', 'kovalibresocialnetwork' )
        )
      );
       
      load_plugin_textdomain( 'kovalibresocialnetwork', false, basename( dirname( __FILE__ ) ) . '/languages' );
       
    }
```
* Dans le code ci-dessus on appelle le constructeur du parent et on lui passe les paramètres suivants : 
    * *Base ID* : un identifiant unique pour le widget. Il doit être en miniscule.
    * *Name* : le nom du widget qui sera affiché sur la page de configuration
    * Et un tableau optionnel qui contient le nom de la classe et une description visible dans l'administration.

## Définition du formulaire d'administration et de son traitement 

* On va maintenant définir le formulaire de notre widget : 
```php
/**
  * Back-end widget form.
  *
  * @see WP_Widget::form()
  *
  * @param array $instance Previously saved values from database.
  */
public function form( $instance ) {    
 
    $title      = esc_attr( $instance['title'] );
    $facebook    = esc_attr( $instance['facebook'] );
    $twitter    = esc_attr( $instance['twitter'] );
    ?>
     
    <p>
        <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
    </p>
    <p>
        <label for="<?php echo $this->get_field_id('facebook'); ?>"><?php _e('Facebook link'); ?></label> 
        <input class="facebook" id="<?php echo $this->get_field_id('facebook'); ?>" name="<?php echo $this->get_field_name('facebook'); ?>" type="text" value="<?php echo $facebook; ?>" />
    </p>
    <p>
        <label for="<?php echo $this->get_field_id('twitter'); ?>"><?php _e('Twitter link'); ?></label> 
        <input class="twitter" id="<?php echo $this->get_field_id('twitter'); ?>" name="<?php echo $this->get_field_name('twitter'); ?>" type="text" value="<?php echo $twitter; ?>" />
    </p>
     
    <?php 
    }
```
* Dans le code ci-dessus : 
    * les trois variables nous permettent de récupérer le contenu existant du widget via l'instance passée en paramètre
    * Ensuite nous définissons trois champs avec un label et un champs input qui utilise deux fonctions : 
        * *get_field_id()* qui construit à partir du nom du champ l'id unique du champs
        * *get_field_name()* qui s'assure à partir du nom du champ que le nom retourné est unique et permet de faire la correspondance avec les valeurs de notre widget.
* Nous allons maintenant traiter les données de ce formulaire pour demander à WordPress de les enregistrer en base de données
```php
/**
  * Sanitize widget form values as they are saved.
  *
  * @see WP_Widget::update()
  *
  * @param array $new_instance Values just sent to be saved.
  * @param array $old_instance Previously saved values from database.
  *
  * @return array Updated safe values to be saved.
  */
public function update( $new_instance, $old_instance ) {        
     
    $instance = $old_instance;
     
    $instance['title'] = strip_tags( $new_instance['title'] );
    $instance['facebook'] = strip_tags( $new_instance['facebook'] );
    $instance['twitter'] = strip_tags( $new_instance['twitter'] );
     
    return $instance;
```
* Dans cette méthode, nous avons deux paramètres comme expliqué un peu plus tôt. Nous assignons les anciennes valeurs à notre variable *$instance*, puis nous venons modifier ces valeurs en récupérant les nouvelles valeurs depuis le paramètre *$new_isntance*. Le fait de renvoyer la variable *$instance* qui contient les bonnes valeurs suffit à ce que WordPress les enregistre en base de données. 
* Nous avons maintenant terminer la partie administration et il ne reste plus qu'à définir l'affichage de notre widget avant de l'utiliser

## Affichage du widget et déclaration du widget à Wordpress

* Nous allons définir l'affichage de notre widget avec la méthode *widget()* dans laquelle nous ajoutons le contenu suivant :
```php
/**  
  * Front-end display of widget.
  *
  * @see WP_Widget::widget()
  *
  * @param array $args     Widget arguments.
  * @param array $instance Saved values from database.
  */
public function widget( $args, $instance ) {    
     
    extract( $args );
     
    $title         = apply_filters( 'widget_title', $instance['title'] );
    $facebook  = $instance['facebook'];
    $twitter   = $instance['twitter'];

     
    echo $before_widget;
     
    if ( $title ) {
        echo $before_title . $title . $after_title;
    }
                         
    printf('<a href="%1$s">Facebook</a><br />',$facebook);
    printf('<a href="%1$s">Twitter</a>',$twitter);

    echo $after_widget;
     
}
```

* On n'oublie pas de déclarer notre widget à WordPress avec un hook action pour que celui-ci soit détecté et disponible dans WordPress
```php
add_action( 'widgets_init', function() {
     register_widget( 'KovalibreSocialNetwork_Widget' );
});
```
* Vous pouvez maintenant aller dans l'administration pour ajouter votre plugin à la zone de votre choix

## A vous de jouer 
* Vous pouvez définir vos propres styles et scripts avec wp_enqueue_style et wp_enqueue_script, mais aussi prévoir l'ajout de logo, l'activation ou non de certains réseaux sociaux, etc.
* Créer un autre plugin avec une déclaration de widget pour le bloc de présentation qui est présent sur la page d'accueil (le bloc référente ...)

## Bonus - Rendre son plugin traductible
* Afin de permettre la traduction de notre plugin, il convient de dire à WordPress que notre plugin est traduisible en ajoutant ces deux lignes dans la déclaration du plugin : 
```php
Domain Path: /languages
Text Domain: kovalibre
```
* Puis de vérifier que vous avez bien utiliser les [fonctions qui permettre la traduction](https://vincentdubroeucq.com/internationaliser-theme-extension-wordpress/) des chaines dans votre code
* Ensuite nous pouvons générer le fichier de traduction de différentes manière :
  * avec wp-cli : https://developer.wordpress.org/cli/commands/i18n/make-pot/ 
  * avec une extension comme WPML https://wpml.org/fr/ 
* Une fois le fichier pot de traduction recupérer, il ne vous reste plus qu'à traduire les chaines dans les languages de votre choix (sans oublier d'ajouter les langues correspondante dans wordpress [avec composer par exemple](https://www.bjornjohansen.com/composer-wordpress-translations))

## Pour aller plus loin 
* Un exemple plus complet de ce widget est disponible ici : https://github.com/ArmandPhilippot/minimalist-social-links-widget/ 