<?php
/**
 * categoriesCategoriesController : gestion de categories
 *
 * @package 
 * @version $id$
 * @copyright 
 * @author Pierre-Alexis <pa@quai13.com> 
 * @license 
 */
class categoriesCategoriesController extends categoriesCategoriesController_Parent
{

    /**
     * addAction : preparer les variables pour ajouter une nouvelle categorie
     * 
     * @access public
     * @return void
     */
    function addAction($request, $params = null) 
    {
        $this->getModel('users')->needPrivilege('manage_categories');
        if ($_POST) {
            // recupere les parametres 
            $ns = $this->getModel('fonctions');
            $id_parent   = $ns->ifPost("int", "id_parent"); 
            $nom         = $ns->ifPost("string", "nom"); 
            // verification des donnees requises
            $erreurs = array(); 
            if (!strlen($nom)) {
                $erreurs['nom'][] = 'Le champ nom est obligatoire'; 
            }
            if (count($erreurs)) {
                $this->data['erreurs'] = $erreurs; 
            } else {
                // enregistre la nouvelle categorie et redirection pour eviter les problemes de rafraichissement
                if ($id = $this->getModel('categories')->addCategorie($nom, $id_parent)) {
                    $ns->redirect(__WWW__ . '/categories/add_ok?id=' . $id);
                } else {
                    $this->data['erreurs']['sauvegarde'] = 'Erreur lors de la sauvegarde';
                }
            }
        } else {
            $ns = $this->getModel('fonctions');
            $this->data['id_parent'] = $ns->ifGet("int", "id_parent");
        }
    }

    /**
     * add_okAction : page de confirmation de l'ajout d'une categorie
     * 
     * @access public
     * @return void
     */
    function add_okAction($request, $params = null) 
    {
        $this->getModel('users')->needPrivilege('manage_categories');
        $ns = $this->getModel('fonctions');
        $this->data['id'] = $ns->ifGet("int", "id"); 
    }

    /**
     * modAction :  chargement et modification d'une categorie
     * 
     * @access public
     * @return void
     */
    function modAction($request, $params = null) 
    {
        $this->getModel('users')->needPrivilege('manage_categories');
        if ($_POST) {
            // recupere les parametres 
            $ns = $this->getModel('fonctions');
            $id  = $ns->ifPost("int", "id"); 
            $nom = $ns->ifPost("string", "nom"); 

            // verification des donnees requises
            $erreurs = array(); 
            if (!strlen($nom)) {
                $erreurs['nom'][] = 'Le champ nom est obligatoire'; 
            }
            if (count($erreurs)) {
                $this->data['erreurs'] = $erreurs; 
            } else {
                // enregistre la nouvelle categorie et redirection pour eviter les problemes de rafraichissement
                if ($this->getModel('categories')->modCategorie($nom, $id)) {
                    $ns->redirect(__WWW__ . '/categories/mod_ok?id=' . $id);
                } else {
                    $this->data['erreurs']['sauvegarde'] = 'Erreur lors de la sauvegarde';
                }
            }
        } else {
            $ns = $this->getModel('fonctions');
            $id = $ns->ifGet("int", "id");
            $this->data = $this->getModel('categories')->getCategorie($id); 
        }
    }

    /**
     * mod_okAction : page de confirmation de modification d'une categorie
     * 
     * @access public
     * @return void
     */
    function mod_okAction($request, $params = null) 
    {
        $this->getModel('users')->needPrivilege('manage_categories');
        $ns = $this->getModel('fonctions');
        $this->data['id'] = $ns->ifGet("int", "id"); 
    }

