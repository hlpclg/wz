<?php

class FormTpl
{
	public static function tip($text)
	{
		return "<div class='col-lg-12'><label class='col-xs-12 col-sm-4 col-md-3 col-lg-2 control-label'></label><span class='help-block col-sm-8 col-md-8 col-lg-8 col-xs-12'>{$text}</span></div>";
	}

	public static function label($text)
	{
		return '<label class="col-xs-12 col-sm-4 col-md-3 col-lg-2 control-label">' . $text . '</label>';
	}

	public static function label2($text, $class, $required = false)
	{
		if ($required) {
			$text = '<em style="color:red">*</em>' . $text;
		}
		return '<label class="' . $class . ' control-label">' . $text . '</label>';
	}

	public static function helpblock2($text)
	{
		return '<span class="help-block">' . $text . '</span>';
	}

	public static function text($name, $value, $extra = 6)
	{
		return self::getWebControl('text', $name, $value, $extra);
	}

	public static function plain($value)
	{
		return self::getWebControl('plain', '', $value);
	}

	public static function getWebControl($type, $name, $value, $extra = 1, $width = 1)
	{
		$str = '';
		switch ($type) {
			case 'disabled':
				$str = "<div class='col-sm-8 col-md-8 col-lg-8 col-xs-12'><input class='form-control' type='text' id='{$name}' name='{$name}' value='{$value}' disabled/></div>";
				break;
			case 'text':
			case 'tel':
				$str = "<div class='col-sm-8 col-md-8 col-lg-8 col-xs-12'><input class='form-control' type='text' id='{$name}' name='{$name}' value='{$value}' /></div>";
				break;
			case 'hidden':
				$str .= $value;
				$str = "<input type='hidden' id='{$name}' name='{$name}' value='{$value}' />";
				break;
			case 'time':
				load()->func('tpl');
				$str = "<div class='col-xs-12 col-sm-4'>" . tpl_form_field_date($name, date('Y-m-d H:i', $value)) . "</div>";
				break;
			case 'image':
				load()->func('tpl');
				$str = "<div class='col-xs-12 col-sm-4'>" . tpl_form_field_image($name, $value) . "</div>";
				break;
			case 'textarea':
				$str = "<div class='col-xs-12 col-sm-4'><textarea rows='4' cols='70' class='form-control' type='text' id='{$name}' name='{$name}'>{$value}</textarea></div>";
				break;
			case 'richtext':
				$str = "<div class='col-xs-12 col-sm-8'><textarea class='form-control richtext-clone' type='text' id='{$name}' name='{$name}'>{$value}</textarea></div>";
				break;
			case 'number':
				$str = "<div class='col-xs-12 col-sm-8'><input class='form-control' type='number' id='{$name}' name='{$name}' value='{$value}' step='{$extra}' /></div>";
				break;
			case 'submit':
				$str = "<div class='col-xs-12 col-sm-6 col-lg-4'><input class='btn btn-primary form-control' type='submit' id='{$name}' name='{$name}' value='{$value}' /></div>";
				break;
			case 'select':
			case 'option':
				$options = $extra;
				$str = "<div class='col-xs-12 col-sm-4 col-md-6 col-lg-6'><select style='border:1px solid #cccccc;' id='{$name}' name='{$name}' class='form-control'>";
				foreach ($options as $_key => $_o) {
					$checked = ($_key == $value) ? 'selected' : '';
					$str .= "<option value ='{$_key}' {$checked}>{$_o}</option>";
				}
				$str .= "</select></div>";
				break;
			case 'radio':
				$options = $extra;
				$str = "<div class='col-xs-12 col-sm-4'>";
				foreach ($options as $_key => $_o) {
					$checked = ($_key == $value) ? 'checked' : '';
					$str .= "<label class='radio-inline'><input type='radio' name='{$name}'value='{$_key}' {$checked} />{$_o}</label>";
				}
				$str .= "</div>";
				break;
			case 'plain':
				$str = "<div class='col-sm-8 col-md-8 col-lg-8 col-xs-12'>{$value}</div>";
				break;
			default:
				break;
		}
		return $str;
	}

	public static function getWebControl2($type, $name, $value, $options = array())
	{
		$str = '';
		switch ($type) {
			case 'disabled':
				$str = "<input class='form-control' type='text' id='{$name}' name='{$name}' value='{$value}' disabled/>";
				break;
			case 'text':
			case 'tel':
				$str = "<input class='form-control' type='text' id='{$name}' name='{$name}' value='{$value}' />";
				break;
			case 'hidden':
				$str .= $value;
				$str = "<input type='hidden' id='{$name}' name='{$name}' value='{$value}' />";
				break;
			case 'time':
				load()->func('tpl');
				$str = tpl_form_field_date($name, empty($value) ? date('Y-m-d H:i') : date('Y-m-d H:i', $value), 1);
				break;
			case 'image':
				load()->func('tpl');
				$str = tpl_form_field_image($name, $value);
				break;
			case 'textarea':
				$str = "<textarea rows='4' cols='70' class='form-control' type='text' id='{$name}' name='{$name}'>{$value}</textarea>";
				break;
			case 'richtext':
				$str = "<textarea class='form-control richtext-clone' type='text' id='{$name}' name='{$name}'>{$value}</textarea>";
				break;
			case 'number':
				$str = "<input class='form-control' type='number' id='{$name}' name='{$name}' value='{$value}' />";
				break;
			case 'submit':
				$str = "<input class='btn btn-primary form-control' type='submit' id='{$name}' name='{$name}' value='{$value}' />";
				break;
			case 'select':
			case 'option':
				$str = "<select style='border:1px solid #cccccc;' id='{$name}' name='{$name}' class='form-control'>";
				foreach ($options as $_key => $_o) {
					$checked = ($_key == $value) ? 'selected' : '';
					$str .= "<option value ='{$_key}' {$checked}>{$_o}</option>";
				}
				$str .= "</select>";
				break;
			case 'radio':
				$str = "";
				foreach ($options as $_key => $_o) {
					$checked = ($_key == $value) ? 'checked' : '';
					$str .= "<label class='{$class} radio-inline'><input type='radio' name='{$name}'value='{$_key}' {$checked} />{$_o}</label>";
				}
				break;
			case 'plain':
				$str = $value;
				break;
			default:
				break;
		}
		return $str;
	}

	public static function imagePath($path, $prefix)
	{
		return (strpos($path, 'http://') === FALSE) ? $extra . $path : $path;
	}
} ?>