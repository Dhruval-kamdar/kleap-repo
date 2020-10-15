<?php


class DroowShortCode
{
    public $attr = array();

    public function __construct($attr)
    {
        $this->attr = $attr;
    }


    private function optionArray($id, $default = false)
    {
        return droow_acf_option_array($this->attr, $id, $default);
    }

    public function TitleCover($default = false)
    {
        return $this->optionArray('title_cover', $default);
    }


    public function LinkId($default = false)
    {
        return $this->optionArray('link_post', $default);
    }


    public function number_columns($default = 3)
    {
        return 12 / $this->optionArray('number_columns', $default);
    }


    public function getItems($content)
    {
        if (is_array($content))
            return $content;
        else if ($content && is_string($content))
            return json_decode($content);
        return array();
    }


    public function getBlocTitle()
    {
        if ($this->TitleCover() || $this->TitleCover()):
            echo '<div class="one-title">';
            if ($title_cover = $this->TitleCover())
                printf('<div class="title-sub-container"><p class="title-sub" %s>%s</p></div>', $this->titleAnimate(), esc_html($title_cover));

            if ($title = $this->Title())
                printf('<h2 class="title-main" %s>%s</h2>', $this->titleAnimate(), esc_html($title));
            echo '</div>';
        endif;
    }

    public function Title($default = false)
    {
        return $this->optionArray('title', $default);
    }

    public function titleAnimate($default = false)
    {
        return $this->optionArray('title_animate', $default);
    }

    public function Description($default = false)
    {
        return $this->optionArray('description', $default);
    }


    public function DescriptionAnimate($default = false)
    {
        return $this->optionArray('description_animate', $default);
    }


    public function TitleLink($default = false)
    {
        return $this->optionArray('title_link', $default);
    }

    public function UrlLink($default = false)
    {
        return $this->optionArray('url_link', $default);
    }

    public function styleVertical($default = false)
    {
        return $this->optionArray('style_vertical', $default);
    }


    public function Image($default = false)
    {
        $img = droow_acf_option_array($this->optionArray('image', $default), 'id', $this->optionArray('image', $default));
        if (gettype($img) == 'array')
            $img = droow_acf_option_array($img, 'id', $img);
        return $img;
    }

    public function gallery($default = array())
    {
        $val = $this->optionArray('gallery', $default);
        if (is_string($val) && $val)
            return explode(',', $val);

        return $val;
    }


    public function titleCaption($default = false)
    {
        return $this->optionArray('title_caption', $default);
    }

    public function descriptionCaption($default = false)
    {
        return $this->optionArray('description_caption', $default);
    }

    public function showCaption($default = false)
    {
        return $this->optionArray('show_caption', $default);
    }


    public function descriptionAnimateCaption($default = false)
    {
        return $this->optionArray('description_animate_caption', $default);
    }

    public function overlay($default = 0)
    {
        return $this->optionArray('overlay', $default);
    }

    public function parallaxType($default = false)
    {
        return $this->optionArray('parallax_type', $default);
    }


    public function triggerhook($default = false)
    {
        return $this->optionArray('triggerhook', $default);
    }

    public function duration($default = false)
    {
        return $this->optionArray('duration', $default);
    }


    public function changeColor($default = false)
    {
        return $this->optionArray('Change-color', $default);
    }

    public function durationColor($default = false)
    {
        return $this->optionArray('duration_color', $default);
    }

    public function layout($default = 'container-fluid')
    {
        return $this->optionArray('layout', $default);
    }

    public function titleAniamteCaption($default = false)
    {
        return $this->optionArray('title_animate_caption', $default);
    }

    public function Caption($default = false)
    {
        return $this->optionArray('caption', $default);
    }

    public function scale($default = false)
    {
        return $this->optionArray('scale', $default);
    }

    public function transY($default = false)
    {
        return $this->optionArray('y', $default);
    }


    public function style($default = false)
    {
        return $this->optionArray('style', $default);
    }

    public function sizeImage($default = 'full')
    {
        if (gettype($this->optionArray('size-image', $default)) === 'array')
            return $default;
        return $this->optionArray('size-image', $default);
    }

    public function className($default = false)
    {
        return $this->optionArray('className', $default);
    }


}
