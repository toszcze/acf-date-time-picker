<?php
// exit if accessed directly
if(!defined('ABSPATH')) exit;

if(!class_exists('acf_field_date_time_picker')):

class acf_field_date_time_picker extends acf_field {
	/**
	* Class constructor
	*
	* @since 1.0.0
	*/
	public function __construct() {
		$this->name = 'date_time_picker';
		$this->label = __('Date & Time Picker', 'acf-date-time-picker');
		$this->category = 'jQuery';
		
		$this->defaults = array(
			'field_type' => 'date_time',
			'date_format' => 'yy-mm-dd',
			'time_format' => 'HH:mm',
			'first_day' => 1,
		);
		
		add_action('init', array($this, 'init'));
		
    	parent::__construct();
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
	* Create extra settings for the field
	* 
	* @since 1.0.0
	*
	* @param array $field The field being edited
	*/
	public function render_field_settings($field) {
		acf_render_field_setting($field, array(
			'label' => __('Field type', 'acf-date-time-picker'),
			'type' => 'radio',
			'layout' => 'horizontal',
			'name' => 'field_type',
			'choices' => array('date_time' => __('date & time', 'acf-date-time-picker'),
							   'time' => __('time only', 'acf-date-time-picker')),
		));
		
		acf_render_field_setting($field, array(
			'label' => __('Date format', 'acf-date-time-picker'),
			'instructions' => __('Read more about <a href="http://api.jqueryui.com/datepicker/#utility-formatDate" target="_blank">date formats</a>', 'acf-date-time-picker'),
			'type' => 'text',
			'name' => 'date_format',
		));
		
		acf_render_field_setting($field, array(
			'label' => __('Time format', 'acf-date-time-picker'),
			'instructions' => __('Read more about <a href="http://trentrichardson.com/examples/timepicker/#tp-formatting" target="_blank">time formats</a>', 'acf-date-time-picker'),
			'type' => 'text',
			'name' => 'time_format',
		));
		
		global $wp_locale;
		$choices = array_values($wp_locale->weekday);
		acf_render_field_setting($field, array(
			'label' => __('Week starts on', 'acf-date-time-picker'),
			'type' => 'select',
			'name' => 'first_day',
			'choices' => $choices,
		));		
	}
	
	/**
	* Create the HTML interface for the field
	*
	* @since 1.0.0
	* 
	* @param array $field The field being rendered
	*/
	public function render_field($field) {
		$value = $this->format_date_time($field['value'], $field);
		$value = $field['value'];
		?>
		<div class="acf-date_time_picker acf-input-wrap" data-field-type="<?php echo esc_attr($field['field_type']); ?>"
										  data-date-format="<?php echo esc_attr($field['date_format']); ?>"
										  data-time-format="<?php echo esc_attr($field['time_format']); ?>"
										  data-first-day="<?php echo esc_attr($field['first_day']); ?>"
										  >
			<input type="text" name="<?php echo esc_attr($field['name']); ?>" value="<?php echo esc_attr($value); ?>" class="input" />
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
		wp_enqueue_style('acf-jquery-ui-slider', ACF_DTP_URL.'assets/css/jquery-ui-slider.css', array('acf-jquery-ui-timepicker'), ACF_DTP_VERSION);
		wp_enqueue_script('jquery-ui-timepicker', ACF_DTP_URL.'assets/js/jquery-ui-timepicker/jquery-ui-timepicker-addon.min.js', array('jquery-ui-datepicker', 'jquery-ui-slider'), true, ACF_DTP_VERSION);
		wp_enqueue_script('acf-jquery-ui-slideraccess', ACF_DTP_URL.'assets/js/jquery-ui-timepicker/jquery-ui-sliderAccess.js', array('jquery-ui-slider'), true, ACF_DTP_VERSION);
		wp_enqueue_script('acf-input-date_time_picker', ACF_DTP_URL.'assets/js/input.js', array('jquery-ui-timepicker'), true, ACF_DTP_VERSION);
	}	
	
	/**
	* Convert date and time to the standard format (Y-m-d H:i:s) before saving it to database
	*
	* @since 1.0.0
	*
	* @param mixed $value The value which will be saved in the database
	* @param integer $post_id The post ID of which the value will be saved
	* @param array $field The field array holding all the field options
	* 
	* @return mixed The value which will be saved in the database
	*/
	public function update_value($value, $post_id, $field) {
		if(preg_match('/^dd?\//', $field['date_format'])) { // if start with dd/ or d/ (not supported by strtotime())
			$value = str_replace('/', '-', $value);
		}
		$value = date('Y-m-d H:i:s', strtotime($value));
		return $value;
	}
	
	/**
	* Format date and time after it is loaded from the db
	*
	* @since 1.0.0
	*
	* @param mixed $value The value found in the database
	* @param mixed $post_id The post ID from which the value was loaded
	* @param array $field The field array holding all the field options
	* 
	* @return mixed The formatted date and time
	*/
	public function load_value($value, $post_id, $field) {
		return $this->format_date_time($value, $field);
		
	}

	/**
	* Format date and time after it is loaded from the db and before it is returned to the template
	*
	* @since 1.0.0
	*
	* @param mixed $value The value found in the database
	* @param mixed $post_id The post ID from which the value was loaded
	* @param array $field The field array holding all the field options
	*
	* @return mixed The formatted date and time
	*/
	public function format_value($value, $post_id, $field) {
		return $this->format_date_time($value, $field);
	}

	/**
	* Format date and time
	*
	* @since 1.0.0
	*
	* @param mixed $value The value found in the database
	* @param mixed $format The date and time format
	*
	* @return mixed The formatted date and time
	*/
	public function format_date_time($value, $field) {
		if($field['field_type'] == 'time') {
			$format = $this->time_format_js_to_php($field['time_format']);
		}
		else {
			$format = $this->date_format_js_to_php($field['date_format']).' '.$this->time_format_js_to_php($field['time_format']);
		}
		return date_i18n($format, strtotime($value));
	}

	/**
	* Convert JS date format string to PHP
	*
	* @since 1.0.0
	*
	* @param string $format JS date format string
	* 
	* @return string PHP date format string
	*/
	public function date_format_js_to_php($format) {
		$t = array(
			// day
			'dd' => 'd',
			'd' => 'j',
			'DD' => 'l',
			'D' => 'D',
			'o' => 'z',
			// month
			'mm' => 'm',
			'm' => 'n',
			'MM' => 'F',
			'M' => 'M',
			// year
			'yy' => 'Y',
			'y' => 'y',
			);
		return strtr((string)$format, $t);
	}
	
	/**
	* Convert JS time format string to PHP
	*
	* @since 1.0.0
	*
	* @param string $format JS time format string
	* 
	* @return string PHP time format string
	*/
	public function time_format_js_to_php($format) {
		$t = array(
			// hour
			'HH' => 'H',
			'H' => 'G',
			'hh' => 'h',
			'h' => 'g',
			// minute
			'mm' => 'i',
			'm' => 'i',
			// second
			'ss' => 's',
			's' => 's',
			// am / pm
			'tt' => 'a',
			't' => 'a',
			'TT' => 'A',
			'T' => 'A',
			);
		return strtr((string)$format, $t);
	}
}

// initialize field class
new acf_field_date_time_picker();

endif;