    /**
     * delAction : chargement et suppression d'une categorie
     * 
     * @access public
     * @return void
     */
    function delAction($request, $params = null) 
    {
        $this->getModel('users')->needPrivilege('manage_categories');
        if ($_POST) {
            // recupere les parametres 
            $ns = $this->getModel('fonctions');
            $id  = $ns->ifPost("int", "id"); 
            $nom = $ns->ifPost("string", "nom"); 

            // verification des donnees requises
            $erreurs = array(); 
            if (count($erreurs)) {
                $this->data['erreurs'] = $erreurs; 
            } else {
                // supprime la categorie et redirection pour eviter les problemes de rafraichissement
                $parent_cat = $this->getModel('categories')->getParentCategorie($id);
                $parent_id = 0;
                if ($parent_cat) {
                    $parent_id = $parent_cat['id'];
                }
                if ($this->getModel('categories')->delCategorie($id, $nom, '1')) {
                    $ns->redirect(__WWW__ . '/categories/del_ok?id=' . $parent_id);
                } else {
                    $this->data['erreurs']['sauvegarde'] = 'Erreur lors de la suppression';
                }
            }
        } else {
            $ns = $this->getModel('fonctions');
            $id = $ns->ifGet("int", "id");
            $this->data = $this->getModel('categories')->getCategorie($id); 
        }
    }

    /**
     * del_okAction : page de confirmation de suppression d'une categorie
     * 
     * @access public
     * @return void
     */
    function del_okAction($request, $params = null) 
    {
        $this->getModel('users')->needPrivilege('manage_categories');
        $ns = $this->getModel('fonctions');
        $this->data['id'] = $ns->ifGet("int", "id"); 
    }

    /**
     * Function : indexAction() 
     * 
     */
    function indexAction($request, $params = null) 
    {
        $this->getModel('users')->needPrivilege('manage_categories');
        if (Clementine::$config['module_jstools']['use_google_cdn']) {
            $this->getModel('cssjs')->register_js('jquery', array('src' => 'https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js'));
        } else {
            $this->getModel('cssjs')->register_js('jquery', array('src' => __WWW_ROOT_JSTOOLS__ . '/skin/jquery/jquery.min.js'));
        }
        $this->getModel('cssjs')->register_css('jquery.colorbox', array('src' => __WWW_ROOT_JSTOOLS__ . '/skin/colorbox/colorbox.css', 'media' => 'screen'));
        $this->getModel('cssjs')->register_js('jquery.colorbox', array('src' => __WWW_ROOT_JSTOOLS__ . '/skin/colorbox/jquery.colorbox-min.js'));
        $this->getModel('cssjs')->register_js('clementine_categories_js', array('src' => __WWW_ROOT_CATEGORIES__ . '/skin/js/categories.js'));
        $this->getModel('cssjs')->register_css('clementine_categories_css', array('src' => __WWW_ROOT_CATEGORIES__ . '/skin/css/categories.css'));
    }

    /**
     * listAction : affiche la liste des categories filles directes de la categorie passee en parametre
     * 
     * @access public
     * @return void
     */
    function listAction($request, $params = null) 
    {
        if (Clementine::$config['module_jstools']['use_google_cdn']) {
            $this->getModel('cssjs')->register_js('jquery', array('src' => 'https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js'));
        } else {
            $this->getModel('cssjs')->register_js('jquery', array('src' => __WWW_ROOT_JSTOOLS__ . '/skin/jquery/jquery.min.js'));
        }
        $this->getModel('cssjs')->register_css('clementine_categories_css', array('src' => __WWW_ROOT_CATEGORIES__ . '/skin/css/categories.css'));
    }

    /**
     * list_adminAction : affiche la liste des categories filles directes de la categorie passee en parametre ainsi que les liens d'administration
     * 
     * @access public
     * @return void
     */
    function list_adminAction($request, $params = null) 
    {
        $this->getModel('users')->needPrivilege('manage_categories');
        if (Clementine::$config['module_jstools']['use_google_cdn']) {
            $this->getModel('cssjs')->register_js('jquery', array('src' => 'https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js'));
        } else {
            $this->getModel('cssjs')->register_js('jquery', array('src' => __WWW_ROOT_JSTOOLS__ . '/skin/jquery/jquery.min.js'));
        }
        $this->getModel('cssjs')->register_css('jquery.colorbox', array('src' => __WWW_ROOT_JSTOOLS__ . '/skin/colorbox/colorbox.css', 'media' => 'screen'));
        $this->getModel('cssjs')->register_js('jquery.colorbox', array('src' => __WWW_ROOT_JSTOOLS__ . '/skin/colorbox/jquery.colorbox-min.js'));
        $this->getModel('cssjs')->register_css('clementine_categories_css', array('src' => __WWW_ROOT_CATEGORIES__ . '/skin/css/categories.css'));
    }

}
?>
