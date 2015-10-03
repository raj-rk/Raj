<?php

class CwdRequests{

    public function __construct(&$utility, &$messages, &$database)
    {
        $this->utility = $utility;
        $this->messages = $messages;
        $this->database = $database;
    }

    public function add()
    {
        if (isset($_POST['ref']) && !empty($_POST['ref']) && isset($_POST['data']) && !empty($_POST['data']) && $_POST['cwdaction'] == 'add')
        {
            if ( !empty($_POST) && check_admin_referer('cwd_add_action','cwd_add_name') )
            {
                $ref = $this->utility->xssFilter($_POST['ref']);
                $data = $this->utility->xssFilter($_POST['data']);
                $data = $this->utility->processData($data);
                $this->database->insert($ref, $data);
                $this->messages->setMessage('The record "' . $ref . '" has been added') ;
            }

        }
    }

    public function edit()
    {
        $cwd_id = (isset($_REQUEST['id']))? $_REQUEST['id'] : false;

        if($_POST['edit'] == 'y' && $cwd_id)
        {
            if (wp_verify_nonce( $_POST['_edit_rec'], 'edit_rec-' .  $_POST['id']))
            {
                $this->database->update($_POST['id'], $this->utility->processData($this->utility->xssFilter($_POST['data'])));
                $this->messages->setMessage('The record was updated');
            }
            else
            {
                $this->messages->setMessage('Securty check failed');
            }

        }
    }

    public function delete()
    {
        $cwd_id = (isset($_REQUEST['id']))? $_REQUEST['id'] : false;

        if($_GET['del'] == 'y' && $cwd_id)
        {
            if (wp_verify_nonce( $_GET['_del_rec'], 'del_rec-' .  $_GET['id']))
            {
                $this->database->delete($this->utility->xssFilter($cwd_id));
                $this->messages->setMessage('The record has been deleted') ;
            }
            else
            {
                $this->messages->setMessage('Securty check failed');
            }
        }
    }
}