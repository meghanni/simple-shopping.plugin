<?php

class SSC_View
{
    /**
     * Path of the view to render
     */
    var $view = "";
    /**
     * Variables for the view
     */
    var $vars = array();
    /**
     * Construct a view from a file in the
     */
    public 
    function __construct($view) 
    {
        $this->view = SSC_PLUGIN_DIR . "/views/" . $view . ".view.php";
    }
    /**
     * set a variable which gets rendered in the view
     */
    public 
    function Set($name, $value) 
    {
        $this->vars[$name] = $value;
    }
    /**
     * render the view
     */
    public 
    function Render() 
    {
        extract($this->vars, EXTR_SKIP);
        ob_start();
        if ( file_exists($this->view) )
            include $this->view;
        else
            echo $this->view . " not found";
        
        return ob_get_clean();
    }
}

