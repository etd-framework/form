<?php
/**
 * Part of the ETD Framework Form Package
 *
 * @copyright   Copyright (C) 2015-2016 ETD Solutions. Tous droits réservés.
 * @license     Apache License 2.0; see LICENSE
 * @author      ETD Solutions http://etd-solutions.com
 */

namespace EtdSolutions\Form\Field;

use EtdSolutions\Language\LanguageFactory;
use EtdSolutions\Utility\DateUtility;
use EtdSolutions\Utility\RequireJSUtility;
use Joomla\Form\Field;

class DateTimeField extends Field {

    /**
     * The name of the form field.
     *
     * @var    string
     * @since  1.0
     */
    protected $name = 'DateTime';

    /**
     * Method to get the field input markup.
     *
     * @return  string  The field input markup.
     *
     * @since   1.0
     */
    protected function getInput() {

        $js = [];

        $defaultAltFormat = 'd/m/Y';
        $defaultFormat = 'Y-m-d';

        if ($this->element['enableTime'] == "true") {
            $defaultAltFormat .= ' H:i';
            $defaultFormat    .= ' H:i';
        }

        if ($this->element['enableSeconds'] == "true") {
            $defaultAltFormat .= ':S';
            $defaultAltFormat .= ':S';
        }

        $class        = $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';
        $placeholder  = $this->element['placeholder'] ? ' placeholder="' . htmlspecialchars((string) $this->element['placeholder']) . '"' : null;
        $altFormat    = $this->element['displayFormat'] ? (string) $this->element['displayFormat'] : $defaultAltFormat;

        $options = [
            "altInput"   => true,
            "altFormat"  => $this->getText()->translate($altFormat),
            "utc"        => true,
            "time_24hr"  => true,
            "dateFormat" => $defaultFormat
        ];

        if ($this->element['class']) {
            $options["altInputClass"] = (string) $this->element['class'];
        }

        if ($this->element['allowInput']) {
            $options['allowInput'] = ($this->element['allowInput'] == "true");
        }

        if ($this->element['dateFormat']) {
            $options['dateFormat'] = (string) $this->element['dateFormat'];
        }

        if ($this->element['enableTime']) {
            $options['enableTime'] = ($this->element['enableTime'] == "true");
        }

        if ($this->element['hourIncrement']) {
            $options['hourIncrement'] = (int) $this->element['hourIncrement'];
        }

        if ($this->element['minuteIncrement']) {
            $options['minuteIncrement'] = (int) $this->element['minuteIncrement'];
        }

        if ($this->element['nextArrow']) {
            $options['nextArrow'] = (string) $this->element['nextArrow'];
        }

        if ($this->element['noCalendar']) {
            $options['noCalendar'] = ($this->element['noCalendar'] == "true");
        }

        if ($this->element['prevArrow']) {
            $options['prevArrow'] = (string) $this->element['prevArrow'];
        }

        if ($this->element['shorthandCurrentMonth']) {
            $options['shorthandCurrentMonth'] = ($this->element['shorthandCurrentMonth'] == "true");
        }

        if ($this->element['static']) {
            $options['static'] = ($this->element['static'] == "true");
        }

        if ($this->element['time_24hr']) {
            $options['time_24hr'] = ($this->element['time_24hr'] == "true");
        }

        if ($this->element['utc']) {
            $options['utc'] = ($this->element['utc'] == "true");
        }

        if ($this->element['minDate']) {
            $options['minDate'] = (string) $this->element['minDate'];
        }

        if ($this->element['maxDate']) {
            $options['maxDate'] = (string) $this->element['maxDate'];
        }

        if ($this->element['wrap']) {
            $options['wrap'] = ($this->element['wrap'] == "true");
        }

        if ($this->element['clickOpens']) {
            $options['clickOpens'] = ($this->element['clickOpens'] == "true");
        }

        if ($this->element['inline']) {
            $options['inline'] = ($this->element['inline'] == "true");
        }

        if ($this->element['weekNumbers']) {
            $options['weekNumbers'] = ($this->element['weekNumbers'] == "true");
        }

        if ($this->element['weekNumbers']) {
            $options['weekNumbers'] = ($this->element['weekNumbers'] == "true");
        }

        $js[] = "document.getElementById('" . $this->id . "').flatpickr(" . json_encode($options) . ");";

        (new RequireJSUtility())
            ->addRequireJSModule("flatpickr", "js/vendor/flatpickr.min")
            ->addDomReadyJS(implode("\n", $js), false, "flatpickr, css!/css/vendor/flatpickr.min.css", false);

        return '<input id="' . $this->id . '" type="text"  value="' . htmlspecialchars($this->value, ENT_QUOTES, "UTF-8") . '" ' . $placeholder . $class . '>';

    }

}