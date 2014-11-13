<?php

/**
 * Description of analysis_xml
 *
 * @author Pinghai
 */
class Analysis_Xml_PH extends Formatting_Data_PH {

    private $xml_obj;

    public function __construct($fpath) {
        if (file_exists($fpath))
            $rawdata = implode('', file($fpath));
        if ($rawdata)
            $this->xml_obj = new SimpleXMLElement($rawdata);
    }

    public function get_root_info() {
        $tmp_a = array();

        $tmp = (string) $this->xml_obj->id;
        $tmp_a['id'] = trim($tmp);
        $tmp = (string) $this->xml_obj->page_title;
        $tmp_a['page_title'] = trim($tmp);
        $tmp = (string) $this->xml_obj->menu_title;
        $tmp_a['menu_title'] = trim($tmp);
        $tmp = (string) $this->xml_obj->content_title;
        $tmp_a['content_title'] = trim($tmp);
        $tmp = (string) $this->xml_obj->help;
        $tmp_a['help'] = trim($tmp);
        $tmp = (string) $this->xml_obj->help_sidebar;
        $tmp_a['help_sidebar'] = trim($tmp);

        return $this->convert_array_to_object($tmp_a);
    }

    public function get_tab_info($id) {
        $tmp_a = array();
        foreach ($this->xml_obj->tab as $val_objs) {
            $tmp = (string) $val_objs->id;
            if ($id == $tmp) {
                $tmp = (string) $val_objs->id;
                $tmp_a['id'] = trim($tmp);
                $tmp = (string) $val_objs->label;
                $tmp_a['label'] = trim($tmp);
                $tmp = (string) $val_objs->desc;
                $tmp_a['desc'] = trim($tmp);
            }
            if (!$id) {
                $tmp = (string) $val_objs->id;
                $tmp_a['id'] = trim($tmp);
                $tmp = (string) $val_objs->label;
                $tmp_a['label'] = trim($tmp);
                $tmp = (string) $val_objs->desc;
                $tmp_a['desc'] = trim($tmp);
                break;
            }
        }

        return $this->convert_array_to_object($tmp_a);
    }

    public function get_tabs() {
        $tmp_a = array();
        $idx = 0;
        foreach ($this->xml_obj->tab as $val_objs) {
            $tmp = (string) $val_objs->id;
            $tmp_a[$idx]['id'] = trim($tmp);

            $tmp = (string) $val_objs->label;
            $tmp_a[$idx]['label'] = trim($tmp);

            $tmp = (string) $val_objs->desc;
            $tmp_a[$idx]['desc'] = trim($tmp);

            ++$idx;
        }
        return $this->convert_array_to_object($tmp_a);
    }

    public function get_items($id) {
        $tmp_a = array();
        $idx = 0;
        foreach ($this->xml_obj->tab as $val_objs) {
            $tmp = (string) $val_objs->id;
            if ($id == $tmp) {
                foreach ($val_objs->item as $val_a) {
                    foreach ($val_a as $key => $value) {
                        $tmp = (string) $value;
                        $tmp_a[$idx][$key] = trim($tmp);
                    }
                    ++$idx;
                }
            }
            if (!$id) {
                foreach ($val_objs->item as $val_a) {
                    foreach ($val_a as $key => $value) {
                        $tmp = (string) $value;
                        $tmp_a[$idx][$key] = trim($tmp);
                    }
                    ++$idx;
                }
                break;
            }
        }
        return $this->convert_array_to_object($tmp_a);
    }

}

?>
