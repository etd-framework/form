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

        $locale  = $this->element['locale'] ? (string) $this->element['locale'] : (new LanguageFactory())->getLanguage()->get('iso');
        $format  = $this->element['format'] ? (string) $this->element['format'] : 'L';
        $minDate = $this->element['minDate'] ? (string) $this->element['minDate'] : null;
        $maxDate = $this->element['maxDate'] ? (string) $this->element['maxDate'] : null;
        $class   = $this->element['class'] ? ' ' . (string) $this->element['class'] . '"' : '';
        $dayOnly = $this->element['dayOnly'] ? ((string) $this->element['dayOnly'] == "true") : false;

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
        if (!empty($this->value) && $this->value != $this->form->getDb()->getNullDate()) {

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

    $js .= ".format('YYYY-MM-DD HH:mm:ss'))
    else $('#" . $this->id . "').val('');
});
$('#" . $this->id . "_btn').on('click', function() {
    $('#" . $this->id . "_picker').data('DateTimePicker').show();
});";

        (new RequireJSUtility())
            ->addRequireJSModule("moment", "js/vendor/moment.min", true, array("jquery"))
            ->addRequireJSModule("momentfr", "js/vendor/moment.fr", true, array("moment"))
            ->addRequireJSModule("bsdatetimepicker", "js/vendor/bootstrap-datetimepicker.min", true, array("jquery", "bootstrap", "momentfr"))
            ->addDomReadyJS($js, false, "bsdatetimepicker, css!/css/vendor/bootstrap-datetimepicker.min.css");

        $html = array();

        $html[] = '<div class="input-group date' . $class . '">';
        $html[] = '<input type="text" id="' . $this->id . '_picker" class="form-control">';
        $html[] = '<span class="input-group-addon" id="' . $this->id . '_btn">';
        $html[] = '<span class="fa fa-calendar"></span>';
        $html[] = '</span>';
        $html[] = '</div>';
        $html[] = '<input type="hidden" id="' . $this->id . '" name="' . $this->name . '" value="' . htmlspecialchars($this->value) . '">';

        return implode($html);

    }

}