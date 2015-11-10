<?php
/**
 * Part of the ETD Framework Form Package
 *
 * @copyright   Copyright (C) 2015 ETD Solutions, SARL Etudoo. Tous droits réservés.
 * @license     Apache License 2.0; see LICENSE
 * @author      ETD Solutions http://etd-solutions.com
 */

namespace EtdSolutions\Form\Field;

use Joomla\Form\Html\Select as HtmlSelect;

/**
 * Checkboxes Form Field class for the Joomla! Framework.
 *
 * Displays options as a list of check boxes. Multiselect may be forced to be true.
 *
 * @see    \Joomla\Form\Field\CheckboxField
 * @since  1.0
 */
class InlineCheckboxesField extends \Joomla\Form\Field {

    /**
     * The form field type.
     *
     * @var    string
     * @since  1.0
     */
    protected $type = 'InlineCheckboxes';

    /**
     * Flag to tell the field to always be in multiple values mode.
     *
     * @var    boolean
     * @since  1.0
     */
    protected $forceMultiple = true;

    /**
     * Method to get the field input markup for check boxes.
     *
     * @return  string  The field input markup.
     *
     * @since   1.0
     */
    protected function getInput() {

        $html = array();

        // Initialize some field attributes.
        $class = $this->element['class'] ? ' class="checkbox-group ' . (string) $this->element['class'] . '"' : ' class="checkbox-group"';

        // Start the radio field output.
        $html[] = '<span id="' . $this->id . '"' . $class . '>';

        // Get the field options.
        $options = $this->getOptions();

        // Build the radio field output.
        foreach ($options as $i => $option) {
            // Initialize some option attributes.
            $value = !is_array($this->value) ? explode(',', $this->value) : $this->value;
            $checked = (in_array((string) $option->value, $value) ? ' checked="checked"' : '');
            $class = !empty($option->class) ? ' class="' . $option->class . '"' : '';
            $disabled = !empty($option->disable) ? ' disabled="disabled"' : '';

            // Initialize some JavaScript option attributes.
            $onclick = !empty($option->onclick) ? ' onclick="' . $option->onclick . '"' : '';

            $html[] = '<label class="checkbox-inline" for="' . $this->id . $i . '">';

            $html[] = '<input type="checkbox" id="' . $this->id . $i . '" name="' . $this->name . '"' . ' value="'
                . htmlspecialchars($option->value, ENT_COMPAT, 'UTF-8') . '"' . $checked . $class . $onclick . $disabled . '>';

            $html[] = ' ' . $option->text;

            $html[] = '</label>';
        }

        // End the radio field output.
        $html[] = '</span>';

        return implode($html);
    }

    /**
     * Method to get the field options.
     *
     * @return  array  The field option objects.
     *
     * @since   1.0
     */
    protected function getOptions() {

        $options = array();

        $select = new HtmlSelect;

        // Try to inject the text object into the field
        try {
            $select->setText($this->getText());
        } catch (\RuntimeException $exception) {
            // A Text object was not set, ignore the error and try to continue processing
        }

        /** @var \SimpleXMLElement $option */
        foreach ($this->element->children() as $option) {
            // Only add <option /> elements.
            if ($option->getName() != 'option') {
                continue;
            }

            // Create a new option object based on the <option /> element.
            $tmp = $select->option((string)$option['value'], trim((string)$option), 'value', 'text', ((string)$option['disabled'] == 'true'));

            // Set some option attributes.
            $tmp->class = (string)$option['class'];

            // Set some JavaScript option attributes.
            $tmp->onclick = (string)$option['onclick'];

            // Add the option object to the result set.
            $options[] = $tmp;
        }

        reset($options);

        return $options;
    }
}
