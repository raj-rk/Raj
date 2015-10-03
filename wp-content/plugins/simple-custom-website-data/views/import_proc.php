<?php
if($_FILES['file']['type'] == 'text/csv' && wp_verify_nonce( $_GET['_import_rec'], 'import_secure'))
{
    $file = file_get_contents($_FILES['file']['tmp_name']);
    $rows = explode("\n", $file);
    $import_arr = array();
    $skipped = 0;
    foreach ($rows as $row) {
        if (!empty($row)) {

            $split = explode(',', $row, 2);
            if ($this->isJson($split[1]) && !$this->getRecordIdByRef($split[0])) {

                $arr = json_decode(rtrim(trim($split[1]), ","));
                $json = array();
                foreach ($arr as $key => $value) {
                    $json[$this->xss_filter($key)] = $this->xss_filter($value);
                }

                $import_arr[] = array(
                        'ref' => $this->xss_filter($split[0]),
                        'data'=> json_encode($json),
                        'json'=> true
                        );
            }
            elseif (!$this->getRecordIdByRef($split[0])) {
                $import_arr[] = array(
                        'ref' => $this->xss_filter($split[0]),
                        'data'=> $this->xss_filter(rtrim(trim($split[1]), ","))
                        );
            }
            else
            {
                $skipped++;
            }
        }
    }

    foreach ($import_arr as $record) {
        if ($record['json']) {
            $this->insertData($record['ref'], $record['data'], true);
        }
        else
        {
            $this->insertData($record['ref'], $record['data']);
        }
    }
    $imports = count($import_arr);
    $message = $imports . (($imports == 1)? ' record' : ' records') . ' successfully imported';

    if ($skipped > 0) {
        $message .= ' | ' . $skipped . ' skipped due to a record \'Reference\' conflict';
    }


    $this->setMessage($message);
    header('Location: ' . site_url() . '/wp-admin/admin.php?page=cwd-management');
}
else
{
    if (!$_FILES['file']['type'] == 'text/csv') {
        $this->setMessage("The file provided is not CSV");
        header('Location: ' . site_url() . '/wp-admin/admin.php?page=cwd-management');
    }
    else
    {
        $this->setMessage("You failed the security check");
        header('Location: ' . site_url() . '/wp-admin/admin.php?page=cwd-management');
    }

}