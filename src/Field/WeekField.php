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

class WeekField extends Field {

    /**
     * The name of the form field.
     *
     * @var    string
     * @since  1.0
     */
    protected $name = 'Week';

    /**
     * Method to get the field input markup.
     *
     * @return  string  The field input markup.
     *
     * @since   1.0
     */
    protected function getInput() {

        $js = [];

        $class        = $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';
        $placeholder  = $this->element['placeholder'] ? ' placeholder="' . htmlspecialchars((string) $this->element['placeholder']) . '"' : null;

        $options = [];

	    if ($this->value) {
	    	$options['initialDate'] = "##FUNC##moment('".$this->value."', 'X')##FUNC##";
	    } elseif ($this->element['initialDate']) {
		    $options['initialDate'] = "##FUNC##moment('".(string) $this->element['initialDate']."', 'X')##FUNC##";
	    }

	    if ($this->element['endDate']) {
		    $options['endDate'] = "##FUNC##moment('". (string) $this->element['endDate']."')##FUNC##";
	    }

	    if ($this->element['onChange']) {
		    $options['onChange'] = "##FUNC##" . (string) $this->element['onChange'] . "##FUNC##";
	    }

	    if ($this->element['valueFormat']) {
		    $options['valueFormat'] = "##FUNC##" . (string) $this->element['valueFormat'] . "##FUNC##";
	    }

	    $str = str_replace(['"##FUNC##', '##FUNC##"'], "", json_encode($options));

        $js[] = "var wp = new weekpicker('#" . $this->id . "', " . $str . ");$('#" . $this->id . "').data('weekpicker', wp);";

        (new RequireJSUtility())
            ->addRequireJSModule("moment", "js/vendor/moment.min", true)
            ->addRequireJSModule("weekpicker", "js/vendor/week-picker.min", false, ["jquery", "moment"])
            ->addDomReadyJS(implode("\n", $js), false, "moment, weekpicker, css!/css/vendor/week-picker.min.css", false);

	    $value = $this->value;
	    if (empty($value)) {
	    	$value = time();
	    }

	    return '<div class="input-group week-picker">'
                . '<input type="text" readonly id="' . $this->id . '" value="' . date('\SW Y', $value) . '"' . $placeholder . $class . '>'
                . '<span class="input-group-addon" id="' . $this->id . '_btn"><span class="fa fa-calendar"></span></span>'
             . '</div>';


    }

}