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

class Select2ListField extends \Joomla\Form\Field\ListField {

    protected $type = 'Select2List';

    protected function getInput() {

        $options = [];

        if (isset($this->element['templateResult'])) {
            $options['templateResult'] = (string) $this->element['templateResult'];
        }

        if (isset($this->element['placeholder'])) {
            $options['placeholder'] = (string) $this->element['placeholder'];
        }

        if (isset($this->element['tags'])) {
            $options['tags'] = (bool) ((string) $this->element['tags'] == "true");
        }

        if (isset($this->element['allowClear'])) {
            $options['allowClear'] = (bool) ((string) $this->element['allowClear'] == "true");
        }

        $options = json_encode($options);
        $options = str_replace(['"##STARTFUNC##', '##ENDFUNC##"'], "", $options);
        $options = str_replace("\\\\", "\\", $options);

        (new RequireJSUtility())
            ->addRequirePackage('select2', 'js/vendor/select2', 'select2.min')
            ->addDomReadyJS("$('#" . $this->id . "').select2(" . $options . ");", false, "select2");

        return parent::getInput();
    }

}
