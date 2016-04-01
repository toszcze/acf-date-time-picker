<?php
// exit if accessed directly
if(!defined('ABSPATH')) exit;

if(!class_exists('acf_field_date_time_picker')):

class acf_field_date_time_picker extends acf_field {
	/**
	 * Field Options
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	private $settings;
	
	/**
	 * Default field options
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	public $defaults;
	
	/**
	* Class constructor
	*
	* @since 1.0.0
	*/
	public function __construct() {
		$this->name = 'date_time_picker';
		$this->label = __('Date & Time Picker', 'acf-date-time-picker');
		$this->category = __('jQuery', 'acf');
		
		$this->defaults = array(
			'field_type' => 'date_time',
			'date_format' => 'yy-mm-dd',
			'time_format' => 'HH:mm',
			'first_day' => 1,
		);
		
		add_action('init', array($this, 'init'));
		
    	parent::__construct();
		
		$this->settings = array(
			'path' => apply_filters('acf/helpers/get_path', __FILE__),
			'dir' => apply_filters('acf/helpers/get_dir', __FILE__),
			'version' => ACF_DTP_VERSION,
		);
	}
	
	/**
	* Initialize translations
	*
	* @since 1.0.0
	*/
	public function init() {
		global $wp_locale;
		
		$this->l10n = array(
			'closeText'         => __('Done', 'acf-date-time-picker'),
			'currentText'       => __('Now', 'acf-date-time-picker'),
			'monthNames'        => array_values($wp_locale->month),
			'monthNamesShort'   => array_values($wp_locale->month_abbrev),
			'monthStatus'       => __('Show a different month', 'acf-date-time-picker'),
			'dayNames'          => array_values($wp_locale->weekday),
			'dayNamesShort'     => array_values($wp_locale->weekday_abbrev),
			'dayNamesMin'       => array_values($wp_locale->weekday_initial),
			'isRTL'             => isset($wp_locale->is_rtl) ? $wp_locale->is_rtl : false,
			'timeOnlyTitle'		=> __('Choose Time', 'acf-date-time-picker'),
			'timeText'			=> __('Time', 'acf-date-time-picker'),
			'hourText'			=> __('Hour', 'acf-date-time-picker'),
			'minuteText'		=> __('Minute', 'acf-date-time-picker'),
			'secondText'		=> __('Second', 'acf-date-time-picker'),
		);
	}
	
	/**
	* Create extra options for your field. This is rendered when editing a field.
	*
	* @since 1.0.0
	*
	* @param array $field Field data
	*/
	public function create_options($field) {
		$field = array_merge($this->defaults, $field);		
		$key = $field['name'];
		
		?>
		<tr class="field_option field_option_<?php echo $this->name; ?>">
			<td class="label">
				<label><?php _e('Field type', 'acf-date-time-picker'); ?></label>
			</td>
			<td>
				<?php
				do_action('acf/create_field', array(
					'type' => 'radio',
					'layout' => 'horizontal',
					'name' => 'fields['.$key.'][field_type]',
					'value' => $field['field_type'],
					'choices' => array('date_time' => __('date & time', 'acf-date-time-picker'),
									   'time' => __('time only', 'acf-date-time-picker')),
				));
				?>
			</td>
		</tr>
		
		<tr class="field_option field_option_<?php echo $this->name; ?>">
			<td class="label">
				<label><?php _e('Date format', 'acf-date-time-picker'); ?></label>
				<p class="description"><?php _e('Read more about <a href="http://api.jqueryui.com/datepicker/#utility-formatDate" target="_blank">date formats</a>', 'acf-date-time-picker'); ?></p>
			</td>
			<td>
				<?php
				do_action('acf/create_field', array(
					'type' => 'text',
					'name' => 'fields['.$key.'][date_format]',
					'value' => $field['date_format'],
				));
				?>
			</td>
		</tr>
		
		<tr class="field_option field_option_<?php echo $this->name; ?>">
			<td class="label">
				<label><?php _e('Time format', 'acf-date-time-picker'); ?></label>
				<p class="description"><?php _e('Read more about <a href="http://trentrichardson.com/examples/timepicker/#tp-formatting" target="_blank">time formats</a>', 'acf-date-time-picker'); ?></p>
			</td>
			<td>
				<?php
				do_action('acf/create_field', array(
					'type' => 'text',
					'name' => 'fields['.$key.'][time_format]',
					'value' => $field['time_format'],
				));
				?>
			</td>
		</tr>
		
		<?php
		global $wp_locale;
		$choices = array_values($wp_locale->weekday);
		?>
		<tr class="field_option field_option_<?php echo $this->name; ?>">
			<td class="label">
				<label><?php _e('Week starts on', 'acf-date-time-picker'); ?></label>
			</td>
			<td>
				<?php
				do_action('acf/create_field', array(
					'type' => 'select',
					'name' => 'fields['.$key.'][first_day]',
					'value' => $field['first_day'],
					'choices' => $choices,
				));
				?>
			</td>
		</tr>
		<?php
	}
	
	/**
	* Create the HTML interface for the field
	* 
	* @since 1.0.0
	*
	* @param array $field Field data
	*/	
	public function create_field($field) {
		$field = array_merge($this->defaults, $field);
		?>
		<div class="acf-date_time_picker" data-field-type="<?php echo esc_attr($field['field_type']); ?>"
										  data-date-format="<?php echo esc_attr($field['date_format']); ?>"
										  data-time-format="<?php echo esc_attr($field['time_format']); ?>"
										  data-first-day="<?php echo esc_attr($field['first_day']); ?>"
										  >
			<input type="text" name="<?php echo esc_attr($field['name']); ?>" value="<?php echo esc_attr($field['value']); ?>" class="input" />
		</div>
		<?php
	}
	
	/**
	* Enqueue admin JavaScript scripts and CSS stylesheets
	*
	* @since 1.0.0
	*/
	public function input_admin_enqueue_scripts() {
		wp_enqueue_style('acf-jquery-ui-timepicker', ACF_DTP_URL.'assets/css/jquery-ui-timepicker-addon.min.css', array('acf-datepicker'), ACF_DTP_VERSION);
		wp_enqueue_script('acf-jquery-ui-timepicker', ACF_DTP_URL.'assets/js/jquery-ui-timepicker/jquery-ui-timepicker-addon.min.js', array('jquery-ui-datepicker', 'jquery-ui-slider'), true, ACF_DTP_VERSION);
		wp_enqueue_script('acf-jquery-ui-slideraccess', ACF_DTP_URL.'assets/js/jquery-ui-timepicker/jquery-ui-sliderAccess.js', array('jquery-ui-slider'), true, ACF_DTP_VERSION);
		wp_enqueue_script('acf-input-date_time_picker', ACF_DTP_URL.'assets/js/input.js', array('acf-jquery-ui-timepicker'), true, ACF_DTP_VERSION);
	}
	
	/**
	* Validate date and time before saving it to database
	*
	* @since 1.0.0
	*
	* @param mixed $value The value which will be saved in the database
	* @param integer $post_id The post ID of which the value will be saved
	* @param array $field The field array holding all the field options
	* @return mixed The value which will be saved in the database
	*/
	public function update_value($value, $post_id, $field) {
		if(strtotime($value) === -1 || strtotime($value) === false) {
			return '';
		}
		return $value;
	}
	
}

// initialize field class
new acf_field_date_time_picker();

endif;
