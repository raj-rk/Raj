<?php

class CwdMessages {

    public function showMessage($message, $errormsg = false)
    {
        if ($errormsg)
        {
            echo '<div id="message" class="error">';
        }
        else
        {
            echo '<div id="message" class="updated fade">';
        }

        echo '<p><strong>' . $message . '</strong></p></div>';
    }

    public function showAdminMessages()
    {
        if (isset($_SESSION['cwd_message']) && !empty($_SESSION['cwd_message']))
        {
            $this->showMessage($_SESSION['cwd_message']);
            $_SESSION['cwd_message'] = false;
        }
    }

    public function setMessage($message = null)
    {
        if ($_SESSION['cwd_started'])
        {
            $_SESSION['cwd_message'] = $message;
        }
    }
}