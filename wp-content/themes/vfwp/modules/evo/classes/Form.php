<?php

namespace Evo;

class Form{

	/**
	 * Returns an HTML button
	 * @param  string $text
	 * @param  object $options
	 * @return string [ HTML ]
	 */
	public static function button( $text, $options ){
		$html = array();

		$html[] = "<button ";

		foreach( $options as $attribute => $value ){
			$html[] = "{$attribute}='${value}' ";
		}

		$html[] = ">";

		$html[] = "{$text}</button>";

		return implode('', $html);
	}

	/**
	 * Returns an HTML checkbox
	 * @param  Array  $arr - requires "fieldName", "data" as returned by the TypeBuilder
	 * @return string [ HTML ]
	 */
	public static function checkbox( Array $arr, $suppress = false ){
		$name = $arr["fieldName"];
		$value = $arr["data"];
		$label = isset($arr["label"]) ? $arr["label"] : '';
		$checked = $value === 'true' ? 'checked' : '';

		if( ! $suppress )
			return "<label class='custom-field-label checkbox-label'><span class='label-text'>{$label}</span><input id='{$name}' type='checkbox' name='{$name}' value='{$value}' {$checked}></label>";
		else
			return "<input id='{$name}' type='checkbox' name='{$name}' value='{$value}' {$checked}>";
	}

	/**
	 * Returns a hidden HTML input
	 * @param  Array  $arr - requires "fieldName", "data" as returned by the TypeBuilder
	 * @return string [ HTML ]
	 */
	public static function hidden( Array $arr ){
		$name = $arr["fieldName"];
		$value = htmlentities($arr["data"]);

		return "<input id='{$name}' type='hidden' name='{$name}' value='{$value}' />";
	}

	public static function multiselect( $arr, $options ){
		$name = $arr["fieldName"];
		$value = $arr["data"];
		$label = $arr["label"];

		$html = array();

		$html[] = "<div class='multiselect-wrap'>";

		$html[] = "<label class='custom-field-label multiselect-label'>
					<span class='label-text'>{$label}</span>
					<input type='hidden' name='{$name}' value='{$value}' data-receiver='{$name}'/>
				</label>";

		$html[] = "<div class='multiselect-box' data-transmitter='{$name}'>";

			foreach( $options as $key => $value ){
				$html[] = "<div class='select-option' data-value='{$key}'>{$value}</div>";
			}

		$html[] = "</div></div>";

		return implode('', $html);
	}

	/**
	 * Returns an HTML input
	 * @param  Array  $arr - requires "fieldName", "data" as returned by the TypeBuilder
	 * @return string [ HTML ]
	 */
	public static function input( Array $arr , $type = 'text'){
		$name = $arr["fieldName"];
		$value = $arr["data"];

		return "<input id='{$name}' type='{$type}' name='{$name}' value='{$value}' />";
	}

	/**
	 * Return an HTML radio element
	 * @param  Array  $arr
	 * @param  Array  $options - options list to be rendered for the radio
	 * @return string [ HTML ]
	 */
	public static function radio( Array $arr, $options ){
		$name = $arr["fieldName"];
		$value = $arr["data"];

		$html = array();

		$html[] = "<fieldset class='radio-group'><h4>" . $arr['label'] . "</h4>";

		foreach( $options as $opt ){
			$checked = strtolower($value) === strtolower($opt) ? 'checked' : '';
			$html[] = "<label><span class='label-wrapper'>{$opt}</span><input type='radio' name='{$name}' value='{$value}' {$checked}/></label>";
		}

		$html[] = "</fieldset>";

		return implode( '', $html ) ;
	}

	/**
	 * Returns an HTML textarea
	 * @param  Array  $arr - requires "fieldName", "data" as returned by the TypeBuilder
	 * @return string [ HTML ]
	 */
	public static function textarea( Array $arr ){
		$name = $arr["fieldName"];
		$value = $arr["data"];

		return "<textarea id='{$name}' name='{$name}'>{$value}</textarea>";
	}

	/**
	 * [wysiwyg description]
	 * @param  Array  $arr - requires "fieldName", "data" as returned by the TypeBuilder
	 * @param  Array  $settings - TinyMCE settings to merge with the editor
	 * @return string [ HTML - TinyMCE Editor ]
	 */
	public static function wysiwyg( Array $arr, Array $settings = array() ){
		$content = $arr["data"];
		$editor_id = $arr["fieldName"];

		ob_start();
			wp_editor( $content, $editor_id, $settings );

			$editor = ob_get_contents();
		ob_end_clean();

		return $editor;
	}
}