<?php

class Product_Admin {

    private $plugin_name;
    private $version;
    
    public function __construct($plugin_name, $version) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    public function enqueue_styles() {
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/plugin-name-admin.css', array(), $this->version, 'all');
    }

    public function enqueue_scripts() {
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/plugin-name-admin.js', array('jquery'), $this->version, false);
    }
    
    public function create_custom_post_type_product() {
        register_post_type('product', array(
            'labels' => array(
                'name' => 'Products',
                'singular_name' => 'Product',
                'add_new' => 'Add New',
                'add_new_item' => 'Add New Product',
                'edit' => 'Edit',
                'edit_item' => 'Edit Product',
                'new_item' => 'New Product',
                'view' => 'View',
                'view_item' => 'View Product',
                'search_items' => 'Search Products',
                'not_found' => 'No Product found',
                'not_found_in_trash' => 'No Products found in Trash',
                'parent' => 'Parent Product'
            ),
            'public' => true,
            'menu_position' => 15,
            'supports' => array('title', 'editor', 'comments', 'thumbnail'),
            'taxonomies' => array('category'),
            'menu_icon' => plugins_url('images/image.png', __FILE__),
            'has_archive' => true,
            'rewrite' => array('slug' => 'products')
            )
        );
    }
    
    public function product_plugin_create_menu(){
        add_options_page( 'Product Options', 'Product Import', 'manage_options', 'product-identifier', array($this,'product_options') );
    }
    
function import_product_test() {
    if ( isset ( $_GET['url'] ) ){
        echo "***************************************************************************";
        echo "***********************  STARTING IMPORT PROCESS  *************************";
        echo "*********** DOWNLOADING FILE, AND SCHEDULING THE PARSER AND   **************";
        echo "********************* SAVING TO DATABASE SHOULD BE **************************";
        echo "*************DONE USING CRON JOB AND SHOULD RUN AT PHP CLI  ******************";
        echo "************* FOR NOW ITS SIMPLE IMPORTED ONLINE            ******************";
        
        require_once plugin_dir_path( __FILE__ ) . 'partials/product-import-handler.php';
        $importhandle = new Product_Import_Handler($_GET['url']);
        $importhandle->importStart();
    }
        
    die( __FUNCTION__ );
}

    public function register_product_import(){
        //register our settings
	register_setting( 'product-settings-group', 'new_option_name' );
	register_setting( 'product-settings-group', 'some_other_option' );
	register_setting( 'product-settings-group', 'option_etc' );
    }
    
    public function product_options(){
        if(!current_user_can('manage_options')){
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }
        require_once plugin_dir_path( __FILE__ ) . 'partials/product-import-manager.php';
    }
    
    public function add_product_meta_box() {
        add_meta_box(
            'product_meta_box',
            'Product Meta Details',
            array( $this, 'render_meta_box' ),
            'product',
            'normal',
            'high'
        );
 
    }
    
    public function render_meta_box(){
        require_once plugin_dir_path( __FILE__ ) . 'partials/single-product-meta-manager.php';
    }


    function add_product_meta_fields( $product_id, $product ) {
        // Check post type for product
        if ( $product->post_type == 'product' ) {
            // Store data in post meta table if present in post data
            if ( isset( $_POST['product_ID'] ) && $_POST['product_ID'] != '' ) {
                update_post_meta( $product_id, 'product_ID', $_POST['product_ID'] );
            }
            if ( isset( $_POST['price_currency'] ) && $_POST['price_currency'] != '' ) {
                update_post_meta( $product_id, 'price_currency', $_POST['price_currency'] );
            }
        }
    }
}