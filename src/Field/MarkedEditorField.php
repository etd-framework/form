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
use Joomla\Form\Field\TextareaField;

class MarkedEditorField extends TextareaField {

    protected function getInput() {

        $options = [
            "autoDownloadFontAwesome" => false,
            "spellChecker"            => false,
            "autofocus"               => false,
            "element"                 => "##STARTELEMENT##".$this->id."##ENDELEMENT##"
        ];

        if (isset($this->element['autoDownloadFontAwesome'])) {
            $options['autoDownloadFontAwesome'] = ((string) $this->element['autoDownloadFontAwesome'] == 'true');
        }

        if (isset($this->element['autofocus'])) {
            $options['autofocus'] = ((string) $this->element['autofocus'] == 'true');
        }

        if (isset($this->element['autosave'])) {
            $options['autosave'] = [
                "enabled"  => ((string) $this->element['autosave'] == 'true'),
                "uniqueId" => $this->id
            ];
        }

        if (isset($this->element['autosave_delay']) && isset($options['autosave'])) {
            $options['autosave']["delay"] = (int) $this->element['autosave_delay'];
        }

        if (isset($this->element['hideIcons'])) {
            $options['hideIcons'] = explode(",", (string) $this->element['hideIcons']);
        }

        if (isset($this->element['lineWrapping'])) {
            $options['lineWrapping'] = ((string) $this->element['lineWrapping'] == 'true');
        }

        if (isset($this->element['placeholder'])) {
            $options['placeholder'] = (string) $this->element['placeholder'];
        }

        if (isset($this->element['showIcons'])) {
            $options['showIcons'] = explode(",", (string) $this->element['showIcons']);
        }

        if (isset($this->element['spellChecker'])) {
            $options['spellChecker'] = ((string) $this->element['spellChecker'] == 'true');
        }

        if (isset($this->element['status'])) {
            $options['status'] = ((string) $this->element['status'] == 'true');
        }

        if (isset($this->element['tabSize'])) {
            $options['tabSize'] = (int) $this->element['tabSize'];
        }

        if (isset($this->element['toolbar'])) {
            if ((string) $this->element['toolbar'] == 'false') {
                $options['toolbar'] = false;
            } else {
                $options['toolbar'] = explode(",", (string) $this->element['toolbar']);
            }
        }

        if (isset($this->element['toolbarTips'])) {
            $options['toolbarTips'] = ((string) $this->element['toolbarTips'] == 'true');
        }

        $options = json_encode($options);
        $options = str_replace(['"##STARTELEMENT##', '##ENDELEMENT##"'], ["document.getElementById('", "')"], $options);

        (new RequireJSUtility())
            ->addRequirePackage("codemirror", "js/vendor/codemirror", "lib/codemirror")
            ->addRequireJSModule("marked", "js/vendor/marked.min")
            ->addRequireJSModule("simplemde", "js/vendor/simplemde.min", false, ["codemirror/codemirror", "marked"])
            ->addDomReadyJS("new simplemde(" . $options . ");", false, "simplemde, css!/css/vendor/simplemde.min.css, css!/js/vendor/codemirror/lib/codemirror.css");

        return parent::getInput();

    }

}