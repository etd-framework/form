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
use Joomla\Form\Html\Select as HtmlSelect;

class ChoicesField extends \Joomla\Form\Field\ListField {

    protected $type = 'Choices';

    protected function getInput() {

        $text = $this->getText();

        $addItemText = "APP_GLOBAL_ADD_OPTION";
        if ($this->element['addItemText']) {
            $addItemText = (string) $this->element['addItemText'];
        }

        $options = [
            'removeItems'    => false,
            'shouldSort'     => false,
            'noResultsText'  => $text->translate("APP_GLOBAL_NO_RESULT", ['jsSafe' => true]),
            'loadingText'    => $text->translate("APP_GLOBAL_LOADING", ['jsSafe' => true]),
            'noChoicesText'  => $text->translate("APP_GLOBAL_NO_CHOISES", ['jsSafe' => true]),
            'itemSelectText' => $text->translate("APP_GLOBAL_SELECT_OPTION", ['jsSafe' => true]),
            'addItemText'    => '##STARTFUNC##function(v){return \'' . $text->sprintf($addItemText, "'+v+'", ['jsSafe' => true]) . '\'}##ENDFUNC##',
        ];

        if ($this->element['class']) {
            $this->element['class'] = (string) $this->element['class'] . " choices-select";
        } else {
            $this->element['class'] = "choices-select";
        }

        if ($this->element['maxItemCount']) {
            $options['maxItemCount'] = (int) $this->element['maxItemCount'];
        }

        if ($this->element['addItems']) {
            $options['addItems'] = ((string) $this->element['addItems'] == 'true');
            $this->element['disable'] = ((string) $this->element['addItems'] == 'true') ? "true" : "false";
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

        if ($this->element['searchEnabled']) {
            $options['searchEnabled'] = ((string) $this->element['searchEnabled'] == 'true');
        }

        if ($this->element['searchChoices']) {
            $options['searchChoices'] = ((string) $this->element['searchChoices'] == 'true');
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

            // On triche pour le placeholder sur le select.
            $this->element['class'] = (string) $this->element['class'] . '" placeholder="' . htmlspecialchars((string) $this->element['placeholderValue'], ENT_QUOTES, "UTF-8");
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

        if ($this->element['containerClass']) {
            $options['classNames'] = [
                'containerOuter' => (string) $this->element['containerClass']
            ];
        }

        if ($this->element['callbackOnInit']) {
            $options['callbackOnInit'] = (string) $this->element['callbackOnInit'];
        }

        if ($this->element['callbackOnAddItem']) {
            $options['callbackOnAddItem'] = (string) $this->element['callbackOnAddItem'];
        }

        if ($this->element['callbackOnRemoveItem']) {
            $options['callbackOnRemoveItem'] = (string) $this->element['callbackOnRemoveItem'];
        }

        if ($this->element['callbackOnHighlightItem']) {
            $options['callbackOnHighlightItem'] = (string) $this->element['callbackOnHighlightItem'];
        }

        if ($this->element['callbackOnUnhighlightItem']) {
            $options['callbackOnUnhighlightItem'] = (string) $this->element['callbackOnUnhighlightItem'];
        }

        if ($this->element['callbackOnChange']) {
            $options['callbackOnChange'] = (string) $this->element['callbackOnChange'];
        }

        if ($this->element['callbackOnSearch']) {
            $options['callbackOnSearch'] = (string) $this->element['callbackOnSearch'];
        }

        if ($this->element['callbackOnCreateTemplates']) {
            $options['callbackOnCreateTemplates'] = (string) $this->element['callbackOnCreateTemplates'];
        }

        $modules = "choices";
        if (($this->element['includeCSS'] && ((string) $this->element['includeCSS'] != 'false')) || !$this->element['includeCSS']) {
            $modules .= ", css!/css/vendor/choices.min.css";
        }

        $options = str_replace(['"##STARTFUNC##', '##ENDFUNC##"'], "", json_encode($options));
        $options = str_replace('##Q##', '"', $options);
        $options = str_replace('##SLASH##', '/', $options);
        $options = str_replace("\\\\", "\\", $options);

        (new RequireJSUtility())
            ->addRequireJSModule("choices", "js/vendor/choices.min")
            ->addDomReadyJS("window.choicesInstances = window.choicesInstances || {}; window.choicesInstances['". $this->id . "'] = new choices(document.getElementById('". $this->id . "'), " . $options . ");", false, $modules, false);

        return parent::getInput();
    }

}
