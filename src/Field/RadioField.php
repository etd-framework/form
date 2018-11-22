<?php
/**
 * Part of the ETD Framework Form Package
 *
 * @copyright   Copyright (C) 2016 ETD Solutions, SARL Etudoo. Tous droits réservés.
 * @license     Apache License 2.0; see LICENSE
 * @author      ETD Solutions http://etd-solutions.com
 */

namespace EtdSolutions\Form\Field;

use Joomla\Form\Field\RadioField as JRadioField;

class RadioField extends JRadioField {

    /**
     * The form field type.
     *
     * @var    string
     */
    protected $type = 'Radio';


    protected function getOptions() {
        $options = array();

        /** @var \SimpleXMLElement $option */
        foreach ($this->element->children() as $option) {
            // Only add <option /> elements.
            if ($option->getName() != 'option') {
                continue;
            }

            $text = $this->translateOptions
                ? $this->getText()->alt(trim((string) $option), preg_replace('/[^a-zA-Z0-9_\-]/', '_', $this->fieldname))
                : trim((string) $option);

            // Set up option elements.
            $tmp = new \stdClass;
            $tmp->value = (string) $option['value'];
            $tmp->text = $text;
            $tmp->disable = ((string) $option['disabled'] == 'true');
            $tmp->class = (string) $option['class'];
            $tmp->onclick = (string) $option['onclick'];
            $tmp->tooltip = (string) $option['tooltip'];

            // Add the option object to the result set.
            $options[] = $tmp;
        }

        reset($options);

        return $options;
    }

}
