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

class CodeField extends TextareaField {

    protected function getInput() {

        $options = [];

        // module de base
        $module = "codemirror";

        if (isset($this->element['mode'])) {
            $mode = (string) $this->element['mode'];
            if ($mode == "html") {
                $mode = "xml";
            }
            $options['mode'] = $mode;
            $module .= ", codemirror/mode/".$mode."/".$mode;
        }

        if (isset($this->element['lineSeparator'])) {
            $options['lineSeparator'] = (string) $this->element['lineSeparator'];
        }

        if (isset($this->element['theme'])) {
            $options['theme'] = (string) $this->element['theme'];
        }

        if (isset($this->element['indentUnit'])) {
            $options['indentUnit'] = (int) $this->element['indentUnit'];
        }

        if (isset($this->element['smartIndent'])) {
            $options['smartIndent'] = ((string) $this->element['smartIndent'] == 'true');
        }

        if (isset($this->element['tabSize'])) {
            $options['tabSize'] = (int) $this->element['tabSize'];
        }

        if (isset($this->element['indentWithTabs'])) {
            $options['indentWithTabs'] = ((string) $this->element['indentWithTabs'] == 'true');
        }

        if (isset($this->element['electricChars'])) {
            $options['electricChars'] = ((string) $this->element['electricChars'] == 'true');
        }

        if (isset($this->element['specialChars'])) {
            $options['specialChars'] = (string) $this->element['specialChars'];
        }

        if (isset($this->element['rtlMoveVisually'])) {
            $options['rtlMoveVisually'] = ((string) $this->element['rtlMoveVisually'] == 'true');
        }

        if (isset($this->element['keyMap'])) {
            $keyMap = (string) $this->element['keyMap'];
            $options['keyMap'] = $keyMap;
            $module .= ", codemirror/keymap/" . $keyMap;
        }

        if (isset($this->element['extraKeys'])) {
            $options['extraKeys'] = (string) $this->element['extraKeys'];
        }

        if (isset($this->element['lineWrapping'])) {
            $options['lineWrapping'] = ((string) $this->element['lineWrapping'] == 'true');
        }

        if (isset($this->element['lineNumbers'])) {
            $options['lineNumbers'] = ((string) $this->element['lineNumbers'] == 'true');
        }

        if (isset($this->element['firstLineNumber'])) {
            $options['firstLineNumber'] = (int) $this->element['firstLineNumber'];
        }

        if (isset($this->element['fixedGutter'])) {
            $options['fixedGutter'] = ((string) $this->element['fixedGutter'] == 'true');
        }

        if (isset($this->element['scrollbarStyle'])) {
            $options['scrollbarStyle'] = (string) $this->element['scrollbarStyle'];
        }

        if (isset($this->element['coverGutterNextToScrollbar'])) {
            $options['coverGutterNextToScrollbar'] = ((string) $this->element['coverGutterNextToScrollbar'] == 'true');
        }

        if (isset($this->element['inputStyle'])) {
            $options['inputStyle'] = (string) $this->element['inputStyle'];
        }

        if (isset($this->element['readonly'])) {
            $options['readOnly'] = (string) $this->element['readonly'];
        }

        if (isset($this->element['showCursorWhenSelecting'])) {
            $options['showCursorWhenSelecting'] = ((string) $this->element['showCursorWhenSelecting'] == 'true');
        }

        if (isset($this->element['lineWiseCopyCut'])) {
            $options['lineWiseCopyCut'] = ((string) $this->element['lineWiseCopyCut'] == 'true');
        }

        if (isset($this->element['undoDepth'])) {
            $options['undoDepth'] = (int) $this->element['undoDepth'];
        }

        if (isset($this->element['historyEventDelay'])) {
            $options['historyEventDelay'] = (int) $this->element['historyEventDelay'];
        }

        if (isset($this->element['tabindex'])) {
            $options['tabindex'] = (int) $this->element['tabindex'];
        }

        if (isset($this->element['autofocus'])) {
            $options['autofocus'] = ((string) $this->element['autofocus'] == 'true');
        }

        if (isset($this->element['autoCloseTags'])) {
            $options['autoCloseTags'] = ((string) $this->element['autoCloseTags'] == 'true');
            $module .= ", codemirror/addon/edit/closetag";
        }

        if (isset($this->element['matchTags'])) {
            $options['matchTags'] = ((string) $this->element['matchTags'] == 'true');
            $module .= ", codemirror/addon/edit/matchtags";
        }

        if (isset($this->element['matchBrackets'])) {
            $options['matchBrackets'] = ((string) $this->element['matchBrackets'] == 'true');
            $module .= ", codemirror/addon/edit/matchbrackets";
        }

        if (isset($this->element['autoCloseBrackets'])) {
            $options['autoCloseBrackets'] = ((string) $this->element['autoCloseBrackets'] == 'true');
            $module .= ", codemirror/addon/edit/closebrackets";
        }

        if (isset($this->element['newlineAndIndentContinueMarkdownList'])) {
            $options['newlineAndIndentContinueMarkdownList'] = ((string) $this->element['newlineAndIndentContinueMarkdownList'] == 'true');
            $module .= ", codemirror/addon/edit/continuelist";
        }

        if (isset($this->element['toggleComment'])) {
            $options['toggleComment'] = ((string) $this->element['toggleComment'] == 'true');
            $module .= ", codemirror/addon/comment/comment";
        }

        if (isset($this->element['foldCode']) && $this->element['foldCode'] == 'true') {
            $module .= ", codemirror/addon/fold/foldcode";
        }

        if (isset($this->element['foldGutter'])) {
            $options['foldGutter'] = ((string) $this->element['foldGutter'] == 'true');
            $module .= ", codemirror/addon/fold/foldgutter";
        }

        if (isset($this->element['hint'])) {
            $hint = (string) $this->element['hint'];
            $module .= ", codemirror/addon/hint/show-hint, codemirror/addon/hint/" . $hint . "-hint";
            $options["extraKeys"] = array(
                "Ctrl-Space" => "autocomplete"
            );
        }

        $js = "codemirror.fromTextArea(document.getElementById('" . $this->id . "')";
        if (!empty($options)) {
            $js .= ", " . json_encode($options);
        }
        $js .= ");";

        $requirejs = new RequireJSUtility();
        $requirejs->addRequirePackage("codemirror", "js/vendor/codemirror", "lib/codemirror");

        // feuille css à charger dynamiquement
        $module .= ", css!/js/vendor/codemirror/lib/codemirror.css";
        $requirejs->requireJS($module, $js);

        return parent::getInput();

    }

}