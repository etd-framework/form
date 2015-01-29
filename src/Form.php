<?php
/**
 * Part of the ETD Framework Form Package
 *
 * @copyright   Copyright (C) 2015 ETD Solutions, SARL Etudoo. Tous droits réservés.
 * @license     Apache License 2.0; see LICENSE
 * @author      ETD Solutions http://etd-solutions.com
 */

namespace EtdSolutions\Form;

use EtdSolutions\Application\Web;
use Joomla\Form\Form as JoomlaForm;
use Joomla\Registry\Registry;

defined('_JEXEC') or die;

/**
 * Extension du formulaire pour ajouter quelques fonctionnalités.
 */
class Form extends JoomlaForm {

    public function __construct($name, array $options = array()) {

        parent::__construct($name, $options);

        $app = Web::getInstance();
        $this->setText($app->getText());

    }

    public function getXML() {

        return $this->xml->asXML();
    }

    public function validate($data, $group = null, $fieldName = null) {

        if (is_null($fieldName)) {
            return parent::validate($data, $group);
        }

        if (!is_string($fieldName)) {
            return false;
        }

        // Make sure there is a valid Form XML document.
        if (!($this->xml instanceof \SimpleXMLElement)) {
            return false;
        }

        $return = true;

        $field = $this->findField($fieldName, $group);

        // Create an input registry object from the data to validate.
        $input = new Registry($data);

        $value = null;
        $name  = (string)$field['name'];

        // Get the group names as strings for ancestor fields elements.
        $attrs  = $field->xpath('ancestor::fields[@name]/@name');
        $groups = array_map('strval', $attrs ? $attrs : array());
        $group  = implode('.', $groups);

        // Get the value from the input data.
        if ($group) {
            $value = $input->get($group . '.' . $name);
        } else {
            $value = $input->get($name);
        }

        // Validate the field.
        $valid = $this->validateField($field, $group, $value, $input);

        // Check for an error.
        if ($valid instanceof \Exception) {
            array_push($this->errors, $valid);
            $return = false;
        }

        return $return;

    }

    /**
     * On rend la méthode publique.
     */
    public function &findFieldsByFieldset($name) {

        return parent::findFieldsByFieldset($name);
    }

    /**
     * On rend la méthode publique.
     */
    public function &findFieldsByGroup($group = null, $nested = false) {

        return parent::findFieldsByGroup($group, $nested);
    }

}