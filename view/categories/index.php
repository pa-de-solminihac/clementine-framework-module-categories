<?php
$data['categories_list_link_params_href'] = __WWW__ . '/categories/mod';
$data['categories_list_link_params_class'] = 'colorbox';
$data['categories_list_link_category_param'] = 'id';
// ouvre l'arbo jusqu'a notre categorie
$ns = $this->getModel('fonctions');
$id_dest = $ns->ifGet('int', 'id');
$parent_ids = $this->getModel('categories')->getAllParentCategories($ns->ifGet('int', 'id'));
$parent_ids = array_reverse($parent_ids);
$parent_ids[] = $ns->ifGet('int', 'id');
$data['categories_list_parent_ids'] = json_encode($parent_ids); 
$this->getBlock('categories/list_admin', $data);
?>
