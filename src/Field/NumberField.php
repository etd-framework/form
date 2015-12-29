<?php
/**
 * Part of the ETD Framework Form Package
 *
 * @copyright   Copyright (C) 2015 ETD Solutions, SARL Etudoo. Tous droits réservés.
 * @license     Apache License 2.0; see LICENSE
 * @author      ETD Solutions http://etd-solutions.com
 */

namespace EtdSolutions\Form\Field;

use EtdSolutions\Utility\LocaleUtility;

class NumberField extends MaskedTextField {

    /**
     * The form field type.
     *
     * @var    string
     */
    protected $type = 'Number';

    protected function getInput() {

        // On récupère les infos de localisation.
        $localeconv = (new LocaleUtility())->localeconv();

        // On force quelques paramètres.
        $this->element["alias"]              = "numeric";
        $this->element["radixPoint"]         = $localeconv['mon_decimal_point'];
        $this->element["groupSeparator"]     = $localeconv['mon_thousands_sep'];
        $this->element["digits"]             = $localeconv['frac_digits'];
        $this->element["autoUnmask"]         = "true";
        $this->element["unmaskAsNumber"]     = "true";
        $this->element["removeMaskOnSubmit"] = "true";

        return parent::getInput();
    }

}
