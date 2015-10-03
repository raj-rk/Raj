<?php

class CwdUtility{

    public function xssFilter($string)
    {
        if($this->isJson(stripslashes($string)))
        {
            return strip_tags($string);
        }
        return strip_tags(htmlentities($string));
    }

    public function isJson($string)
    {
        if (!is_numeric($string)) {
            json_decode($string);
            return (json_last_error() == JSON_ERROR_NONE);
        }
        return false;
    }

    public function isMulti($a, $json = null) {
        if($json == true)
        {
            $a = $this->objectToArray(json_decode($a));
        }
        $rv = @array_filter($a,'is_array');
        if(count($rv)>0) return true;
        return false;
    }

    public function processData($data)
    {
        if (is_array($data))
        {
            return json_encode($data);
        }
        elseif($this->isJson($data))
        {
            return json_decode($data, true);
        }
        elseif(strstr($data, "\n") && strstr($data, '='))
        {
            $retArr = array();
            $data = str_replace(array("\r", "\n"), '|', $data);
            $exploded = explode('|', $data);
            foreach ($exploded as $entry => $value)
            {
                $single_ex = explode('=', $value);
                if (!empty($single_ex[0]))
                {
                    $retArr[$single_ex[0]] = $single_ex[1];
                }
            }
            return json_encode($retArr);
        }
        else
        {
            return $data;
        }
    }

    public function redirectToView($view = null)
    {
        if(!headers_sent())
        {
            header('Location: ' . CWD_URL . '&view=' . $view);
            exit;
        }

        echo '<meta http-equiv="refresh" content="0;URL=\'' . CWD_URL . '&view=' . $view . '\'" />';
        exit;

    }

    public function objectToArray($obj)
    {
        if(!is_array($obj) && !is_object($obj)) return $obj;
        if(is_object($obj)) $obj = get_object_vars($obj);
        return array_map(array($this, 'objectToArray'), $obj);
    }
}