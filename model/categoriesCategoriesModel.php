<?php
/**
 * categoriesCategoriesModel : gestion de categories
 *
 * @package 
 * @version $id$
 * @copyright 
 * @author Pierre-Alexis <pa@quai13.com> 
 * @license 
 */
class categoriesCategoriesModel extends categoriesCategoriesModel_Parent
{

    public $table_categories = 'clementine_categories';
    public $table_traduction_contenu = 'clementine_traduction_contenu';
    public $champs_a_traduire = array("nom");

    /**
     * getCategorie : récupère les infos de la categorie $id
     * 
     * @param mixed $id 
     * @access public
     * @return void
     */
    public function getCategorie ($id) 
    {
        $id = (int) $id; 
        $db = $this->getModel('db');
        $sql = 'SELECT * FROM ' . $this->table_categories . ' WHERE id = \'' . $id . '\' ORDER BY rang_tri LIMIT 1'; 
        $res = $db->fetch_assoc($db->query($sql)); 
        $request = $this->getRequest();
        if ($request['LANG'] != __DEFAULT_LANG__ && $res) {
            foreach ($this->champs_a_traduire as $champ) {
                $traduit = $this->getModel('traduction')->traduire_contenu($this->table_categories, $champ, $res['id']);
                if ($traduit != 'NULL') {
                    $res[$champ] = $traduit;
                    $res['notrad'] = 1;
                } else {
                    $res['notrad'] = 0;
                }
            }
        }
        return $res; 
    }

    /**
     * getCategorieByName : récupère les infos de la categorie par son nom $name
     * 
     * @param mixed $name 
     * @access public
     * @return void
     */
    public function getCategorieByName ($name) 
    {
        $name = trim((string) $name); 
        $db = $this->getModel('db');
        $sql = 'SELECT * FROM ' . $this->table_categories . ' WHERE nom = \'' . $db->escape_string($name) . '\' ORDER BY rang_tri LIMIT 1'; 
        $res = $db->fetch_assoc($db->query($sql)); 
        $request = $this->getRequest();
        if ($request['LANG'] != __DEFAULT_LANG__ && $res) {
            foreach ($this->champs_a_traduire as $champ) {
                $traduit = $this->getModel('traduction')->traduire_contenu($this->table_categories, $champ, $res['id']);
                if ($traduit != 'NULL') {
                    $res[$champ] = $traduit;
                    $res['notrad'] = 1;
                } else {
                    $res['notrad'] = 0;
                }
            }
        }
        return $res; 
    }

    /**
     * getSousCategories : récupère les catégories filles directes d'un id_parent
     * 
     * @param mixed $id_parent 
     * @access public
     * @return void
     */
    public function getSousCategories ($id_parent)
    {
        $id_parent = (int) $id_parent;
        $db = $this->getModel('db');
        $sql  = 'SELECT * FROM ' . $this->table_categories . ' ';
        $sql .= 'WHERE id_parent = \'' . $id_parent . '\' ORDER BY rang_tri';
        $stmt = $db->query($sql);
        $filles = array();
        for (; $res = $db->fetch_assoc($stmt); true) {
            $request = $this->getRequest();
            if ($request['LANG'] != __DEFAULT_LANG__) {
                foreach ($this->champs_a_traduire as $champ) {
                    $traduit = $this->getModel('traduction')->traduire_contenu($this->table_categories, $champ, $res['id']);
                    if ($traduit != 'NULL') {
                        $res[$champ] = $traduit;
                        $res['notrad'] = 1;
                    } else {
                        $res['notrad'] = 0;
                    }
                }
            }
            $filles[] = $res;
        }
        return $filles;
    }

    /**
     * getSousCategoriesIds : récupère les id des catégories filles directes d'un id_parent
     * 
     * @param int $id_parent 
     * @param mixed $onlycnt 
     * @access public
     * @return void
     */
    public function getSousCategoriesIds ($id_parent, $onlycnt = false) 
    {
        $id_parent = (int) $id_parent; 
        $db = $this->getModel('db');
        if (!$onlycnt) {
            $sql  = 'SELECT * FROM ' . $this->table_categories . ' '; 
        } else {
            $sql  = 'SELECT COUNT(*) AS nb FROM ' . $this->table_categories . ' '; 
        }
        $sql .= 'WHERE id_parent = \'' . $id_parent . '\' ORDER BY rang_tri'; 
        return $db->query($sql); 
    }

