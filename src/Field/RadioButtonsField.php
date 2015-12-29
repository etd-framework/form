<?php
/**
 * Part of the ETD Framework Form Package
 *
 * @copyright   Copyright (C) 2015 ETD Solutions, SARL Etudoo. Tous droits réservés.
 * @license     Apache License 2.0; see LICENSE
 * @author      ETD Solutions http://etd-solutions.com
 */

namespace EtdSolutions\Form\Field;

use Joomla\Form\Field\RadioField;

class RadioButtonsField extends RadioField {

    /**
     * The form field type.
     *
     * @var    string
     */
    protected $type = 'RadioButtons';

    protected function getInput() {

        $html = array();

        // Initialize some field attributes.
        $class       = $this->element['class'] ? ' ' . (string) $this->element['class'] : '';
        $buttonClass = $this->element['buttonClass'] ? ' ' . (string)$this->element['buttonClass'] : '';

        // Start the radio field output.
        $html[] = '<div class="btn-group' . $class . '" data-toggle="buttons">';

        // Get the field options.
        $options = $this->getOptions();

        // Build the radio field output.
        foreach ($options as $i => $option) {
            // Initialize some option attributes.
            $checked = ((string) $option->value == (string) $this->value) ? ' checked="checked"' : '';
            $active  = ((string) $option->value == (string) $this->value) ? ' active' : '';
            $class = !empty($option->class) ? ' class="' . $option->class . '"' : '';
            $disabled = (!empty($option->disable) || $this->element['disabled']) ? ' disabled="disabled"' : '';

            // Initialize some JavaScript option attributes.
            $onclick = !empty($option->onclick) ? ' onclick="' . $option->onclick . '"' : '';

            $html[] = '<label class="btn' . $buttonClass . $active . '" for="' . $this->id . $i . '">';

            $html[] = '<input type="radio" id="' . $this->id . $i . '" name="' . $this->name . '"' . ' value="'
                . htmlspecialchars($option->value, ENT_COMPAT, 'UTF-8') . '"  autocomplete="off"' . $checked . $class . $onclick . $disabled . '>';

            $html[] = ' ' . $option->text;

            $html[] = '</label>';
        }

        // End the radio field output.
        $html[] = '</div>';

        return implode($html);
    }

}
