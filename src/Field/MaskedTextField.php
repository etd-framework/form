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

        $options = array();

        if (!empty($this->element['mask'])) {
            $mask = (string) $this->element['mask'];
            $mask = $this->getText()->translate($mask);
            $options['mask'] = $mask;
        }

        if (!empty($this->element['alias'])) {
            $options['alias'] = (string) $this->element['alias'];
        }

        if (!empty($this->element['placeholder'])) {
            $options['placeholder'] = (string)$this->element['placeholder'];
        }

        if (!empty($this->element['repeat'])) {
            $options['repeat'] = (int)$this->element['repeat'];
        }

        if (!empty($this->element['suffix'])) {
            $options['suffix'] = (string)$this->element['suffix'];
        }

        if (!empty($this->element['digits'])) {
            $options['digits'] = (int)$this->element['digits'];
        }

        if (isset($this->element['greedy'])) {
            $options['greedy'] = ($this->element['greedy'] == "true");
        }

        if (isset($this->element['autoGroup'])) {
            $options['autoGroup'] = ($this->element['autoGroup'] == "true");
        }

        if (isset($this->element['autoUnmask'])) {
            $options['autoUnmask'] = ($this->element['autoUnmask'] == "true");
        }

        if (isset($this->element['removeMaskOnSubmit'])) {
            $options['removeMaskOnSubmit'] = ($this->element['removeMaskOnSubmit'] == "true");
        }

        if (isset($this->element['insertMode'])) {
            $options['insertMode'] = ($this->element['insertMode'] == "true");
        }

        if (isset($this->element['clearIncomplete'])) {
            $options['clearIncomplete'] = ($this->element['clearIncomplete'] == "true");
        }

        if (isset($this->element['showMaskOnFocus'])) {
            $options['showMaskOnFocus'] = ($this->element['showMaskOnFocus'] == "true");
        }

        if (isset($this->element['showMaskOnHover'])) {
            $options['showMaskOnHover'] = ($this->element['showMaskOnHover'] == "true");
        }

        if (isset($this->element['numericInput'])) {
            $options['numericInput'] = ($this->element['numericInput'] == "true");
        }

        if (isset($this->element['rightAlign'])) {
            $options['rightAlign'] = ($this->element['rightAlign'] == "true");
        }

        if (isset($this->element['undoOnEscape'])) {
            $options['undoOnEscape'] = ($this->element['undoOnEscape'] == "true");
        }

        if (isset($this->element['radixPoint'])) {
            $options['radixPoint'] = (string) $this->element['radixPoint'];
        }

        if (isset($this->element['radixFocus'])) {
            $options['radixFocus'] = ($this->element['radixFocus'] == "true");
        }

        if (isset($this->element['nojumps'])) {
            $options['nojumps'] = ($this->element['nojumps'] == "true");
        }

        if (isset($this->element['digitsOptional'])) {
            $options['digitsOptional'] = ($this->element['digitsOptional'] == "true");
        }

        if (isset($this->element['nojumpsThreshold'])) {
            $options['nojumpsThreshold'] = (int) $this->element['nojumpsThreshold'];
        }

        if (isset($this->element['keepStatic'])) {
            $options['keepStatic'] = ($this->element['keepStatic'] == "true");
        }

        if (isset($this->element['canClearPosition'])) {
            $options['canClearPosition'] = ($this->element['canClearPosition'] == "true");
        }

        (new RequireJSUtility())
            ->addRequireMap('*', 'jQuery', 'jquery')
            ->addRequireJSModule("jquery.inputmask", "js/vendor/inputmask/jquery.inputmask.min")
            ->addRequireJSModule("inputmask", "js/vendor/inputmask/inputmask.min")
            ->addRequireJSModule("dependencyLib", "js/vendor/inputmask/dependencyLib.min")
            ->addDomReadyJS("$('#" . $this->id . "').inputmask(" . json_encode($options) . ");", false, "jquery.inputmask, js/vendor/inputmask/inputmask.numeric.extensions.min");

        return parent::getInput();
    }

}
