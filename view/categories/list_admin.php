<?php
// ce bloc charge lui meme si le controleur ne les lui a pas fournies. ainsi on peut l'appeler n'importe ou dans un template
// chargement des modeles
$ns         = $this->getModel('fonctions');
$categories = $this->getModel('categories');

// PARAMETRES
// param $data['categories_list_parent_id'] : id de la categorie mere
// param $data['categories_list'] : liste des categories
// param $data['categories_list_link_params_href'] : parametres href de base pour les liens. l'id categorie lui sera ajoute automatiquement en tant que parametre 'categories_list_link_category_param'
// param $data['categories_list_link_params_class'] : parametre class ajoute au lien
// param $data['categories_list_link_category_param'] : nom du parametre utilise pour transmettre l'id categorie dans l'url (créée à partir du paramètre 'categories_list_link_params_href')
if (isset($data['categories_list_parent_id'])) {
    $categories_list_parent_id = $data['categories_list_parent_id'];
} else {
    $categories_list_parent_id = $ns->ifGet('int', 'categories_list_parent_id'); 
}
if (!isset($data['categories_list']) || !$data['categories_list']) {
    $mere   = $categories->getCategorie($categories_list_parent_id);
    $filles = $categories->getSousCategories($categories_list_parent_id); 
    $data['categories_list'] = array($mere, $filles);
}
if (isset($data['categories_list_link_params_href'])) {
    $href = $data['categories_list_link_params_href'];
} else {
    $href = $ns->ifGet('string', 'href');
}
if (isset($data['categories_list_link_params_class'])) {
    $class = $data['categories_list_link_params_class'];
} else {
    $class = $ns->ifGet('string', 'categories_list_link_params_class');
}
if (isset($data['categories_list_link_category_param'])) {
    $link_category_param = $data['categories_list_link_category_param'];
} else {
    $link_category_param = $ns->ifGet('string', 'categories_list_link_category_param', null, 'categories_list_parent_id');
}
// si la requete n'est pas faite en ajax on verifie la presence de jQuery
$request = $this->getRequest();
if (!$request['AJAX']) {
?>
<script type="text/javascript" charset="<?php echo __HTML_ENCODING__; ?>">
<!--
    // Load jQuery
    if (typeof jQuery == 'undefined') {
        document.write('jQuery is needed here<br />');
    }
// -->
</script>
<?php
}

