<div class="categories_del">
    <form method="post" action="<?php echo __WWW__; ?>/categories/del">
        <input name="id" type="hidden" value="<?php echo $data['id']; ?>" />
        Supprimer cette catégorie et <strong>toutes</strong> ses sous-catégories ?
        <label for="nom">
            <input name="nom" type="hidden" value="<?php echo $data['nom']; ?>" />
        </label>
        <input type="submit" value="Supprimer" />
    </form>
</div>
