
<form method="get">
  <table style="display: none;">
    <tbody id="inlineedit">
      <tr id="inline-edit" class="inline-edit-row inline-edit-row-post inline-edit-post quick-edit-row quick-edit-row-post inline-edit-post" style="display: none">
        <td colspan="7" class="colspanchange">
          <fieldset class="inline-edit-col-left">
            <legend class="inline-edit-legend"><?php printf(__('Edit %s Information', 'wu-ptm'), $type); ?></legend>
            <div class="inline-edit-col">

              <label>
                <span class="title"><?php _e('Name'); ?></span>
                <span class="input-text-wrap"><input type="text" name="title" class="ptitle" value=""></span>
              </label>

              <label>
                <span class="title"><?php _e('Author'); ?></span>
                <span class="input-text-wrap"><input type="text" name="author" value=""></span>
              </label>
            
              <label>
                <span class="title"><?php _e('Description'); ?></span>
                <span class="input-text-wrap"><textarea name="description"></textarea></span>
              </label>
              
            </div>
          </fieldset>
        
          <fieldset class="inline-edit-col-center inline-edit-categories">
          
            <legend class="inline-edit-legend"><?php _e('Display Options', 'wu-ptm'); ?></legend>

            <div class="inline-edit-col">

              <label>
                <span class="title"></span>
                <span class="input-text-wrap-"><input name="display_author" type="checkbox" checked="checked"> <?php printf(__('Display %s Author?', 'wu-ptm'), $type); ?></span>
              </label>

              <label>
                <span class="title"></span>
                <span class="input-text-wrap-"><input name="display_version" type="checkbox" checked="checked"> <?php printf(__('Display %s Version?', 'wu-ptm'), $type); ?></span>
              </label>

              <label>
                <span class="title"></span>
                <span class="input-text-wrap-"><input name="display_details" type="checkbox" checked="checked"> <?php printf(__('Display %s Details?', 'wu-ptm'), $type); ?></span>
              </label>

              <label>
                <span class="title"></span>
                <span class="input-text-wrap-"><input name="display_other" type="checkbox" checked="checked"> <?php printf(__('Display Other %s Links?', 'wu-ptm'), $type); ?></span>
              </label>

            </div>

          </fieldset>

          <fieldset class="inline-edit-col-right">

            <div class="inline-edit-col">

              <?php if ( count( $flat_taxonomies ) ) : ?>

              <?php foreach ( $flat_taxonomies as $taxonomy ) : ?>
                <?php if ( current_user_can( $taxonomy->cap->assign_terms ) ) :
                  
                  $taxonomy_name = esc_attr( $taxonomy->name );

                  if (stristr($taxonomy_name, $type_slug) === false || $type === '') continue;

                  ?>
                  <label class="inline-edit-tags">
                    <span class="title"><?php echo esc_html( $taxonomy->labels->name ) ?></span>
                    <textarea data-wp-taxonomy="<?php echo $taxonomy_name; ?>" cols="22" rows="1" name="<?php echo $taxonomy_name; ?>" class="tax_input_<?php echo $taxonomy_name; ?>"></textarea>
                  </label>
                <?php endif; ?>

              <?php endforeach; //$flat_taxonomies as $taxonomy ?>

              <?php endif; // count( $flat_taxonomies ) && !$bulk  ?>

              <label class="inline-edit-thumbnail">
                <span class="title"><?php _e('Thumbnail', 'wu-ptm'); ?></span>

                <?php 

                $field_slug = 'extension_thumbnail';
                
                // We need to get the media scripts
                wp_enqueue_media();
                wp_enqueue_script('media');

                ?>


                  <?php $image_url = ''; // var_dump($image_url);

                    if ( true ) {
                      $image = '<img class="%s" src="%s" alt="%s" style="width:%s; height:auto">';
                      printf(
                        $image,
                        $field_slug.'-preview',
                        $image_url,
                        __('Thumbnail'),
                        '150px'
                      );
                      
                    } ?>

                  <br>

                  <a href="#" class="button wu-field-button-upload" data-target="<?php echo $field_slug; ?>">
                    <?php _e('Upload Image', 'wu-ptm'); ?>
                  </a>

                  <a href="#" class="button wu-field-button-upload-remove" data-target="<?php echo $field_slug; ?>">
                    <?php _e('Remove Image', 'wu-ptm'); ?>
                  </a>                  

                  <input type="hidden" name="<?php echo $field_slug; ?>" class="<?php echo $field_slug; ?>" value="">

                </label>
            
            </div>


          </fieldset>

          <p class="submit inline-edit-save">
            <button type="button" class="button cancel alignleft"><?php _e('Cancel', 'wu-ptm'); ?></button>
            
            <?php wp_nonce_field('wu_extension_inline_edit'); ?>

            <button type="button" class="button button-primary save alignright"><?php _e('Save Changes', 'wu-ptm'); ?></button>
            <span class="spinner"></span>
            
            <input type="hidden" name="post_view" value="list">
            <input type="hidden" name="extension_type" value="<?php echo $type_slug; ?>">

            <span class="error" style="display:none"></span>
            <br class="clear">
          </p>
        </td>
      </tr>
    </tbody>
  </table>
</form>
