<?php

function cwd_getThe($ref = null, $key = null)
{
    global $cwd;
    return $cwd->output->process($ref, $key);
}

function cwd_updateThe($ref, $data)
{
    global $cwd;
    if ($record = $cwd->database->getByRef($ref))
    {
        if (is_array($data))
        {
            $filtered_data = array();
            foreach ($data as $key => $value)
            {
                $filtered_data[$key] = $cwd->utility->xssFilter($value);
            }
            $cwd->database->update($record->id, json_encode($filtered_data));
            return true;
        }

        elseif(is_string($data) || is_numeric($data))
        {
            $cwd->database->update($record->id, $data);
            return true;
        }
    }

    return false;
}

function on_cwd()
{
    return (isset($_GET['page']) && $_GET['page'] == 'cwd-management');
}

// debuging use only

function cwddd($var)
{
    echo "<pre>";
    var_dump($var);
    echo "</pre>";
    die();
}
