<?php
/**
 * Part of the ETD Framework Form Package
 *
 * @copyright   Copyright (C) 2015 ETD Solutions, SARL Etudoo. Tous droits réservés.
 * @license     Apache License 2.0; see LICENSE
 * @author      ETD Solutions http://etd-solutions.com
 */

namespace EtdSolutions\Form\Field;

class FileField extends \Joomla\Form\Field\FileField {

    /**
     * Method to get the radio button field input markup.
     *
     * @return  string  The field input markup.
     */
    protected function getInput() {

        // Document
        $doc = \EtdSolutions\Document\Document::getInstance();

        // Debug
        $min = ".min";
        if (JDEBUG) {
            $min = "";
        }

        $js = "$('#" . $this->id . "').fileinput();";

        // On ajoute les ressources.
        $doc->addRequireJSModule("fileinput", "vendor/kartik-v/bootstrap-fileinput/js/fileinput".$min, true, array("bootstrap", "jquery"))
            ->addDomReadyJS($js, false, "fileinput, css!vendor/kartik-v/bootstrap-fileinput/css/fileinput" . $min .".css");

    }

}