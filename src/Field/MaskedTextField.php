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
use Joomla\Form\Field\TextField;

class MaskedTextField extends TextField {

    /**
     * The form field type.
     *
     * @var    string
     */
    protected $type = 'MaskedText';

    protected function getInput() {

        $mask = $this->element['mask'];
        $mask = $this->getText()->translate($mask);

        if (!empty($mask)) {

            $options = array();

            $js = "$('#" . $this->id . "').mask('" . addslashes($mask) . "'";

            if (!empty($this->element['placeholder'])) {
                $options['placeholder'] = (string)$this->element['placeholder'];
            }

            if (!empty($this->element['autoclear'])) {
                $options['autoclear'] = ($this->element['autoclear'] == "true");
            } else {
                $options['autoclear'] = false;
            }

            if(!empty($options)) {
                $js .= "," . json_encode($options);
            }

            $js .= ");";

            (new RequireJSUtility())
                ->addDomReadyJS($js, false, "js/vendor/jquery.maskedinput.min");
        }

        return parent::getInput();
    }

}
