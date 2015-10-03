<?php
/*
Plugin Name: Custom Website Data
Plugin URI: http://dev.dannyweeks.com/cwd/index.php
Version: 2.1
Author: Danny Weeks
Author URI: http://dannyweeks.com/
Description: Allows user to add custom data to be used as either returned values or as shortcodes
*/

class CustomWebsiteData
{
    public function __construct()
    {
        define('CWD_VERSION', '2.1');
        define('CWD_NAMESPACE', 'Cwd\\');
        define('CWD_ROOT', plugins_url('simple-custom-website-data/'));
        define('CWD_MENU_SLUG', 'cwd-management');
        define('CWD_MENU_QUERY_STRING', '?page=' . CWD_MENU_SLUG);
        define('CWD_URL', site_url() . '/wp-admin/admin.php' . CWD_MENU_QUERY_STRING);
        define('CWD_STYLES', CWD_ROOT . 'css/');
        define('CWD_SCRIPTS', CWD_ROOT . 'js/');
        define('CWD_IMAGES', CWD_ROOT . 'img/');

        $this->autoLoadClasses();
        $this->registerActivationHooks();
        $this->createMenu();
        $this->createShortcode();
        $this->startSession();

        if(isset($_GET['export']) && $_GET['export'] == 'true' && on_cwd() && is_admin())
        {
            $this->tools->export($this->database->getAll());
        }

        if(isset($_GET['export']) && $_GET['export'] == 'json' && on_cwd() && is_admin())
        {
            $this->tools->exportJson($this->database->getAll());
        }

        if (on_cwd() && is_admin())
        {
            $this->adminConstruct();
        }
    }

    public function newInstallMsg()
    {
        $this->messages->setMessage('Thanks for installing Custom Website Data, check out the <a href="' . CWD_URL . '&view=user"> User Guide</a> to learn about CWD and get started.');
    }

    //menu and admin page

    public function registerCwdMenu()
    {
        $icon = (on_cwd())? 'active_cwd.png' : 'cwd.png';

        add_menu_page( 'Custom Website Data', 'Custom Data', 'manage_options', CWD_MENU_SLUG, array($this, 'cwd_management_page'), CWD_IMAGES . $icon, '28.8' );

        add_submenu_page( CWD_MENU_SLUG, 'New Record', 'New Record', 'activate_plugins', CWD_MENU_QUERY_STRING . '&view=add');
        add_submenu_page( CWD_MENU_SLUG, 'Utility', 'Utility', 'activate_plugins', CWD_MENU_QUERY_STRING . '&view=utility');
    }

    public function cwd_management_page()
    {
        if(version_compare(get_option('currentCwdVersion', 0), CWD_VERSION, "<") && on_cwd())
        {
            update_option( 'currentCwdVersion', CWD_VERSION );
            $this->utility->redirectToView('whats-new');
        }

        $this->route();
    }

    private function route()
    {
        $imp = (isset($_GET['import'])) ? $_GET['import'] : '';

        switch ($imp)
        {
            case 'true':
                $this->tools->import();
                $this->utility->redirectToView();
                break;
        }
        $view = (isset($_GET['view'])) ? $_GET['view'] : '';
        switch ($view) {
            case 'proc_add':
                $this->requests->add();
                $this->utility->redirectToView();
                break;

            case 'edit_proc':
                $this->requests->edit();
                $this->utility->redirectToView();
                break;

            case 'delete_proc':
                $this->requests->delete();
                $this->utility->redirectToView();
                break;

            default:
                $this->makePage($view);
                break;
        }
    }

    private function makePage($view)
    {
        require 'views/header.php';
        $this->showView($view);
        require 'views/sidebar.php';
        require 'views/footer.php';
    }

    public function showView($view = null){
        switch ($view) {
            case 'dash':
                require 'views/dash.php';
                break;

            case 'whats-new':
                require 'views/whats-new.php';
                break;

            case 'add':
                require 'views/add.php';
                break;

            case 'edit':
                require 'views/edit.php';
                break;

            case 'delete':
                require 'views/delete.php';
                break;

            case 'user':
                require 'views/user.php';
                break;

            case 'utility':
                require 'views/utility.php';
                break;

            case 'import':
                require 'views/import.php';
                break;

            default:
                require 'views/dash.php';
                break;
        }
    }

    //data manipulation

    public function insertData($ref, $data, $skip_proc = false)
    {
        if (!$skip_proc)
        {
            $data = $this->utility->processData($data);
        }

        $this->database->insert($ref, $data);
    }

    private function autoLoadClasses()
    {
        $classes = array(
                    'CwdDatabase',
                    'CwdUtility',
                    'CwdMessages',
                    'CwdReadMe',
                    array('CwdOutput', array ('CwdDatabase','CwdUtility')),
                    array('CwdTools', array('CwdUtility', 'CwdMessages', 'CwdDatabase')),
                    array('CwdRequests', array ('CwdUtility', 'CwdMessages', 'CwdDatabase')),
                    );

        foreach ($classes as $className)
        {
            $args = array();

            if(is_array($className))
            {
                $rawArgs = array_pop($className);

                foreach ($rawArgs as $arg)
                {

                    $argLower = substr(strtolower($arg), 3);
                    $args[] = &$this->{$argLower};
                }

                $className = $className[0];
            }

            require 'classes/' . $className . '.php';
            $classLower = substr(strtolower($className), 3);

            if( ! method_exists($className, '__construct'))
            {
                $this->{$classLower} = new $className;
            }
            else
            {
                $rf = new ReflectionClass($className);
                $this->{$classLower} = $rf->newInstanceArgs($args);
            }
        }
    }

    private function registerActivationHooks()
    {
        register_activation_hook(__FILE__, array($this->database, 'createTable'));

        register_activation_hook(__FILE__, array($this, 'newInstallMsg'));
    }

    private function createMenu()
    {
        add_action( 'admin_menu', array( $this, 'registerCwdMenu') );
    }

    private function createShortcode()
    {
        add_shortcode( 'cwd', array($this->output, 'shortcode'));
    }

    private function adminConstruct()
    {
        add_action('admin_notices', array( $this->messages, 'showAdminMessages'));
        add_action('admin_enqueue_scripts', array( $this, 'adminScripts'));

    }

    public function adminScripts()
    {
        wp_register_script('cwdScript', CWD_SCRIPTS . 'cwd.js', array('jquery'), CWD_VERSION);
        wp_enqueue_script('cwdScript');
        wp_enqueue_style('cwdStyles', CWD_STYLES . 'cwd.css', null, CWD_VERSION);
    }

    private function startSession()
    {
        if( !session_id() && function_exists('session_start'))
        {
            session_start();
            $_SESSION['cwd_started'] = true;
        }
    }
}

include('cwd-functions.php');

//init class
$cwd = new CustomWebsiteData;