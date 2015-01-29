<?php
/**
 * Part of the ETD Framework Form Package
 *
 * @copyright   Copyright (C) 2015 ETD Solutions, SARL Etudoo. Tous droits réservés.
 * @license     Apache License 2.0; see LICENSE
 * @author      ETD Solutions http://etd-solutions.com
 */

namespace EtdSolutions\Form\Field;

use Joomla\Form\Field;
use Joomla\Registry\Registry;
use SimpleXMLElement;

class RightsField extends Field {

    /**
     * The form field type.
     *
     * @var    string
     */
    protected $type = 'Rights';

    /**
     * Method to get the radio button field input markup.
     *
     * @return  string  The field input markup.
     */
    protected function getInput() {

        // Initialisation.
        $actions = $this->getActions();
        $html = array();
        $value = false;

        if (!empty($this->value)) {
            $value = new Registry($this->value);
        }

        $class = $this->element['class'] ? ' ' . (string)$this->element['class'] : '';
        $readonly = isset($this->element['readonly']) ? (bool)$this->element['readonly'] : false;

        $html[] = '<table class="rights table table-condensed' . $class . '">';

        $html[] = '<thead>';
        $html[] = '<tr>';
        $html[] = '<th>' . $this->getText()->translate('APP_GLOBAL_RIGHTS_HEADING_SECTION') . '</th>';
        $html[] = '<th>' . $this->getText()->translate('APP_GLOBAL_RIGHTS_HEADING_ACTION') . '</th>';
        $html[] = '<th>' . $this->getText()->translate('APP_GLOBAL_RIGHTS_HEADING_RIGHT') . '</th>';
        $html[] = '</tr>';
        $html[] = '</thead>';

        $html[] = '<tbody>';

        // On parcourt les sections.
        foreach ($actions as $section) {

            $rowspan = count($section->actions);

            foreach ($section->actions as $i => $action) {

                $labelClass1 = '';
                $checked1 = '';
                $labelClass0 = ' active';
                $checked0 = ' checked';
                $hasValue = $value && $value->exists($section->name . "." . $action->name);

                if ($hasValue) {
                    $v = $value->get($section->name . "." . $action->name, false);
                    if ($v) {
                        $labelClass1 = ' active';
                        $checked1 = ' checked';
                        $labelClass0 = '';
                        $checked0 = '';
                    } else {
                        $labelClass0 = ' active';
                        $checked0 = ' checked';
                    }
                }

                $html[] = '<tr>';

                if ($i == 0) {
                    $html[] = '<td class="section" rowspan="' . $rowspan . '"><span class="hasTooltip" title="' . $this->getText()->translate($section->description) . '">' . $this->getText()->translate($section->title) . '</td>';
                }

                $html[] = '<td class="action"><span class="hasTooltip" title="' . $this->getText()->translate($action->description) . '">' . $this->getText()->translate($action->title) . '</td>';

                $html[] = '<td class="right">';

                // Lecture seule
                if ($readonly) {
                    if ($hasValue && empty($checked0)) {
                        $html[] = $this->getText()->translate('APP_GLOBAL_YES');
                    } else {
                        $html[] = $this->getText()->translate('APP_GLOBAL_NO');
                    }
                } else {
                    $html[] = '<div class="btn-group" data-toggle="buttons">';
                    $html[] = '<label class="btn btn-default btn-sm' . $labelClass1 . '"><input name="' . $this->name . '[' . $section->name . '][' . $action->name . ']" value="1" type="radio"' . $checked1 . '> ' . $this->getText()->translate('APP_GLOBAL_YES') . '</label>';
                    $html[] = '<label class="btn btn-default btn-sm' . $labelClass0 . '"><input name="' . $this->name . '[' . $section->name . '][' . $action->name . ']" value="0" type="radio"' . $checked0 . '> ' . $this->getText()->translate('APP_GLOBAL_NO') . '</label>';
                    $html[] = '</div>';
                }

                $html[] = '</td>';

                $html[] = '</tr>';

            }

        }


        $html[] = '';
        $html[] = '';
        $html[] = '';
        $html[] = '</tbody>';

        $html[] = '</table>';

        return implode("\n", $html);

    }

    protected function getActions() {

        // On charge les droits depuis le XML.
        $data = simplexml_load_file(JPATH_ROOT . "/rights.xml");

        // On contrôle que les données sont bien chargées.
        if ((!($data instanceof SimpleXMLElement)) && (!is_string($data))) {
            throw new \RuntimeException($this->getText()->translate('APP_ERROR_RIGHTS_NOT_LOADED'));
        }

        // On initialise les actions.
        $result = array();

        // On récupère les sections.
        $sections = $data->xpath("/rights/section");

        if (!empty($sections)) {

            foreach ($sections as $section) {

                $tmp = array(
                    'name' => (string) $section['name'],
                    'title' => (string) $section['title'],
                    'description' => (string) $section['description'],
                    'actions' => array()
                );

                $actions = $section->xpath("action[@name][@title][@description]");

                if (!empty($actions)) {

                    foreach ($actions as $action) {
                        $tmp['actions'][] = (object) array(
                            'name' => (string) $action['name'],
                            'title' => (string) $action['title'],
                            'description' => (string) $action['description']
                        );
                    }

                    $result[] = (object) $tmp;
                }

            }

        }

        return $result;

    }
}
