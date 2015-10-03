<?php

class FMViewGenerete_csv {
  ////////////////////////////////////////////////////////////////////////////////////////
  // Events                                                                             //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constants                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Variables                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  private $model;

  ////////////////////////////////////////////////////////////////////////////////////////
  // Constructor & Destructor                                                           //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function __construct($model) {
    $this->model = $model;
  }
  
  ////////////////////////////////////////////////////////////////////////////////////////
  // Public Methods                                                                     //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function display() {
    $params = $this->model->get_data();
	$data = $params[0];
	$title = $params[1]; 
	$is_paypal_info = $params[2];
	
	function cleanData(&$str) {
      $str = preg_replace("/\t/", "\\t", $str);
      $str = preg_replace("/\r?\n/", "\\n", $str);
      if (strstr($str, '"'))
        $str = '"' . str_replace('"', '""', $str) . '"';
    }
	
	$all_keys = array();
	foreach ($data as $key =>$row) {
		$all_keys = array_merge($all_keys, $row);
	}

	$keys_array = array_keys($all_keys);
	foreach ($data as $key => $row) {
		foreach ($keys_array as $key1 => $value) {
			if(!array_key_exists ( $value , $row ))
				array_splice($row, $key1, 0, '');
		}
		$data[$key] = $row;
    }

    // File name for download.
    $filename = $title . "_" . date('Ymd') . ".csv";
    header('Content-Encoding: Windows-1252');
    header('Content-type: text/csv; charset=Windows-1252');
    header("Content-Disposition: attachment; filename=\"$filename\"");
    $flag = FALSE;
    foreach ($data as $row) {
      if (!$flag) {
        # display field/column names as first row
        // echo "sep=,\r\n";
        echo '"' . implode('","', str_replace('PAYPAL_', '', $keys_array));
        
        echo "\"\r\n";
        $flag = TRUE;
      }
      array_walk($row, 'cleanData');
      echo '"' . html_entity_decode(implode('","', array_values($row))) . "\"\r\n";
    }
    die('');
  }

  ////////////////////////////////////////////////////////////////////////////////////////
  // Getters & Setters                                                                  //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Private Methods                                                                    //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Listeners                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
}