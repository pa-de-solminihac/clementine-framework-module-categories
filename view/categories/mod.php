<div class="categories_mod">
    <form method="post" action="<?php echo __WWW__; ?>/categories/mod">
        <input name="id" type="hidden" value="<?php echo $data['id']; ?>" />
<?php
    if (isset($data['erreurs']['nom'])) {
        foreach ($data['erreurs']['nom'] as $erreur) {
            echo '<span class="error_msg">' . $erreur . '</span><br />';
        }
    }
?>
        <label for="nom">
            Nom
            <input name="nom" type="text" value="<?php echo $data['nom']; ?>" />
        </label>
        <input type="submit" value="Modifier" />
    </form>
</div>
