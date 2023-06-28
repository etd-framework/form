<?php
/**
 * Part of the ETD Framework Form Package
 *
 * @copyright   Copyright (C) 2015 ETD Solutions, SARL Etudoo. Tous droits réservés.
 * @license     Apache License 2.0; see LICENSE
 * @author      ETD Solutions http://etd-solutions.com
 */

namespace EtdSolutions\Form\Field;

use EtdSolutions\Language\LanguageFactory;
use EtdSolutions\Utility\DateUtility;
use EtdSolutions\Utility\RequireJSUtility;
use Joomla\Form\Field;

class DateField extends Field {

    /**
     * The name of the form field.
     *
     * @var    string
     * @since  1.0
     */
    protected $name = 'Date';

    /**
     * Method to get the field input markup.
     *
     * @return  string  The field input markup.
     *
     * @since   1.0
     */
    protected function getInput() {

        $js = "";

        $locale   = $this->element['locale'] ? (string) $this->element['locale'] : (new LanguageFactory())->getLanguage()->get('iso');
        $format   = $this->element['format'] ? (string) $this->element['format'] : 'L';
        $valueFormat = $this->element['valueFormat'] ? (string) $this->element['valueFormat'] : 'YYYY-MM-DD HH:mm:ss';
        $minDate  = $this->element['minDate'] ? (string) $this->element['minDate'] : null;
        $maxDate  = $this->element['maxDate'] ? (string) $this->element['maxDate'] : null;
        $placeholder  = $this->element['placeholder'] ? ' placeholder="' . htmlspecialchars((string) $this->element['placeholder']) . '"' : null;
        $class    = $this->element['class'] ? ' ' . (string) $this->element['class'] : '';
        $dayOnly  = $this->element['dayOnly'] ? ((string) $this->element['dayOnly'] == "true") : false;
        $readonly = ((string) $this->element['readonly'] == 'true') ? ' readonly="readonly"' : '';
        $disabled = ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';

        $options = array(
            'locale'  => $locale,
            'format'  => $format,
            'icons' => array(
                'time' => 'fa fa-clock-o',
                'date' => 'fa fa-calendar',
                'up' => 'fa fa-chevron-up',
                'down' => 'fa fa-chevron-down',
                'previous' => 'fa fa-chevron-left',
                'next' => 'fa fa-chevron-right',
                'today' => 'fa fa-dot-circle-o',
                'clear' => 'fa fa-trash',
                'close' => 'fa fa-times'
            )
        );

	    if ($this->element['showClose']) {
		    $options['showClose'] = ($this->element['showClose'] == "true");
	    }

	    if ($this->element['showClear']) {
		    $options['showClear'] = ($this->element['showClear'] == "true");
	    }

	    if ($this->element['showTodayButton']) {
		    $options['showTodayButton'] = ($this->element['showTodayButton'] == "true");
	    }

	    if ($this->element['calendarWeeks']) {
		    $options['calendarWeeks'] = ($this->element['calendarWeeks'] == "true");
	    }

	    if ($this->element['useCurrent']) {
		    $options['useCurrent'] = ($this->element['useCurrent'] == "true");
	    }

	    if ($this->element['collapse']) {
		    $options['collapse'] = ($this->element['collapse'] == "true");
	    }

	    if ($this->element['collapse']) {
		    $options['collapse'] = ($this->element['collapse'] == "true");
	    }

	    if (isset($this->element['stepping'])) {
		    $options["stepping"] = (string) $this->element['stepping'];
	    }

	    if (isset($this->element['viewMode'])) {
		    $options["viewMode"] = (string) $this->element['viewMode'];
	    }

	    if (isset($this->element['viewDate'])) {
		    $options["viewDate"] = (string) $this->element['viewDate'];
	    }

	    if (isset($this->element['toolbarPlacement'])) {
		    $options["toolbarPlacement"] = (string) $this->element['toolbarPlacement'];
	    }

        if (isset($this->element['default'])) {
            $options["defaultDate"] = (string) $this->element['default'];
        }

        if (isset($this->element['widgetParent'])) {
            $options["widgetParent"] = (string) $this->element['widgetParent'];
        }

        if (isset($this->element['disabledDates'])) {
	    	if (is_array($this->element['disabledDates'])) {
			    $options["disabledDates"] = (array) $this->element['disabledDates'];
		    } else {
			    $options["disabledDates"] = explode(",", (string) $this->element['disabledDates']);
		    }
        }

	    if (isset($this->element['enabledDates'])) {
		    if (is_array($this->element['enabledDates'])) {
			    $options["enabledDates"] = (array) $this->element['enabledDates'];
		    } else {
			    $options["enabledDates"] = explode(",", (string) $this->element['enabledDates']);
		    }
	    }

	    if (isset($this->element['daysOfWeekDisabled'])) {
		    if (is_array($this->element['daysOfWeekDisabled'])) {
			    $options["daysOfWeekDisabled"] = (array) $this->element['daysOfWeekDisabled'];
		    } else {
			    $options["daysOfWeekDisabled"] = explode(",", (string) $this->element['daysOfWeekDisabled']);
		    }
	    }

        if (isset($this->element['sideBySide'])) {
            $options["sideBySide"] = ((string) $this->element['sideBySide'] == "true");
        }

        if (isset($this->element['useStrict'])) {
            $options["useStrict"] = ((string) $this->element['useStrict'] == "true");
        }

        if (isset($this->element['keepOpen'])) {
            $options["keepOpen"] = ((string) $this->element['keepOpen'] == "true");
        }

        if (isset($this->element['inline'])) {
            $options["inline"] = ((string) $this->element['inline'] == "true");
        }

        if (isset($this->element['keepInvalid'])) {
            $options["keepInvalid"] = ((string) $this->element['keepInvalid'] == "true");
        }

        if (isset($this->element['debug'])) {
            $options["debug"] = ((string) $this->element['debug'] == "true");
        }

        if (isset($this->element['ignoreReadonly'])) {
            $options["ignoreReadonly"] = ((string) $this->element['ignoreReadonly'] == "true");
        }

        if (isset($this->element['allowInputToggle'])) {
            $options["allowInputToggle"] = ((string) $this->element['allowInputToggle'] == "true");
        }

        if (isset($this->element['focusOnShow'])) {
            $options["focusOnShow"] = ((string) $this->element['focusOnShow'] == "true");
        }

        if (isset($minDate)) {
            if (strpos($minDate, 'field:') !== false) {

                $fieldName = substr($minDate, 6);
                $field     = $this->form->getField($fieldName);

                if ($field !== false) {

                    $js .= "$('#" . $field->id . "_picker').on('dp.change', function(e) {
    if (e.oldDate) {
        $('#" . $this->id . "_picker').data('DateTimePicker').minDate(e.date);
    }
});\n";

                }

            } else {
                $options['minDate'] = $minDate;
            }
        }

        if (isset($maxDate)) {
            if (strpos($maxDate, 'field:') !== false) {

                $fieldName = substr($maxDate, 6);
                $field     = $this->form->getField($fieldName);

                if ($field !== false) {

                    $js .= "$('#" . $field->id . "_picker').on('dp.change', function(e) {
    if (e.oldDate) {
        $('#" . $this->id . "_picker').data('DateTimePicker').maxDate(e.date);
    }
});\n";

                }

            } else {
                $options['maxDate'] = $maxDate;
            }
        }

        // Si on a une valeur.
        if (!empty($this->value) && $this->value != $this->form->getDb()->getNullDate() && $this->value != '0000-00-00') {

            // La date venant du système est en UTC.
            // On la formate avec le fuseau horaire de l'utilisateur.
            $value = (new DateUtility($this->form->getApplication()->get('timezone')))->format($this->value, 'APP_GLOBAL_DATE_ISO');
            $options['defaultDate'] = $value;

        }

        $js .= "$('#" . $this->id . "_picker').datetimepicker(" . json_encode($options) .").on('dp.change', function(e) {
    if (e.date) $('#" . $this->id . "').val(e.date";

        if ($dayOnly) {
            $js .= ".startOf('day')";
        } else {
            $js .= ".utc()";
        }

    $js .= ".format('" . $valueFormat . "'))
    else $('#" . $this->id . "').val('');
    $('#" . $this->id . "').trigger('change');
});
$('#" . $this->id . "_btn').on('click', function() {
    $('#" . $this->id . "_picker').data('DateTimePicker').show();
});";

        (new RequireJSUtility())
            ->addRequireJSModule("moment", "js/vendor/moment.min", true, array("jquery"))
            ->addRequireJSModule("bsdatetimepicker", "js/vendor/bootstrap-datetimepicker.min", true, array("jquery", "bootstrap", "moment"))
            ->addDomReadyJS($js, false, "bsdatetimepicker, css!/css/vendor/bootstrap-datetimepicker.min.css");

        $html = array();

        $html[] = '<div class="input-group date' . $class . '">';
        $html[] = '<input type="text" id="' . $this->id . '_picker" class="form-control"' . $readonly . $placeholder . $disabled . '>';
        $html[] = '<span class="input-group-addon" id="' . $this->id . '_btn">';
        $html[] = '<span class="fa fa-calendar"></span>';
        $html[] = '</span>';
        $html[] = '</div>';
        $html[] = '<input type="hidden" id="' . $this->id . '" name="' . $this->name . '" value="' . htmlspecialchars($this->value) . '">';

        return implode($html);

    }

}
