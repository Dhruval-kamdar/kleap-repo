
<div class="uap-product-links-wrapper">
    <div class="uap-product-links-search-bar-wrapper">
		<?php if ( $data['categories'] ):?>
            <div class="uap-product-links-category">
                <select class="uap-js-product-links-category">
                    <option value=""><?php _e( 'Choose category', 'uap' );?></option>
                    <?php foreach ( $data['categories'] as $categoryId => $categoryLabel ):?>
                        <option value="<?php echo $categoryId;?>" ><?php echo $categoryLabel;?></option>
                    <?php endforeach;?>
                </select>
            </div>
        <?php endif;?>
        <div class="uap-product-links-search-wrapper">
        <input type="text" placeholder="<?php _e( 'Search Products', 'uap' );?>" id="uap_product_links_search_bar" class="uap-product-links-search-bar" />
        </div>
        <div class="uap-clear"></div>
    </div>
    <div class="uap-product-links-extrasearch-wrapper">
    <div class="uap-product-links-count-wrapper uap-special-label">
    	<span class="uap-product-links-count uap-js-product-links-count">0</span><span><?php _e( ' Results', 'uap' );?></span>
    </div>
    <div class="uap-product-links-order-wrapper">
    			<select class="uap-js-product-links-order-type" >
                    <option value=""><?php _e( 'Default Sorting', 'uap' );?></option>
                    <option value="popularity"><?php _e( 'Sort by popularity', 'uap' );?></option>
                    <option value="date"><?php _e( 'Sort by newness', 'uap' );?></option>
                    <option value="price"><?php _e( 'Sort by price: low to high', 'uap' );?></option>
                    <option value="price-desc"><?php _e( 'Sort by price: high to low', 'uap' );?></option>
                </select>
    </div>
    <div class="uap-clear"></div>
    </div>
    <div class="uap-product-links-items-header">
    	<div class="uap-single-product-image-wrapp">&nbsp;</div>
        <div class="uap-single-product-name"><?php _e( 'Product', 'uap' );?></div>
        <div class="uap-single-product-price"><?php _e( 'Price', 'uap' );?></div>
        <div class="uap-single-product-rewards">
		 <?php if ( $data['showReward'] ):?>
				<?php _e( 'Rewards', 'uap' );?>
        <?php endif; ?>
        </div>
        <div class="uap-single-product-link-wrapper">&nbsp;</div>
        <span class="uap-clear"></span>
    </div>
    <div class="uap-product-links-items-wrapper" data-load_more_label="<?php _e( 'Load More', 'uap' );?>" data-loading_more="<?php echo UAP_URL . 'assets/images/loading.gif';?>">

    </div>
</div>
<?php
