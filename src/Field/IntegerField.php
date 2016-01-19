<?php
/**
 * Part of the ETD Framework Form Package
 *
 * @copyright   Copyright (C) 2015 ETD Solutions, SARL Etudoo. Tous droits réservés.
 * @license     Apache License 2.0; see LICENSE
 * @author      ETD Solutions http://etd-solutions.com
 */

namespace EtdSolutions\Form\Field;

class IntegerField extends MaskedTextField {

    /**
     * The form field type.
     *
     * @var    string
     */
    protected $type = 'Integer';

    protected function getInput() {

        // On force quelques paramètres.
        $this->element["alias"]              = "integer";
        $this->element["autoUnmask"]         = "true";
        $this->element["unmaskAsNumber"]     = "true";
        $this->element["removeMaskOnSubmit"] = "true";

        return parent::getInput();
    }

}
