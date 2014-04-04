<?php
class KuneSettingsPage
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Settings Admin', 
            _e('Kune Settings', KUNE_DOMAIN), 
            'manage_options', 
            'my-setting-admin', 
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'my_option_name' ); ?>
        <div class="wrap">
          <?php screen_icon(); ?>
         <h2>Kune Settings</h2>           
         <form method="post" action="options.php">
        <?php
          // This prints out all hidden setting fields
          settings_fields( 'my_option_group' );   
          do_settings_sections( 'my-setting-admin' );
          submit_button(); 
        ?>
</form>
</div>
<?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {        
        register_setting(
            'my_option_group', // Option group
            'my_option_name', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            _e('Kune Custom Settings', KUNE_DOMAIN), // Title
            array( $this, 'print_section_info' ), // Callback
            'my-setting-admin' // Page
        );  

        add_settings_field(
            'sitebarRightMargin', // ID
            _e('Kune sitebar right margin (position of the kune sitebar)', KUNE_DOMAIN), // Title 
            array( $this, 'sitebarRightMargin_callback' ), // Callback
            'my-setting-admin', // Page
            'setting_section_id' // Section           
        );      

        add_settings_field(
            'sitebarTopMargin', 
            _e('Kune sitebar top margin (position of the kune sitebar)', KUNE_DOMAIN), 
            array( $this, 'sitebarTopMargin_callback' ), 
            'my-setting-admin', 
            'setting_section_id'
        );      
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        if( isset( $input['sitebarRightMargin'] ) )
            $new_input['sitebarRightMargin'] = absint( $input['sitebarRightMargin'] );

        if( isset( $input['sitebarTopMargin'] ) )
            // $new_input['sitebarTopMargin'] = sanitize_text_field( $input['sitebarTopMargin'] );
            $new_input['sitebarTopMargin'] = absint( $input['sitebarTopMargin'] );

        return $new_input;
    }

    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
        print _e('These are the default options for kune documents embeded in this Wordpress:', KUNE_DOMAIN);
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function sitebarRightMargin_callback()
    {
        printf(
            '<input type="text" id="sitebarRightMargin" name="my_option_name[sitebarRightMargin]" value="%s" />',
            isset( $this->options['sitebarRightMargin'] ) ? esc_attr( $this->options['sitebarRightMargin']) : ''
        );
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function sitebarTopMargin_callback()
    {
        printf(
            '<input type="text" id="sitebarTopMargin" name="my_option_name[sitebarTopMargin]" value="%s" />',
            isset( $this->options['sitebarTopMargin'] ) ? esc_attr( $this->options['sitebarTopMargin']) : ''
        );
    }
}

if( is_admin() )
    $my_settings_page = new KuneSettingsPage();