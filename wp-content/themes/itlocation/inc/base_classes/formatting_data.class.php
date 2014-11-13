<?php

/**
 * Formatting for data.
 * 
 * @author Pinghai
 * Description: 
 * 
 * Version: 1.0.0
 * 
 */
class Formatting_Data_PH {

    /**
     * Serialize data, if needed.
     *
     * @since 1.0.0
     *
     * @param mixed $data Data that might be serialized.
     * @return mixed A scalar data
     */
    final public function maybe_serialize($data) {
        if (is_array($data) || is_object($data))
            return serialize($data);

        // Double serialization is required for backward compatibility.
        // See http://core.trac.wordpress.org/ticket/12930
        if ($this->is_serialized($data))
            return serialize($data);

        return $data;
    }

    /**
     * Unserialize value only if it was serialized.
     *
     * @since 1.0.0
     *
     * @param string $original Maybe unserialized original, if is needed.
     * @return mixed Unserialized data can be any type.
     */
    final public function maybe_unserialize($original) {
        if ($this->is_serialized($original)) // don't attempt to unserialize data that wasn't serialized going in
            return @unserialize($original);
        return $original;
    }

    /**
     * Check value to find if it was serialized.
     *
     * If $data is not an string, then returned value will always be false.
     * Serialized data is always a string.
     *
     * @since 2.0.5
     *
     * @param mixed $data Value to check to see if was serialized.
     * @return bool False if not serialized and true if it was.
     */
    final private function is_serialized($data) {
        // if it isn't a string, it isn't serialized
        if (!is_string($data))
            return false;
        $data = trim($data);
        if ('N;' == $data)
            return true;
        $length = strlen($data);
        if ($length < 4)
            return false;
        if (':' !== $data[1])
            return false;
        $lastc = $data[$length - 1];
        if (';' !== $lastc && '}' !== $lastc)
            return false;
        $token = $data[0];
        switch ($token) {
            case 's' :
                if ('"' !== $data[$length - 2])
                    return false;
            case 'a' :
            case 'O' :
                return (bool) preg_match("/^{$token}:[0-9]+:/s", $data);
            case 'b' :
            case 'i' :
            case 'd' :
                return (bool) preg_match("/^{$token}:[0-9.E-]+;\$/", $data);
        }
        return false;
    }

    /**
     * Convert array to object.
     *
     * @since 1.0.0
     *
     * @param array $data Data that might be array.
     * @return object A onject data
     */
    final function convert_array_to_object($array) {
        $obj = new stdClass();
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                $v = $this->convert_array_to_object($v);
            }
            $obj->{strtolower($k)} = $v;
        }
        return $obj;
    }

}

?>