<?php
/**
 * Prelauncher Admin
 */
class PrelauncherSettings {


	// Name of the array
	protected $option_name = 'prelauncher-credentials';

	// Default values
	protected $data = array(
	    'company_id' => '',
	    'private_key' => ''
	);

	public function activate() {
    	update_option($this->option_name, $this->data);
	}

	public function deactivate() {
    	delete_option($this->option_name);
	}

	public function __construct() {
		add_action('admin_init', array($this, 'admin_init'));
		add_action('admin_menu', array($this, 'add_page'));

		// Listen for the activate event
		register_activation_hook(TZ_TODO_FILE, array($this, 'activate'));
	}

	public function admin_init() {
    	register_setting('prelauncher_options', $this->option_name, array($this, 'validate'));
	}

	public function validate($input) {

	    $valid = array();
	    $valid['company_id'] = sanitize_text_field($input['company_id']);
	    $valid['private_key'] = sanitize_text_field($input['private_key']);

	    if (strlen($valid['company_id']) == 0) {
	        add_settings_error(
	                'company_id',                     // Setting title
	                'companyid_texterror',            // Error ID
	                'Please enter a company ID',     // Error message
	                'error'                         // Type of message
	        );

	        // Set it to the default value
	        $valid['private_key'] = $this->data['private_key'];
	    }
	    if (strlen($valid['private_key']) == 0) {
	        add_settings_error(
	                'private_ket',
	                'privatekey_texterror',
	                'Please enter a private key',
	                'error'
	        );

	        $valid['private_key'] = $this->data['private_key'];
	    }

	    return $valid;
	}

	// Add entry in the settings menu
	public function add_page() {
	    add_options_page('Prelauncher', 'Prelauncher', 'manage_options', 'prelauncher_options', array($this, 'add_prelauncher_options'));
	}

	// Print the menu page itself
	public function add_prelauncher_options() {
		$options = get_option($this->option_name);
		?>
			<div class="wrap">
			        <h2>Prelauncher options</h2>
			        <form method="post" action="options.php">
			            <?php settings_fields('prelauncher_options'); ?>
			            <table class="form-table">
			                <tr valign="top"><th scope="row">Company ID:</th>
			                    <td><input type="text" name="<?php echo $this->option_name?>[company_id]" value="<?php echo $options['company_id']; ?>" /></td>
			                </tr>
			                <tr valign="top"><th scope="row">Prelauncher private key:</th>
			                    <td><input type="password" name="<?php echo $this->option_name?>[private_key]" value="<?php echo $options['private_key']; ?>" /></td>
			                </tr>
			            </table>
			            <p class="submit">
			                <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			            </p>
			        </form>
			    </div>
		<?php
	}

}


?>