// AFFICHAGE
// affichage des categories s'il y en a
if (isset($data['categories_list']) && count($data['categories_list'])) {
    $mere = $data['categories_list'][0];
    if (!$request['AJAX'] || (!$mere) || isset($data['categories_list_parent_id'])) {
?>
<div class="categories_list">
<?php
        if (isset($data['categories_list_parent_ids'])) {
?>
    <input type="hidden" name="categories_list_parent_ids" value='<?php echo $data['categories_list_parent_ids']; ?>' />
<?php 
        }
    }
    $filles = array();
    if (isset($data['categories_list'][1])) {
        $filles = $data['categories_list'][1];
    }
?>
    <input type="hidden" name="category_id" value="<?php echo $mere['id']; ?>" /><a href="<?php
    $url = $ns->mod_param(__WWW__ . '/categories/list_admin', 'categories_list_parent_id', $mere['id']); 
    $url = $ns->mod_param($url, 'href', rawurlencode($href)); 
    $url = $ns->mod_param($url, 'categories_list_link_category_param', rawurlencode($link_category_param)); 
    $url = $ns->mod_param($url, 'categories_list_link_params_class', rawurlencode($class)); 
    echo $url; 
    ?>" class="categories_list_dir categories_list_ouvert"><span class="categories_list_state_img">[-]</span></a>
<?php
    if (strlen($href)) {
?>
<a href="<?php 
    $url = $ns->mod_param($href, $link_category_param, $mere['id']); 
    $url = $ns->mod_param($url, 'href', rawurlencode($href)); 
    $url = $ns->mod_param($url, 'categories_list_link_category_param', rawurlencode($link_category_param)); 
    $url = $ns->mod_param($url, 'categories_list_link_params_class', rawurlencode($class)); 
    echo $url; 
    ?>" <?php
    if ($class) {
        echo 'class="' . $class . '"';
    }
    ?>><?php echo $mere['nom']; ?></a>

    <span class="categories_list_tools">
        <!-- lien d'ajout -->
        <a href="<?php echo __WWW__; ?>/categories/add?id_parent=<?php echo $mere['id']; ?>" 
<?php
    if ($class) {
        echo 'class="categories_list_add_img ' . $class . '"';
    }
?>
            ><span class="categories_list_admin_button_img">add</span></a>

        <!-- lien de modification -->
        <a href="<?php echo __WWW__; ?>/categories/mod?id=<?php echo $mere['id']; ?>" 
<?php
    if ($class) {
        echo 'class="categories_list_mod_img ' . $class . '"';
    }
?>
            ><span class="categories_list_admin_button_img">mod</span></a>

        <!-- lien de suppression -->
        <a href="<?php echo __WWW__; ?>/categories/del?id=<?php echo $mere['id']; ?>" 
<?php
    if ($class) {
        echo 'class="categories_list_del_img ' . $class . '"';
    }
?>
            ><span class="categories_list_admin_button_img">del</span></a>
    </span>

<?php 
    } else {
?>
    <span>
<?php
        echo $mere['nom'];
?>
    </span>

    <span class="categories_list_tools">
        <!-- lien d'ajout -->
        <a href="<?php echo __WWW__; ?>/categories/add?id_parent=<?php echo $mere['id']; ?>" 
<?php
        if ($class) {
            echo 'class="categories_list_add_img ' . $class . '"';
        }
?>
            ><span class="categories_list_admin_button_img">add</span></a>

        <!-- lien de modification -->
        <a href="<?php echo __WWW__; ?>/categories/mod?id=<?php echo $mere['id']; ?>" 
<?php
        if ($class) {
            echo 'class="categories_list_mod_img ' . $class . '"';
        }
?>
            ><span class="categories_list_admin_button_img">mod</span></a>

        <!-- lien de suppression -->
        <a href="<?php echo __WWW__; ?>/categories/del?id=<?php echo $mere['id']; ?>" 
<?php
        if ($class) {
            echo 'class="categories_list_del_img ' . $class . '"';
        }
?>
            ><span class="categories_list_admin_button_img">del</span></a>
    </span>
<?php 
    }
    if (count($filles)) {
?>
<ul>
<?php
        $class_if_last = '';
        $cnt = count($filles);
        for ($i = 0; $i < $cnt; ++$i) {
            $fille = $filles[$i];
            if ($i == $cnt - 1) {
                $class_if_last = ' class="last"';
            }
?>
    <li id="category_list_li_id_<?php echo $fille['id']; ?>" <?php echo $class_if_last; ?>><input type="hidden" name="category_id" value="<?php echo $fille['id']; ?>" /><a href="<?php
            $url = $ns->mod_param(__WWW__ . '/categories/list_admin', 'categories_list_parent_id', $fille['id']); 
            $url = $ns->mod_param($url, 'href', rawurlencode($href)); 
            $url = $ns->mod_param($url, 'categories_list_link_category_param', rawurlencode($link_category_param)); 
            $url = $ns->mod_param($url, 'categories_list_link_params_class', rawurlencode($class)); 
            echo $url; 
            ?>" class="categories_list_dir categories_list_ferme"><span class="categories_list_state_img">[+]</span></a>
<?php
            if (strlen($href)) {
?>
        <a href="<?php 
                $url = $ns->mod_param($href, $link_category_param, $fille['id']); 
                $url = $ns->mod_param($url, 'href', rawurlencode($href)); 
                $url = $ns->mod_param($url, 'categories_list_link_category_param', rawurlencode($link_category_param)); 
                $url = $ns->mod_param($url, 'categories_list_link_params_class', rawurlencode($class)); 
                echo $url; 
                ?>" <?php
                if ($class) {
                    echo 'class="' . $class . '"';
                }
                ?>><?php echo $fille['nom']; ?></a>

                    <span class="categories_list_tools">
                        <!-- lien d'ajout -->
                        <a href="<?php echo __WWW__; ?>/categories/add?id_parent=<?php echo $fille['id']; ?>" 
<?php
                if ($class) {
                    echo 'class="categories_list_add_img ' . $class . '"';
                }
?>
                            ><span class="categories_list_admin_button_img">add</span></a>

                        <!-- lien de modification -->
                        <a href="<?php echo __WWW__; ?>/categories/mod?id=<?php echo $fille['id']; ?>" 
<?php
                if ($class) {
                    echo 'class="categories_list_mod_img ' . $class . '"';
                }
?>
                            ><span class="categories_list_admin_button_img">mod</span></a>

                        <!-- lien de suppression -->
                        <a href="<?php echo __WWW__; ?>/categories/del?id=<?php echo $fille['id']; ?>" 
<?php
                if ($class) {
                    echo 'class="categories_list_del_img ' . $class . '"';
                }
?>
                            ><span class="categories_list_admin_button_img">del</span></a>
                    </span>
    </li>
<?php 
            } else {
?>
    <span>
<?php
                echo $fille['nom'];
?>
    </span>

    <span class="categories_list_tools">
        <!-- lien d'ajout -->
        <a href="<?php echo __WWW__; ?>/categories/add?id_parent=<?php echo $fille['id']; ?>" 
<?php
                if ($class) {
                    echo 'class="categories_list_add_img ' . $class . '"';
                }
?>
            ><span class="categories_list_admin_button_img">add</span></a>

        <!-- lien de modification -->
        <a href="<?php echo __WWW__; ?>/categories/mod?id=<?php echo $fille['id']; ?>" 
<?php
                if ($class) {
                    echo 'class="categories_list_mod_img ' . $class . '"';
                }
?>
            ><span class="categories_list_admin_button_img">mod</span></a>

        <!-- lien de suppression -->
        <a href="<?php echo __WWW__; ?>/categories/del?id=<?php echo $fille['id']; ?>" 
<?php
                if ($class) {
                    echo 'class="categories_list_del_img ' . $class . '"';
                }
?>
            ><span class="categories_list_admin_button_img">del</span></a>
    </span>
<?php 
            }
        }
?>
</ul>
<?php 
    }
    if (!$request['AJAX'] || (!$mere) || isset($data['categories_list_parent_id'])) {
?>
</div>
<?php 
    }
}
// si la requete n'est pas faite en ajax on charge le javascript pour l'ergonomie
if (!$request['AJAX']) {
?>
<script type="text/javascript">
    if (typeof jQuery != 'undefined') {
        jQuery('div.categories_list a.categories_list_dir').live('click', categories_list_ouvre_categories);
        jQuery("a.colorbox").colorbox();
        // lance l'ouverture en cascade des categories contenues dans le tableau parent_ids
        parent_ids = eval(jQuery('.categories_list input[name="categories_list_parent_ids"]:first').val());
        if (parent_ids[0]) {
            jQuery('#category_list_li_id_' + (parent_ids[0]) + ' a.categories_list_ferme').click();
        }
    }
</script>
<?php
}
?>
