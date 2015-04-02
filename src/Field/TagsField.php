<?php
/**
 * Part of the ETD Framework Form Package
 *
 * @copyright   Copyright (C) 2015 ETD Solutions, SARL Etudoo. Tous droits réservés.
 * @license     Apache License 2.0; see LICENSE
 * @author      ETD Solutions http://etd-solutions.com
 */

namespace EtdSolutions\Form\Field;

use EtdSolutions\Utility\RequireJSUtility;
use Joomla\Form\Html\Select as HtmlSelect;

class TagsField extends \Joomla\Form\Field {

    protected $type = 'Tags';

    protected function getInput() {

        $html = [];
        $attr = '';

        $select = new HtmlSelect;

        // Try to inject the text object into the field
        try {
            $select->setText($this->getText());
        } catch (\RuntimeException $exception) {
            // A Text object was not set, ignore the error and try to continue processing
        }

        // Initialize some field attributes.
        $attr .= $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';

        // To avoid user's confusion, readonly="true" should imply disabled="true".
        if ((string) $this->element['readonly'] == 'true' || (string) $this->element['disabled'] == 'true')
        {
            $attr .= ' disabled="disabled"';
        }

        $attr .= $this->element['size'] ? ' size="' . (int) $this->element['size'] . '"' : '';

        // Initialize JavaScript field attributes.
        $attr .= $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';

        $js = "var \$input = $('#".$this->id."');
\$input.tagsinput();
\$input.on('itemAdded itemRemoved', function(e) {
    $('input[name=\"" . $this->name . "\"]').val(json.stringify(\$input.tagsinput('items')));
})";

        // On charge le JS.
        (new RequireJSUtility())
            ->addRequireJSModule("bootstraptagsinput", "js/vendor/bootstrap-tagsinput", true, array("jquery"))
            ->addRequireJSModule("json", "etdsolutions/utils/json")
            ->addDomReadyJS($js, false, "bootstraptagsinput, etdsolutions/utils/json");

        $attr .= ' multiple';

        // Get the field options.
		$options = (array) $this->getOptions();

        $value = $this->value;

        if (is_array($value)) {
            $value = json_encode($value);
        }

        $html[] = $select->genericlist($options, '', trim($attr), 'value', 'text', null, $this->id);
        $html[] = '<input type="hidden" name="' . $this->name . '" value="' . htmlspecialchars($value) . '">';

        return implode($html);
    }


    /**
     * Method to get the field options.
     *
     * @return  array  The field option objects.
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

            $text = $this->translateOptions
                ? $this->getText()->alt(trim((string) $option), preg_replace('/[^a-zA-Z0-9_\-]/', '_', $this->fieldname))
                : trim((string) $option);

            // Create a new option object based on the <option /> element.
            $tmp = $select->option((string) $option['value'], $text, 'value', 'text', ((string) $option['disabled'] == 'true'));

            // Set some option attributes.
            $tmp->class = (string) $option['class'];

            // Set some JavaScript option attributes.
            $tmp->onclick = (string) $option['onclick'];

            // Add the option object to the result set.
            $options[] = $tmp;
        }

        if ($this->value) {
            $values = $this->value;

            if (is_string($values)) {
                $values = json_decode($values);
            }

            if ($values) {
                foreach ($values as $value) {
                    $options[] = $select->option($value, $value, 'value', 'text');
                }
            }
        }

        reset($options);

        return $options;
    }

}
