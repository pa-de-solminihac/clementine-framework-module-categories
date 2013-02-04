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
    }
    $filles = array();
    if (isset($data['categories_list'][1])) {
        $filles = $data['categories_list'][1];
    }
?>
    <a href="<?php
    $url = $ns->mod_param(__WWW__ . '/categories/list', 'categories_list_parent_id', $mere['id']); 
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
<?php 
    } else {
?>
    <span>
<?php
        echo $mere['nom'];
?>
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
    <li<?php echo $class_if_last; ?>><a href="<?php
            $url = $ns->mod_param(__WWW__ . '/categories/list', 'categories_list_parent_id', $fille['id']); 
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
                ?>><?php echo $fille['nom']; ?></a></li>
<?php 
            } else {
?>
    <span>
<?php
                echo $fille['nom'];
?>
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
    }
</script>
<?php
}
?>
