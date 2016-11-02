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

class ChoicesField extends \Joomla\Form\Field\ListField {

    protected $type = 'Choices';

    protected function getInput() {

        $text = $this->getText();

        $options = [
            'addItems'       => false,
            'removeItems'    => false,
            'shouldSort'     => false,
            'noResultsText'  => $text->translate("APP_GLOBAL_NO_RESULT", ['jsSafe' => true]),
            'loadingText'    => $text->translate("APP_GLOBAL_LOADING", ['jsSafe' => true]),
            'noChoicesText'  => $text->translate("APP_GLOBAL_NO_CHOISES", ['jsSafe' => true]),
            'itemSelectText' => $text->translate("APP_GLOBAL_SELECT_OPTION", ['jsSafe' => true])
        ];

        if ($this->element['maxItemCount']) {
            $options['maxItemCount'] = (int) $this->element['maxItemCount'];
        }

        if ($this->element['addItems']) {
            $options['addItems'] = ((string) $this->element['addItems'] == 'true');
        }

        if ($this->element['removeItems']) {
            $options['removeItems'] = ((string) $this->element['removeItems'] == 'true');
        }

        if ($this->element['removeItemButton']) {
            $options['removeItemButton'] = ((string) $this->element['removeItemButton'] == 'true');
        }

        if ($this->element['editItems']) {
            $options['editItems'] = ((string) $this->element['editItems'] == 'true');
        }

        if ($this->element['duplicateItems']) {
            $options['duplicateItems'] = ((string) $this->element['duplicateItems'] == 'true');
        }

        if ($this->element['delimiter']) {
            $options['delimiter'] = (string) $this->element['delimiter'];
        }

        if ($this->element['paste']) {
            $options['paste'] = ((string) $this->element['paste'] == 'true');
        }

        if ($this->element['search']) {
            $options['search'] = ((string) $this->element['search'] == 'true');
        }

        if ($this->element['searchFloor']) {
            $options['searchFloor'] = (int) $this->element['searchFloor'];
        }

        if ($this->element['flip']) {
            $options['flip'] = ((string) $this->element['flip'] == 'true');
        }

        if ($this->element['regexFilter']) {
            $options['regexFilter'] = (string) $this->element['regexFilter'];
        }

        if ($this->element['shouldSort']) {
            $options['shouldSort'] = ((string) $this->element['shouldSort'] == 'true');
        }

        if ($this->element['placeholder']) {
            $options['placeholder'] = ((string) $this->element['placeholder'] == 'true');
        }

        if ($this->element['placeholderValue']) {
            $options['placeholderValue'] = (string) $this->element['placeholderValue'];
        }

        if ($this->element['prependValue']) {
            $options['prependValue'] = (string) $this->element['prependValue'];
        }

        if ($this->element['appendValue']) {
            $options['appendValue'] = (string) $this->element['appendValue'];
        }

        if ($this->element['loadingText']) {
            $options['loadingText'] = $text->translate((string) $this->element['loadingText'], ['jsSafe' => true]);
        }

        if ($this->element['noResultsText']) {
            $options['noResultsText'] = $text->translate((string) $this->element['noResultsText'], ['jsSafe' => true]);
        }

        if ($this->element['noChoicesText']) {
            $options['noChoicesText'] = $text->translate((string) $this->element['noChoicesText'], ['jsSafe' => true]);
        }

        if ($this->element['itemSelectText']) {
            $options['itemSelectText'] = $text->translate((string) $this->element['itemSelectText'], ['jsSafe' => true]);
        }

        $modules = "choices";
        if (($this->element['includeCSS'] && ((string) $this->element['includeCSS'] != 'false')) || !$this->element['includeCSS']) {
            $modules .= ", css!/css/vendor/choices.min.css";
        }

        (new RequireJSUtility())
            ->addRequireJSModule("choices", "js/vendor/choices")
            ->addDomReadyJS("new choices(document.getElementById('". $this->id . "'), " . json_encode($options) . ");", false, $modules);

        return parent::getInput();
    }

}