    /**
     * addCategorie : enregistre une nouvelle categorie, avec le dernier rang_tri possible, et renvoie son id
     * 
     * @param mixed $nom 
     * @param int $id_parent 
     * @access public
     * @return void
     */
    public function addCategorie ($nom, $id_parent = 0) 
    {
        // insertion
        $db = $this->getModel('db');
        // $sql  = "START TRANSACTION"; 
        $sql  = "LOCK TABLES " . $this->table_categories . " WRITE "; 
        $stmt = $db->query($sql); 
        $sql  = "SELECT MAX(rang_tri) + 1 FROM " . $this->table_categories . " WHERE id_parent = '$id_parent'"; 
        $stmt = $db->query($sql); 
        $max_rang_tri_array = $db->fetch_array($stmt); 
        $max_rang_tri = $max_rang_tri_array[0]; 
        $sql  = "INSERT INTO " . $this->table_categories . " (`id`, `id_parent`, `nom`, `date_creation`, `date_modification`, `active`, `rang_tri`) 
            VALUES ('', '$id_parent', '$nom', NULL, NULL, '1', '$max_rang_tri')"; 
        $stmt = $db->query($sql); 
        $sql  = "SELECT LAST_INSERT_ID() FROM " . $this->table_categories . ""; 
        $stmt = $db->query($sql); 
        $last_insert_id_array = $db->fetch_array($stmt); 
        $last_insert_id = $last_insert_id_array[0]; 
        // $sql  = "COMMIT"; 
        $sql  = "UNLOCK TABLES "; 
        $stmt = $db->query($sql); 
        return $last_insert_id; 
    }

    /**
     * modCategorie : modifie une categorie
     * 
     * @param mixed $nom 
     * @param mixed $id 
     * @access public
     * @return void
     */
    public function modCategorie ($nom, $id) 
    {
        // modification 
        $request = $this->getRequest();
        $db = $this->getModel('db');
        if ($request['LANG'] == __DEFAULT_LANG__) {
            $sql  = "UPDATE " . $this->table_categories . " SET `nom` = '$nom' WHERE `id` =  '$id' LIMIT 1 "; 
        } else {
            $sql  = "INSERT INTO `" . $this->table_traduction_contenu . "` (orig_table, orig_field, orig_id, lang, texte) 
                VALUES ('" . $this->table_categories . "', 'nom', '" . $id . "', '" . $request['LANG'] . "', '" . $nom . "') 
                ON DUPLICATE KEY UPDATE texte = '" . $nom . "' ";
        }
        $stmt = $db->query($sql); 
        return $stmt; 
    }

    /**
     * delCategorie : supprime une categorie si elle n'a pas d'enfants, supprime recursivement la categorie et ses enfants si $forcer
     * 
     * @param mixed $id 
     * @param string $nom 
     * @param int $forcer 
     * @access public
     * @return void
     */
    public function delCategorie ($id, $nom = '', $forcer = 0) 
    {
        // categorie par defaut
        $id_categorie_defaut = '0'; 
        $db = $this->getModel('db');
        if ($forcer) {
            // verifie si il y a des categories filles
            $stmt = $this->getSousCategoriesIds($id); 
            if ($db->num_rows($stmt)) {
                // si oui : relance delCategorie sur chacune des categories filles
                for (; $res = $db->fetch_array($stmt); true) {
                    $this->delCategorie($res['id'], $res['nom'], '1'); 
                }
            } else {
                // si non : relance delCategorie mais sans le parametre forcer
                $this->delCategorie($id, $nom); 
            }
        }
        // suppression
        $sql      = "DELETE FROM " . $this->table_categories . " WHERE `id` = '$id' "; 
        if (strlen($nom)) {
            $sql .= "AND `nom` = '$nom' "; 
        }
        $sql     .= "LIMIT 1"; 
        $stmt = $db->query($sql); 
        $cond = 1; 
        if ($stmt) {
            $request = $this->getRequest();
            if ($request['LANG'] != __DEFAULT_LANG__) {
                $sql = "DELETE FROM `" . $this->table_traduction_contenu . "` WHERE orig_table = '" . $this->table_categories . "' AND orig_id = '" . $id . "' AND lang = '" . $request['LANG'] . "' "; 
                $stmt = $db->query($sql); 
            }
        }
        return $cond; 
    }

    /**
     * getParentCategorie : récupère la catégorie parente d'un id. 
     * 
     * @param mixed $id 
     * @access public
     * @return void
     */
    public function getParentCategorie ($id) 
    {
        $id = (int) $id; 
        $db = $this->getModel('db');
        $sql = 'SELECT * FROM ' . $this->table_categories . ' WHERE id = (SELECT id_parent FROM ' . $this->table_categories . ' WHERE id = \'' . $id . '\' LIMIT 1)'; 
        return $db->fetch_array($db->query($sql)); 
    }

    /**
     * getAllParentCategories : récupère la liste des id des catégories parente d'un id. 
     * 
     * @param mixed $id 
     * @access public
     * @return void
     */
    public function getAllParentCategories ($id) 
    {
        $id = (int) $id;
        $arbo = array();
        // limite a 20 niveaux de profondeur pour eviter les boucles infinies
        $id_cat = $id;
        for ($i = 0; $i < 20; ++$i) {
            $parent_id_cat = $this->getParentCategorie($id_cat);
            if (isset($parent_id_cat['id']) && $parent_id_cat['id']) {
                $arbo[] = $parent_id_cat['id'];
                $id_cat = $parent_id_cat['id'];
            } else {
                break;
            }
        }
        return $arbo;
    }

}
