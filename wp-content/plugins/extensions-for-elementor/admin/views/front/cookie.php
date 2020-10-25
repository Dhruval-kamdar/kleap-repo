<?php
    if(!empty($cookie) && !empty($cookie['message']) && !empty($cookie['enable'])):

        $message =  html_entity_decode($cookie['message']);
        /* $button_text = $cookie['button_text; */
        $box_color = esc_html($cookie['box_color']);
        $font_color = esc_html($cookie['font_color']);
        $close_btn_color = esc_html($cookie['close_btn_color']);

		$overlay = (!empty($cookie['overlay'])) ? esc_html($cookie['overlay']) : '';
        $vertical_position = esc_html($cookie['vertical_position']);
        $horizontal_position = esc_html($cookie['horizontal_position']);

        $cookie_class = '';
        if(!empty($cookie['overlay'])){
            $cookie_class = 'cookie_position '.$vertical_position.' '.$horizontal_position;
        }

        $style = 'background-color:'.$box_color.';';
        $style .= 'color:'.$font_color.';';
?>

<div id="ee_mb_cookie_msg" class="<?php echo $cookie_class; ?>" style="display:none;">
    <div class="ee-mb-cookie-msg-container" style="<?php echo $style; ?>">

        <?php if ($overlay !== 'yes' || ($horizontal_position !== 'left' && $horizontal_position !== 'right')): ?>
                <span class="ee-mb-cookie-msg-text"><?php _e($message, 'elementor-extensions'); ?></span>
        <?php endif; ?>
        
        <a id="ee_mb_cookie_close_btn" href="#" class="ee-mb-cookie-button">
            <svg class="ee_mb_cookie_close_icon" width="64" version="1.1" xmlns="http://www.w3.org/2000/svg" height="64" viewBox="0 0 64 64" xmlns:xlink="http://www.w3.org/1999/xlink" enable-background="new 0 0 64 64">
                <g>
                <path fill="<?php echo $close_btn_color; ?>" d="M28.941,31.786L0.613,60.114c-0.787,0.787-0.787,2.062,0,2.849c0.393,0.394,0.909,0.59,1.424,0.59   c0.516,0,1.031-0.196,1.424-0.59l28.541-28.541l28.541,28.541c0.394,0.394,0.909,0.59,1.424,0.59c0.515,0,1.031-0.196,1.424-0.59   c0.787-0.787,0.787-2.062,0-2.849L35.064,31.786L63.41,3.438c0.787-0.787,0.787-2.062,0-2.849c-0.787-0.786-2.062-0.786-2.848,0   L32.003,29.15L3.441,0.59c-0.787-0.786-2.061-0.786-2.848,0c-0.787,0.787-0.787,2.062,0,2.849L28.941,31.786z"/>
                </g>
            </svg>
        </a>

        <?php if($overlay == 'yes' && ($horizontal_position == 'left' || $horizontal_position == 'right' )){ ?>
            <span class="ee-mb-cookie-msg-text"><?php _e($message,'elementor-extensions'); ?></span>
        <?php } ?>

    </div>
</div>
<?php 
    endif;
    