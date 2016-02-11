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

        (new RequireJSUtility())
            ->addRequirePackage('select2', 'js/vendor/select2', 'select2.min')
            ->addDomReadyJS("$('#" . $this->id . "').select2({templateResult:function(s){return(s.text.indexOf('||')!==-1?$('<span>'+s.text.split('||').join('</span><span>')+'</span>'):s.text)}});", false, "select2");

        return parent::getInput();
    }

}
