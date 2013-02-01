<div class="categories_add">
    <form method="post" action="<?php echo __WWW__; ?>/categories/add">
        <input name="id_parent" type="hidden" value="<?php echo $data['id_parent']; ?>" />
<?php
    if (isset($data['erreurs']['nom'])) {
        foreach ($data['erreurs']['nom'] as $erreur) {
            echo '<span class="error_msg">' . $erreur . '</span><br />';
        }
    }
?>
        <label for="nom">
            Nom
            <input name="nom" type="text" value="" />
        </label>
        <input type="submit" value="Ajouter" />
    </form>
</div>
