<?php

/**
 * Description of create_ctrls
 *
 * @author Pinghai
 */
class Create_Ctrls_PH {

    final function input_text($name, $default_val, $attrs = array()) {
        $attr_str = $this->get_attr_str($attrs);

        $ctrl_str = '<input type="text" ';
        $ctrl_str .= ' name="' . $name . '" ';
        $ctrl_str .= ' value="' . $default_val . '" ';
        $ctrl_str .= $attr_str;
        $ctrl_str .= '/>';

        return $ctrl_str;
    }

    final function input_radio($name, $options, $default_val, $attrs = array()) {
        $attr_str = $this->get_attr_str($attrs);

        $ctrl_str = '';
        foreach ($options as $key => $value) {
            $selected = '';
            if (trim($value) == trim($default_val))
                $selected = ' checked="checked" ';
            $ctrl_str .= '<input type="radio" ';
            $ctrl_str .= ' name="' . $name . '" ';
            $ctrl_str .= ' value="' . $value . '" ';
            $ctrl_str .= $selected;
            $ctrl_str .= $attr_str;
            $ctrl_str .= '>';
            $ctrl_str .= $key;
        }

        return $ctrl_str;
    }

    final function input_check($name, $options, $default_val, $attrs = array()) {
        $attr_str = $this->get_attr_str($attrs);

        $ctrl_str = '';
        foreach ($options as $key => $value) {
            $selected = '';
            if (is_array($default_val)) {
                foreach ($default_val as $default) {
                    if (trim($default) == trim($value))
                        $selected = ' checked="checked" ';
                }
            } else {
                if (trim($default_val) == trim($value))
                    $selected = ' checked="checked" ';
            }
            $ctrl_str .= '<input type="checkbox" ';
            $ctrl_str .= ' name="' . $name . '[]" ';
            $ctrl_str .= ' value="' . $value . '" ';
            $ctrl_str .= $selected;
            $ctrl_str .= $attr_str;
            $ctrl_str .= '>';
            $ctrl_str .= $key;
        }

        return $ctrl_str;
    }

    final function select($name, $options, $default_val, $attrs = array()) {
        $attr_str = $this->get_attr_str($attrs);

        $ctrl_str = '<select ';
        $ctrl_str .= ' name="' . $name . '" ';
        $ctrl_str .= $attr_str;
        $ctrl_str .= '>';
        foreach ($options as $key => $value) {
            $selected = '';
            if (!is_array($default_val)) {
                if ($value == $default_val)
                    $selected = ' selected ';
            }
            $ctrl_str .= '<option value="' . $value . '" ' . $selected . '>' . $key . '</option>';
        }
        $ctrl_str .= '</select>';

        return $ctrl_str;
    }

    final function textarea($name, $value, $attrs = array()) {
        $attr_str = $this->get_attr_str($attrs);

        $ctrl_str = '<textarea name = "' . $name . '" ';
        $ctrl_str .= $attr_str;
        $ctrl_str .= '>';
        $ctrl_str .= $value;
        $ctrl_str .= '</textarea>';

        return $ctrl_str;
    }

    final function img($src, $attrs = array()) {
        $attr_str = $this->get_attr_str($attrs);

        $ctrl_str = '<img ';
        $ctrl_str .= ' src="' . $src . '" ';
        $ctrl_str .= $attr_str;
        $ctrl_str .= '/>';

        return $ctrl_str;
    }

    final function get_attr_str($attrs) {
        foreach ($attrs as $key => $value)
            $attr_str = $key . '="' . $value . '" ';
        return $attr_str;
    }

}

?>
