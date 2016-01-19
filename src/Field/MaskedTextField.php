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
use Joomla\Form\Field;

class MaskedTextField extends Field {

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

        if (!empty($this->element['maskPlaceholder'])) {
            $options['placeholder'] = (string)$this->element['maskPlaceholder'];
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

        if (isset($this->element['unmaskAsNumber'])) {
            $options['unmaskAsNumber'] = ($this->element['unmaskAsNumber'] == "true");
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
            ->addRequireJSModule("jquery.inputmask", "js/vendor/inputmask/jquery.inputmask.min", true, ["jquery"])
            ->addRequireJSModule("inputmask", "js/vendor/inputmask/inputmask.min")
            ->addRequireJSModule("inputmask.dependencyLib", "js/vendor/inputmask/inputmask.dependencyLib.jquery.min")
            ->addDomReadyJS("$('#" . $this->id . "').inputmask(" . json_encode($options) . ");", false, "jquery.inputmask, js/vendor/inputmask/inputmask.numeric.extensions.min");

        // Initialize some field attributes.
        $size = $this->element['size'] ? ' size="' . (int) $this->element['size'] . '"' : '';
        $maxLength = $this->element['maxlength'] ? ' maxlength="' . (int) $this->element['maxlength'] . '"' : '';
        $class = $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';
        $readonly = ((string) $this->element['readonly'] == 'true') ? ' readonly="readonly"' : '';
        $disabled = ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';

        // Temporary workaround to make sure the placeholder can be set without coupling to joomla/language
        $placeholder = '';

        if ($this->element['placeholder'])
        {
            try
            {
                $placeholder = ' placeholder="' . $this->getText()->translate((string) $this->element['placeholder']) . '"';
            }
            catch (\RuntimeException $e)
            {
                $placeholder = ' placeholder="' . (string) $this->element['placeholder'] . '"';
            }
        }

        // Initialize JavaScript field attributes.
        $onchange = $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';

        return '<input type="text" name="' . $this->name . '" id="' . $this->id . '"' . ' value="'
        . htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '"' . $class . $size . $disabled . $readonly . $onchange . $maxLength . $placeholder . '/>';
    }

}
