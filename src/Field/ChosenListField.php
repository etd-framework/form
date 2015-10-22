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

class ChosenListField extends \Joomla\Form\Field\ListField {

    protected $type = 'ChosenList';

    protected function getInput() {

        $options = [];


        $options = [
            'no_results_text' => $this->getText()->translate("APP_GLOBAL_NO_RESULT", ['jsSafe' => true])
        ];

        $width                 = $this->element['width'] ? (string) $this->element['width'] : null;
        $max_selected_options  = $this->element['maxSelectedOptions'] ? (int) $this->element['maxSelectedOptions'] : null;
        $placeholder           = $this->element["placeholder"] ? (string) $this->element["placeholder"] : "APP_GLOBAL_SELECT_OPTION";

        if (isset($width)) {
            $options['width'] = $width;
        }

        if ($max_selected_options > 0) {
            $options['max_selected_options'] = $max_selected_options;
        }

        if ($this->multiple) {
            $options["placeholder_text_multiple"] = $this->getText()->translate($placeholder, ['jsSafe' => true]);
        } else {
            $options["placeholder_text_single"] = $this->getText()->translate($placeholder, ['jsSafe' => true]);
        }

        if ($this->element["allow_single_deselect"]) {
            $options['allow_single_deselect'] = (string) $this->element["allow_single_deselect"] == "true";
        }


        (new RequireJSUtility())
            ->addRequireJSModule("chosen", "js/vendor/chosen.min", true, array("jquery"))
            ->addDomReadyJS("$('#" . $this->id . "').chosen(" . json_encode($options) . ");", false, "chosen, css!/css/vendor/chosen.min.css");

        if ($this->element['class']) {
            $this->element['class'] .= " chosen-select";
        } else {
            $this->element['class'] = "chosen-select";
        }

        return parent::getInput();
    }

}
