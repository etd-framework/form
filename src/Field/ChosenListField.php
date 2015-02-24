<?php
/**
 * Part of the ETD Framework Form Package
 *
 * @copyright   Copyright (C) 2015 ETD Solutions, SARL Etudoo. Tous droits réservés.
 * @license     Apache License 2.0; see LICENSE
 * @author      ETD Solutions http://etd-solutions.com
 */

namespace EtdSolutions\Form\Field;

class ChosenListField extends \Joomla\Form\Field\ListField {

    protected $type = 'ChosenList';

    protected function getInput() {

        $doc = \EtdSolutions\Document\Document::getInstance();

        $min = ".min";
        if (JDEBUG) {
            $min = "";
        }

        $doc->addRequireJSModule("chosen", "vendor/etdsolutions/chosen/chosen".$min, true, array("jquery"))
            ->addDomReadyJS("$('.chosen-select').chosen();", false, "chosen, css!vendor/etdsolutions/chosen/chosen".$min.".css");

        if ($this->element['class']) {
            $this->element['class'] .= " chosen-select";
        } else {
            $this->element['class'] = "chosen-select";
        }

        return parent::getInput();
    }

}
