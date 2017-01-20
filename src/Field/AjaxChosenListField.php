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

class AjaxChosenListField extends \Joomla\Form\Field\ListField {

    protected $type = 'AjaxChosenList';

	protected function getInput() {

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

		$ajaxOptions = [
			"lookingForMsg" => "Recherche de",
			"keepTypingMsg" => "Continuez à taper...",
			"type"          => "POST",
			"url"           => (string) $this->element["url"],
			"dataType"      => "json",
			"data"          => [
				$this->form->getApplication()->getFormToken() => "1"
			]
		];

		if ($this->element["minTermLength"]) {
			$ajaxOptions["minTermLength"] = (int) $this->element["minTermLength"];
		}

		if ($this->element["afterTypeDelay"]) {
			$ajaxOptions["afterTypeDelay"] = (int) $this->element["afterTypeDelay"];
		}

		if ($this->element["jsonTermKey"]) {
			$ajaxOptions["jsonTermKey"] = (string) $this->element["jsonTermKey"];
		}

        if ($this->element['dataCallback']) {
            $ajaxOptions['dataCallback'] = (string) $this->element['dataCallback'];
        }

        // Data dans le XML.
        foreach ($this->element->children() as $node) {
		    if ($node->getName() == 'data') {
                foreach ($node->children() as $child) {
                    $ajaxOptions['data'][(string)$child['name']] = (string) $child['value'];
                }
            }
        }

		$callback = "function(d){var r=[];$.each(d,function(i,v){r.push({value:v.value,text:v.text});});return r}";
		if (isset($this->element["callback"])) {
			$callback = (string) $this->element["callback"];
		}

        $options = str_replace(['"##STARTFUNC##', '##ENDFUNC##"'], "", json_encode($options));
        $options = str_replace('##Q##', '"', $options);
        $options = str_replace('##SLASH##', '/', $options);
        $options = str_replace("\\\\", "\\", $options);

        $ajaxOptions = str_replace(['"##STARTFUNC##', '##ENDFUNC##"'], "", json_encode($ajaxOptions));
        $ajaxOptions = str_replace('##Q##', '"', $ajaxOptions);
        $ajaxOptions = str_replace('##SLASH##', '/', $ajaxOptions);
        $ajaxOptions = str_replace("\\\\", "\\", $ajaxOptions);

		(new RequireJSUtility())
			->addRequireJSModule("chosen", "js/vendor/chosen.min", true, array("jquery"))
			->addRequireJSModule("ajaxchosen", "js/vendor/ajax-chosen.min", true, array("jquery", "chosen"))
			->addDomReadyJS("$('#" . $this->id . "').ajaxChosen(" . $ajaxOptions . ", " . $callback . ", " . $options . ");", false, "ajaxchosen, css!/css/vendor/chosen.min.css");

		if ($this->element['class']) {
			$this->element['class'] .= " chosen-select with-ajax";
		} else {
			$this->element['class'] = "chosen-select with-ajax";
		}

		return parent::getInput();
	}

}
