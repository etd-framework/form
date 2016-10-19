<?php
/**
 * Part of the ETD Framework Form Package
 *
 * @copyright   Copyright (C) 2015 ETD Solutions, SARL Etudoo. Tous droits réservés.
 * @license     Apache License 2.0; see LICENSE
 * @author      ETD Solutions http://etd-solutions.com
 */

namespace EtdSolutions\Form\Field;

class InlineRadioField extends \Joomla\Form\Field\RadioField {

    /**
     * The form field type.
     *
     * @var    string
     * @since  1.0
     */
    protected $type = 'InlineRadio';

    protected function getInput() {

        $html = array();

        // Initialize some field attributes.
        $class           = $this->element['class'] ? ' class="radio-group ' . (string) $this->element['class'] . '"' : ' class="radio-group"';
        $radiolabelclass = $this->element['radiolabelclass'] ? (string) $this->element['radiolabelclass'] : 'radio-inline';

        // Start the radio field output.
        $html[] = '<span id="' . $this->id . '"' . $class . '>';

        // Get the field options.
        $options = $this->getOptions();

        // Build the radio field output.
        foreach ($options as $i => $option) {
            // Initialize some option attributes.
            $checked = ((string) $option->value == (string) $this->value) ? ' checked="checked"' : '';
            $class = !empty($option->class) ? ' class="' . $option->class . '"' : '';
            $disabled = (!empty($option->disable) || $this->element['disabled']) ? ' disabled="disabled"' : '';

            // Initialize some JavaScript option attributes.
            $onclick = !empty($option->onclick) ? ' onclick="' . $option->onclick . '"' : '';

            $html[] = '<label class="' . $radiolabelclass . '" for="' . $this->id . $i . '">';

            $html[] = '<input type="radio" id="' . $this->id . $i . '" name="' . $this->name . '"' . ' value="'
                . htmlspecialchars($option->value, ENT_COMPAT, 'UTF-8') . '"' . $checked . $class . $onclick . $disabled . '>';

            $html[] = ' ' . $option->text;

            $html[] = '</label>';
        }

        // End the radio field output.
        $html[] = '</span>';

        return implode($html);
    }
}
