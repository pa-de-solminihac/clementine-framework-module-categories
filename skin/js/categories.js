/**
 *  fonction indexOf manquante sous IE
 */
if(!Array.indexOf) {
    Array.prototype.indexOf = function(obj){
        for(var i=0; i<this.length; i++){
            if(this[i]==obj){
                return i;
            }
        }
        return -1;
    }
}

/**
 * categories_list_ouvre_categories : fonction pour deplier les categories en AJAX
 * 
 * @access public
 * @return void
 */
function categories_list_ouvre_categories () {
    categories_list_anim_delay = 200;
    element = jQuery(this).parent();
    /* si l'element est ferme */
    if(element.children('a.categories_list_ferme').length == 1) {
        jQuery.ajax({
            type: "get",
            url: jQuery(this).attr('href'),
            success: function(html) {
                element.html(html); 
                if (element.children('ul').length) {
                    element.children('ul').hide(0, function () {
                        jQuery(this).show(categories_list_anim_delay);
                        jQuery(this).parent().find('a.colorbox').colorbox();
                        var parent_ids;
                        parent_ids = eval(jQuery('.categories_list input[name="categories_list_parent_ids"]:first').val());
                        var category_id;
                        category_id = element.find('input[name="category_id"]:first').val();
                        // console.log(parent_ids);
                        // console.log(category_id);
                        var idx;
                        idx = parent_ids.indexOf(category_id);
                        var new_parent_ids;
                        new_parent_ids = [];
                        if (idx != -1) {
                            parent_ids[idx] = null;
                            jQuery.each(parent_ids, function (key, cid) {
                                if (cid !== null) {
                                    new_parent_ids.push(cid);
                                }
                            });
                        } else {
                            new_parent_ids = parent_ids;
                        }
                        // serialize et stocke le tableau modifie
                        jQuery('.categories_list input[name="categories_list_parent_ids"]:first').val('["' + new_parent_ids.toString().replace(/,/g, '","') + '"]');
                        if (new_parent_ids[0]) {
                            jQuery('#category_list_li_id_' + (new_parent_ids[0]) + ' a.categories_list_ferme').click();
                        }
                    });
                } else {
                    element.find('a.colorbox').colorbox();
                }
            }
        });
        /* si l'element est ouvert */
    } else {
        element.children('ul').hide(categories_list_anim_delay, function () {
            jQuery(this).parent().children('a.categories_list_ouvert').removeClass('categories_list_ouvert').addClass('categories_list_ferme');
            jQuery(this).remove();
        });
    }
    return false;
}
