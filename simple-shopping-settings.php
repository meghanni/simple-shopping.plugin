<?php

class scc_settings
{
    public 
    function __construct() 
    {
        
        if (is_admin()) 
        {
            add_action('admin_menu', array(
                $this,
                'add_plugin_page'
            ));
            add_action('admin_init', array(
                $this,
                'page_init'
            ));
        }
    }
    public 
    function add_plugin_page() 
    {

        // This page will be under "Settings"
        add_options_page('Settings Admin', 'Simple Shopping Cart', 'manage_options', 'test-setting-admin', array(
            $this,
            'create_admin_page'
        ));
    }
    public 
    function create_admin_page() 
    {
?>
	<div class="wrap">
	    <?php screen_icon(); ?>
	    <h2>Simple Shopping Cart Settings</h2>
	    <hr/>			
	    <form method="post" action="options.php">
	        <?php

        // This prints out all hidden setting fields
        settings_fields('test_option_group');
        do_settings_sections('test-setting-admin');
?>
	        <?php submit_button(); ?>
	    </form>
	</div>
	<?php
    }
    public 
    function page_init() 
    {
        add_settings_section('setting_section_id', '<h3>Setting</h3>', array(
            $this,
            'print_section_info'
        ) , 'test-setting-admin');    
        register_setting('test_option_group', 'array_key', array(
            $this,
            'check_form'
        ));
        

        add_settings_field('pricing_level', 'Number of Pricing Levels (Max 10): ', array(
            $this,
            'create_pricing_level_field'
        ) , 'test-setting-admin', 'setting_section_id');
        add_settings_field('company_name', 'Your Company Name:', array(
            $this,
            'create_company_name_field'
        ) , 'test-setting-admin', 'setting_section_id');        
    }
    public 
    function check_form($input) 
    {
        $mid = filter_var($input['pricing_level'],FILTER_SANITIZE_NUMBER_INT);
        
        if ($mid && $mid > 0 && $mid <= 10 ) 
        {
            $mid = $input['pricing_level'];
            
            if (get_option('pricing_level') === FALSE) 
            {
                add_option('pricing_level', $mid);
            }
            else
            {
                update_option('pricing_level', $mid);
            }
        }
        else
        {
            $mid = '';
        }
        
           $mid = filter_var($input['company_name'],FILTER_SANITIZE_STRING);
            
            
            
            if (get_option('company_name') === FALSE) 
            {
                add_option('company_name', $mid);
            }
            else
            {
                update_option('company_name', $mid);
            }


        
        return $input;
    }
   
    public 
    function print_section_info() 
    {
        print 'Enter your setting below:';
    }
    public 
    function create_pricing_level_field() 
    {
?><input type="text" size="5" id="pricing_level" name="array_key[pricing_level]" value="<?=get_option('pricing_level'); ?>" /><?php
    }
    
    public 
    function create_company_name_field() 
    {
?><input type="text" size="40" id="company_name" name="array_key[company_name]" value="<?=get_option('company_name'); ?>" /><?php
    }
}
$scc_settings = new scc_settings();

