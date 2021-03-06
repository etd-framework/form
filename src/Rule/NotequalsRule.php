<?php
/**
 * Part of the ETD Framework Form Package
 *
 * @copyright   Copyright (C) 2015 ETD Solutions, SARL Etudoo. Tous droits réservés.
 * @license     Apache License 2.0; see LICENSE
 * @author      ETD Solutions http://etd-solutions.com
 */

namespace EtdSolutions\Form\Rule;

use Joomla\Form\Form;
use Joomla\Form\Rule;
use Joomla\Registry\Registry;

/**
 * Not Equals Form Rule class.
 *
 * Validates that two values are not equal.
 */
class NotEqualsRule extends Rule {

	/**
	 * Method to test if two values are not equal. To use this rule, the form
	 * XML needs a validate attribute of equals and a field attribute
	 * that is not equal to the field to test against.
	 *
	 * @param   \SimpleXMLElement  $element  The SimpleXMLElement object representing the <field /> tag for the form field object.
	 * @param   mixed              $value    The form field value to validate.
	 * @param   string             $group    The field name group control value. This acts as as an array container for the field.
	 *                                       For example if the field has name="foo" and the group value is set to "bar" then the
	 *                                       full field name would end up being "bar[foo]".
	 * @param   Registry           $input    An optional Registry object with the entire data set to validate against the entire form.
	 * @param   Form               $form     The form object for which the field is being tested.
	 *
	 * @return  boolean  True if the value is valid, false otherwise.
	 *
	 * @since   1.0
	 * @throws  \InvalidArgumentException
	 * @throws  \UnexpectedValueException
	 */
	public function test(\SimpleXMLElement $element, $value, $group = null, Registry $input = null, Form $form = null) {
		$field = (string) $element['field'];

		// Check that a validation field is set.
		if (!$field) {
			throw new \UnexpectedValueException(sprintf('$field empty in %s::test', get_class($this)));
		}

		if (is_null($input)) {
			throw new \InvalidArgumentException(sprintf('The value for $input must not be null in %s', get_class($this)));
		}

		// Test the two values against each other.
		if ($value != $input->get($field)) {
			return true;
		}

		return false;
	}
}
