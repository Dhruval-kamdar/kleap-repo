<?php


class DroowInFields extends DroowControl
{

    private $control;



    private function getType($type, $obj = array())
    {

        if (in_array($type, array('accordion'))) return;
        if ($type === 'clone') {
            $this->getClone($obj['value']['clone']);
            return ;
        }

        $op = $obj['value'];

        if ($type === 'text') {
            $this->addText($this->control, $op);
        } else if ($type === 'button_group') {
            $this->addButtonGroup($this->control, $op);
        }else if ($type === 'repeater') {
            $this->addRepeater($this->control, $op);
        }else if ($type === 'image') {
            $this->addImage($this->control, $op);
        } else if ($type === 'group') {
            $this->addGroup($this->control, $op);
        }else if ($type === 'url') {
            $this->addURL($this->control, $op);
        }else if ($type === 'textarea') {
            $this->addTextarea($this->control, $op);
        }else if ($type === 'number') {
            $this->addNumber($this->control, $op);
        }else if ($type === 'select') {
            $this->addButtonGroup($this->control, $op);
        }else if ($type === 'true_false') {
            $this->addTrueFalse($this->control, $op);
        }else if ($type === 'radio') {
            $this->addButtonGroup($this->control, $op);
        }else if ($type === 'post_object') {
            $this->addPostObject($this->control, $op);
        } else if ($type === 'gallery') {
            $this->addGallery($this->control, $op);
        }

//        else {
//            dd($type);
//        }


    }


    private function getClone($clons)
    {

        foreach ($clons as $clone) {
            $f = acf_get_local_field($clone);

            $this->getType(
                $this->getArrayData($f, 'type'),
                array(
                    'key' => $clone,
                    'value' => $f
                )
            );
        }
    }


    public function getData($ob, $control)
    {
        $this->control = $control;

        foreach (acf_get_local_fields($ob['dsn_group_key']) as $key => $value) {
            $type = $value['type'];
            $this->getType($type, array(
                'key' => $key,
                'value' => $value
            ));
        }
    }
}