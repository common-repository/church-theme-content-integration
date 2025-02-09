<?php
/**
 * Created by PhpStorm.
 * User: Chris
 * Date: 31/03/14
 * Time: 3:32 PM
 */

// No direct access
if ( ! defined( 'ABSPATH' ) ) exit;

require_once dirname( __FILE__ ) . '/interface-logger.php';

class CTCI_Logger implements CTCI_LoggerInterface {

	public static $INFO = 1;
	public static $WARNING = 2;
	public static $ERROR = 4;
	public static $SUCCESS = 8;

	protected $messages = array();

	protected $hasWarnings = false;
	protected $hasErrors = false;

	protected $filter;

	protected $outputExceptionDetails = false;

	public function __construct() {
		$this->filter = static::$INFO | static::$WARNING | static::$ERROR | static::$SUCCESS;
		$this->hasErrors = false;
		$this->hasWarnings = false;
	}

	public function includeExceptions( $include = true ) {
		$this->outputExceptionDetails = $include;
	}

	public function clear() {
		$this->messages = array();
		$this->hasErrors = false;
		$this->hasWarnings = false;
		return $this;
	}

	public function info( $message ) {
		$this->messages[] = array(
			static::$INFO,
			$message
		);
		return $this;
	}

	public function warning( $message, Exception $exception = null ) {
		$this->messages[] = array(
			static::$WARNING,
			$message,
			$exception
		);
		$this->hasWarnings = true;
		return $this;
	}

	public function error( $message, Exception $exception = null ) {
		$this->messages[] = array(
			static::$ERROR,
			$message,
			$exception
		);
		$this->hasErrors = true;
		return $this;
	}

	public function success( $message ) {
		$this->messages[] = array(
			static::$SUCCESS,
			$message
		);
		return $this;
	}

	public function filter( $flags ) {
		$this->filter = $flags;
		return $this;
	}

	public function toHTML() {
		$output = '';
		foreach ( $this->messages as $message ) {
			$output .= '<p>';
			switch ( $message[0] ) {
				case static::$ERROR:
					if ( $this->filter & static::$ERROR ) {
						$output .= '<span style="color: red">Error:</span> ';
						$output .= $message[1];
						if ( $this->outputExceptionDetails && $message[2] instanceof \Exception ) {
							$output .= ' Exception Details: ';
							$output .= $this->getExceptionStringAsHTML( $message[2] );
						}
					}
					break;
				case static::$WARNING:
					if ( $this->filter & static::$WARNING ) {
						$output .= '<span style="color: orange">Warning:</span> ';
						$output .= $message[1];
						if ( $this->outputExceptionDetails && $message[2] instanceof \Exception ) {
							$output .= ' Exception Details: ';
							$output .= $this->getExceptionStringAsHTML( $message[2] );
						}
					}
					break;
				case static::$INFO:
					if ( $this->filter & static::$INFO ) {
						$output .= '<span style="color: blue">Info:</span> ';
						$output .= $message[1];
					}
					break;
				case static::$SUCCESS:
					if ( $this->filter & static::$SUCCESS ) {
						$output .= '<span style="color: green">Success:</span> ';
						$output .= $message[1];
					}
					break;
			}
			$output .= '</p>';
		}
		return $output;
	}

	protected function getExceptionStringAsHTML( Exception $exception ) {
		$str = sprintf(
			"<br /><br />Type: %s, File: %s, Line: %s, Message: %s,<br /><br />
			<span style=\"font-weight: bold\">Trace:</span> %s<br /><br />
			<span style=\"font-weight: bold\">ToString:</span> %s<br /><br />",
			get_class( $exception ), $exception->getFile(), $exception->getLine(), $exception->getMessage(),
			$exception->getTraceAsString(), (string) $exception
		);
		return $str;
	}

	protected function getExceptionString( Exception $exception ) {
		$str = sprintf(
			'Type: %s, File: %s, Line: %s, Message: %s,
			Trace: %s
			ToString: %s',
			get_class( $exception ), $exception->getFile(), $exception->getLine(), $exception->getMessage(),
			$exception->getTraceAsString(), (string) $exception
		);
		return $str;
	}

	public function toString() {
		return $this->__toString();
	}

	public function __toString() {
		$output = '';
		foreach ( $this->messages as $message ) {
			switch ( $message[0] ) {
				case static::$ERROR:
					if ( $this->filter & static::$ERROR ) {
						$output .= 'Error: ';
						$output .= $message[1];
						if ( $this->outputExceptionDetails && $message[2] instanceof \Exception ) {
							$output .= ' Exception Details: ';
							$output .= $this->getExceptionString( $message[2] );
						}
					}
					break;
				case static::$WARNING:
					if ( $this->filter & static::$WARNING ) {
						$output .= 'Warning: ';
						$output .= $message[1];
						if ( $this->outputExceptionDetails && $message[2] instanceof \Exception ) {
							$output .= ' Exception Details: ';
							$output .= $this->getExceptionString( $message[2] );
						}
					}
					break;
				case static::$INFO:
					if ( $this->filter & static::$INFO ) {
						$output .= 'Info: ';
						$output .= $message[1];
					}
					break;
				case static::$SUCCESS:
					if ( $this->filter & static::$SUCCESS ) {
						$output .= 'Success: ';
						$output .= $message[1];
					}
					break;
			}
			$output .= PHP_EOL;
		}
		return $output;
	}

	public function hasWarnings() {
		return $this->hasWarnings;
	}

	public function hasErrors() {
		return $this->hasErrors;
	}
}