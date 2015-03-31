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

class ChosenListField extends \Joomla\Form\Field\ListField {

    protected $type = 'ChosenList';

    protected function getInput() {

        (new RequireJSUtility())
            ->addRequireJSModule("chosen", "js/vendor/chosen.min", true, array("jquery"))
            ->addDomReadyJS("$('.chosen-select').chosen();", false, "chosen, css!/css/vendor/chosen.min.css");

        if ($this->element['class']) {
            $this->element['class'] .= " chosen-select";
        } else {
            $this->element['class'] = "chosen-select";
        }

        return parent::getInput();
    }

}
