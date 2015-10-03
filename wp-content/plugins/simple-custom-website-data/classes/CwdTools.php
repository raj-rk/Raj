<?php

class CwdTools{

    public function __construct(&$utility, &$messages, &$database)
    {
        $this->utility = $utility;
        $this->messages = $messages;
        $this->database = $database;
    }

    public function export($records)
    {
        $csv = '';

        foreach ($records as $row) {
            $csv .=  $row->ref . "," . $row->data . "\n";
        }

        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private", false);
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename=\"cwd-export_" . date("Y-m-d_H-i-s") . ".csv\";" );
        header("Content-Transfer-Encoding: binary");

        echo $csv;
        exit;
    }

    public function exportJson($records)
    {
        foreach($records as $record)
        {
            unset($record->id);
            if($this->utility->isJson($record->data)) // json
            {
                $record->data = json_decode($record->data);
            }
            elseif($this->utility->isJson(stripslashes($record->data))) // multi
            {
                $record->data = json_decode(stripslashes($record->data));
            }
        }

        if(defined('JSON_PRETTY_PRINT'))
        {
            $json = json_encode($records, JSON_PRETTY_PRINT);
        }
        else
        {
            $json = json_encode($records);
        }

        header("Content-disposition: attachment; filename=\"cwd-export_" . date("Y-m-d_H-i-s") . ".json\";");
        header('Content-type: application/json');
        echo $json;
        exit;
    }

    public function importJson()
    {
        $records = file_get_contents($_FILES['file']['tmp_name']);
        $existSkip = 0;
        $incorrectFormatSkip = 0;
        $imports = 0;
        $recordArray = $this->utility->objectToArray(json_decode($records));
        foreach ($recordArray as $record)
        {
            if(array_key_exists('ref', $record) && array_key_exists('data', $record))
            {
                $recordExists = (!is_null($this->database->getByRef($record['ref'])));

                if(!$recordExists)
                {
                    if(($this->utility->isJson(json_encode($record['data'])) && is_array($record['data'])) || $this->utility->isMulti($record['data']))
                    {
                        $record['data']  = json_encode($record['data']);
                    }

                    // add record
                    $this->database->insert(
                        $this->utility->xssFilter($record['ref']),
                        $this->utility->xssFilter($record['data'])
                        );
                    $imports++;
                }
                else
                {
                    $existSkip++;
                }
            }
            else
            {
                $incorrectFormatSkip++;
            }
        }

        $message = ($imports > 0) ? $imports . ' records have been imported. ' :'';
        $message .= ($imports > 0) ? $existSkip . ' records have been skipped because the reference already exists. ' :'';
        $message .= ($imports > 0) ? $incorrectFormatSkip . ' records have been skipped due to it being incorrectly formatted. ' :'';
        $this->messages->setMessage($message);

    }

    public function importCSV()
    {

        $file = file_get_contents($_FILES['file']['tmp_name']);
        $rows = explode("\n", $file);
        $imports = 0;
        $skipped = 0;

        foreach ($rows as $row)
        {
            if (!empty($row))
            {
                $split = explode(',', $row, 2);

                $recordExists = (!is_null($this->database->getByRef($split[0])));

                if ($this->utility->isJson($split[1]) && !$recordExists)
                {
                    $arr = json_decode(rtrim(trim($split[1]), ","));
                    $json = array();

                    foreach ($arr as $key => $value)
                    {
                        $json[$this->utility->xssFilter($key)] = $this->utility->xssFilter($value);
                    }

                    $this->database->insert(
                        $this->utility->xssFilter($split[0]),
                        json_encode($json)
                        );

                    $imports++;
                }
                elseif (!$recordExists)
                {
                    $this->database->insert(
                        $this->utility->xssFilter($split[0]),
                        $this->utility->xssFilter(rtrim(trim($split[1]), ","))
                        );
                    $imports++;
                }
                else
                {
                    $skipped++;
                }
            }
        }

        $message = $imports . (($imports == 1)? ' record' : ' records') . ' successfully imported';

        if ($skipped > 0)
        {
            $message .= ' | ' . $skipped . ' skipped due to a record \'Reference\' conflict';
        }

        $this->messages->setMessage($message);
        $this->utility->redirectToView();
    }

    public function import()
    {
        if(!wp_verify_nonce( $_GET['_import_rec'], 'import_secure'))
        {
            $this->messages->setMessage("You failed the security check");
            $this->utility->redirectToView('utility');
        }

        switch ($_FILES['file']['type'])
        {
            case 'text/csv':
                $this->importCSV();
                break;

            case 'application/octet-stream':
            case 'application/json':
                $this->importJson();
                break;

            default:
                $this->messages->setMessage("The file must be JSON or CSV");
                $this->utility->redirectToView('utility');
                break;
        }
    }
}