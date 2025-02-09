<?php
/**
 * Created by PhpStorm.
 * User: Chris
 * Date: 27/03/14
 * Time: 11:06 AM
 */

// No direct access
if ( ! defined( 'ABSPATH' ) ) exit;

require_once dirname( __FILE__ ) . '/interface-data-provider.php';

abstract class CTCI_DataProvider implements CTCI_DataProviderInterface {

	public function showSettingsPage() {
		if ( ! current_user_can( Church_Theme_Content_Integration::$CONFIG_CAPABILITY ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		?>
		<div class="wrap">
			<h2><?php printf( __( '%s Settings', Church_Theme_Content_Integration::$TEXT_DOMAIN ), $this->getHumanReadableName() ); ?></h2>
			<!--suppress HtmlUnknownTarget -->
			<form method="post" action="options.php">
				<?php
				// This prints out all hidden setting fields
				settings_fields( $this->getSettingsGroupName() );
				do_settings_sections( $this->getSettingsPageName() );
				submit_button();
				?>
			</form>
		</div>
	<?php
	}

	public function registerSettings() {
		register_setting( $this->getSettingsGroupName(), $this->getSettingsGroupName(), array( $this, 'validateSettings' ) );
		$this->registerSectionsAndFields();
	}

	abstract protected function getSettingsPageName();

	abstract protected function registerSectionsAndFields();

	protected function addSettingsSection( $id, $title, $beforeContentCallbackName ) {
		add_settings_section(
			$id,
			$title,
			array( $this, $beforeContentCallbackName ),
			$this->getSettingsPageName()
		);
	}

	protected function addSettingsField( $sectionName, $fieldName, $fieldTitle, $displayFieldCallbackName, array $args = array() ) {
		add_settings_field(
			$fieldName,
			$fieldTitle,
			array( $this, $displayFieldCallbackName ),
			$this->getSettingsPageName(),
			$sectionName,
			array_merge(
				array(
					'fieldName' => $fieldName
				), $args
			)
		);
	}

	public function displayTextField( $args = array() ) {
		$optionValues = get_option( $this->getSettingsGroupName() );
		$attr = '';
		if ( isset( $args['size'] ) ) {
			$attr .= "size='" . $args['size'] . "' ";
		}
		if ( isset( $args['maxlength'] ) ) {
			$attr .= "maxlength='" . $args['maxlength'] . "' ";
		}
		$value = '';
		if ( isset( $optionValues[ $args['fieldName'] ] ) ) {
			$value = "value='" . $optionValues[ $args['fieldName'] ] . "'";
		}
		printf(
			"<input id='%s' name='%s' type='text' %s %s />",
			$args['fieldName'],
			sprintf( "%s[%s]", $this->getSettingsGroupName(), $args['fieldName'] ),
			$value,
			$attr
		);
	}

	public function displayPasswordField( $args = array() ) {
		$optionValues = get_option( $this->getSettingsGroupName() );
		$attr = '';
		if ( isset( $args['size'] ) ) {
			$attr .= "size='" . $args['size'] . "' ";
		}
		$value = '';
		if ( isset( $optionValues[ $args['fieldName'] ] ) ) {
			$value = "value='" . $optionValues[ $args['fieldName'] ] . "'";
		}
		printf(
			"<input id='%s' name='%s' type='password' %s %s />",
			$args['fieldName'],
			sprintf( "%s[%s]", $this->getSettingsGroupName(), $args['fieldName'] ),
			$value,
			$attr
		);
	}

	public function displayCheckBoxField( $args ) {
		$optionValues = get_option( $this->getSettingsGroupName() );
		$name = sprintf( "%s[%s]", $this->getSettingsGroupName(), $args['fieldName'] );
		// this hidden field ensures the field is submitted even if unchecked
		// by default forms do not submit checkboxes not checked
		printf("<input type='hidden' name='%s' value='F' />", $name);
		printf(
			"<input id='%s' name='%s' type='checkbox' value='T' %s />",
			$args['fieldName'],
			$name,
			checked(
				isset( $optionValues[ $args['fieldName'] ] ) &&
				$optionValues[ $args['fieldName'] ] === 'T',
				true,
				false
			)
		);
	}

	public function displayTextAreaField( $args = array() ) {
		$optionValues = get_option( $this->getSettingsGroupName() );
		$attr = '';
		if ( isset( $args['cols'] ) ) {
			$attr .= "cols='" . $args['cols'] . "' ";
		}
		if ( isset( $args['rows'] ) ) {
			$attr .= "rows='" . $args['rows'] . "' ";
		}
		$value = '';
		if ( isset( $optionValues[ $args['fieldName'] ] ) ) {
			$value = $optionValues[ $args['fieldName'] ];
		}
		printf(
			"<textarea id='%s' name='%s' %s />%s</textarea>",
			$args['fieldName'],
			sprintf( "%s[%s]", $this->getSettingsGroupName(), $args['fieldName'] ),
			$attr,
			$value
		);
	}

	public function displaySelectField( $args = array() ) {
		$optionValues = get_option( $this->getSettingsGroupName() );
		$attr = '';
		$value = '';
		if ( isset( $optionValues[ $args['fieldName'] ] ) ) {
			$value = $optionValues[ $args['fieldName'] ];
		}
		printf(
			"<select id='%s' name='%s' %s >",
			$args['fieldName'],
			sprintf( "%s[%s]", $this->getSettingsGroupName(), $args['fieldName'] ),
			$attr
		);
		foreach ( $args['options'] as $key => $option ) {
			if ( $value == $key ) {
				printf( '<option value="%s" selected>%s</option>', $key, esc_attr($option));
			} else {
				printf( '<option value="%s">%s</option>', $key, esc_attr($option));
			}
		}
		echo '</select>';
	}
} 