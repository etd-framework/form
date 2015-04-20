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
use EtdSolutions\Utility\RequireJSUtility;
use Joomla\Form\Field\TextField;

class DateField extends TextField {

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

        $language    = $this->element['language'] ? (string) $this->element['language'] : (new LanguageFactory())->getLanguage()->get('iso');
        $format      = $this->element['format'] ? (string) $this->element['format'] : 'dd/mm/yyyy';
        $orientation = $this->element['orientation'] ? (string) $this->element['orientation'] : 'default';


        $options = array(
            'language'    => $language,
            'format'      => $format,
            'orientation' => $orientation
        );

        (new RequireJSUtility())
            ->addRequireJSModule("bsdatepicker", "js/vendor/bootstrap-datepicker.min", true, array("jquery"))
            ->addRequireJSModule("bsdatepickerfr", "js/vendor/bootstrap-datepicker.fr.min", true, array("bsdatepicker"))
            ->addDomReadyJS("$('#" . $this->id . "').datepicker(" . json_encode($options) .")", false, "bsdatepicker, bsdatepickerfr");

        if ($this->element['class']) {
            $this->element['class'] .= ' date-picker';
        } else {
            $this->element['class'] = 'date-picker';
        }

        return parent::getInput();

    }

}