<?php

/**
 * base functions for database.
 * 
 * @author Pinghai
 * 
 * Description: 
 * 
 * Version: 1.0.0
 * 
 */
class DB_Base_PH extends Formatting_Data_PH {

    private $tb_nm;

    function __construct($tablename = "") {
        $this->tb_nm = $tablename;
    }

    function insert($info) {
        $last_id = 0;
        if ($this->tb_nm) {
            if (count($info)) {
                $query = "INSERT INTO " . $this->tb_nm . " SET ";
                $idx = 0;
                foreach ($info as $key => $value) {
                    if ($idx)
                        $query .= ',';
                    $query .= "`" . trim($key) . "` = '" . trim(mysql_escape_string($value)) . "'";
                    ++$idx;
                }
                mysql_query($query);
                $last_id = mysql_insert_id();
            }
        }
        return $last_id;
    }

    function update($id, $info) {
        if ($this->tb_nm) {
            $tmp = '';
            $idx = 0;

            foreach ($info as $key => $value) {
                $dot = '';
                if ($idx != 0)
                    $dot = ',';
                $tmp .= $dot . "`" . $key . "`= '" . trim(mysql_escape_string($value)) . "'";
                ++$idx;
            }

            if ($id) {
                $query = "UPDATE {$this->tb_nm} SET " . $tmp . " WHERE `id` = " . $id;
                mysql_query($query);
            }
        }
    }

    function get_by_id($id) {
        if ($this->tb_nm) {
            $query = 'SELECT * FROM ' . $this->tb_nm;
            $result = mysql_query($query);
            $i = 0;
            while ($i < mysql_num_fields($result)) {
                $fld = mysql_fetch_field($result, $i);
                $field_nm_a[] = $fld->name;
                $i = $i + 1;
            }

            $query = "SELECT * FROM {$this->tb_nm} WHERE `id` = {$id}";
            $result = mysql_query($query);

            $tmp_a = array();
            $idx = 0;
            while ($row = mysql_fetch_array($result)) {
                if ($idx > 0)
                    break;
                $tmp_idx = 0;
                foreach ($field_nm_a as $value) {
                    $tmp_a[$value] = $row[$tmp_idx];
                    ++$tmp_idx;
                }
                ++$idx;
            }
            return $this->convert_array_to_object($tmp_a);
        } else {
            return '';
        }
    }

    function get_all() {
        if ($this->tb_nm) {
            $query = 'SELECT * FROM ' . $this->tb_nm;
            $result = mysql_query($query);
            $i = 0;
            while ($i < mysql_num_fields($result)) {
                $fld = mysql_fetch_field($result, $i);
                $field_nm_a[] = $fld->name;
                $i = $i + 1;
            }

            $query = "SELECT * FROM {$this->tb_nm}";
            $result = mysql_query($query);

            $tmp_a = array();
            $idx = 0;
            while ($row = mysql_fetch_array($result)) {
                $tmp_idx = 0;
                foreach ($field_nm_a as $value) {
                    $tmp_a[$idx][$value] = $row[$tmp_idx];
                    ++$tmp_idx;
                }
                ++$idx;
            }

            return $this->convert_array_to_object($tmp_a);
        } else {
            return '';
        }
    }

    function del_by_id($id) {
        if ($this->tb_nm) {
            if ($id)
                $query = "DELETE FROM {$this->tb_nm} WHERE id=" . $id;
            mysql_query($query);
        }
    }

}

?>
