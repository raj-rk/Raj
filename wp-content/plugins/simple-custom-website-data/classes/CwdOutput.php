<?php

class CwdOutput{

    public function __construct(&$database, &$utility)
    {
        $this->utility = $utility;
        $this->database = $database;
    }

    public function shortcode($atts){
        extract(shortcode_atts(array(
          'ref' => null,
          'key' => null,
       ), $atts));

        return $this->process($ref, $key);
    }

    public function process($ref, $key = null)
    {
        $rawData = $this->database->getByRef($ref)->data;

        if ($this->utility->isJson($rawData) || $this->utility->isJson(stripcslashes($rawData)))
        {
            $data =  json_decode(stripcslashes($rawData), true);

            if(!empty($key) && isset($data[$key]))
            {
                return $data[$key];
            }
            return $data;
        }

        return $rawData;
    }

    public function prettyArray($data)
    {
        if(!$this->utility->isMulti($data))
        {
            echo '<strong>Array:</strong>' . '<br>';
            echo "<ul>";
            foreach($data as $ref => $key)
            {
                echo '<li>';
                echo $ref . ' => ' . $key ;
                echo '</li>';
            }
        }
        else
        {
            echo '<strong>Multidimensional Array:</strong>' . '<br>';
            echo "<ul>";
            foreach($data as $mainRef => $inner)
            {
                echo '<li>';
                echo $mainRef;
                echo '<ul>';
                foreach($inner as $innerRef => $innerData)
                {
                    echo '<li>';
                    echo $innerRef . ' => ' . ((is_array($innerData))? '<em>Array</em>' : $innerData) ;
                    echo '</li>';
                }
                echo '</ul>';
                echo '</li><hr>';
            }
        }
        echo "</ul>";
    }
}