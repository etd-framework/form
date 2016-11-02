<?php
/**
 * Part of the ETD Framework Form Package
 *
 * @copyright   Copyright (C) 2015 ETD Solutions, SARL Etudoo. Tous droits réservés.
 * @license     Apache License 2.0; see LICENSE
 * @author      ETD Solutions http://etd-solutions.com
 */

namespace EtdSolutions\Form;

use EtdSolutions\Form\Exception\InvalidFieldException;

use Joomla\Application\AbstractApplication;
use Joomla\Database\DatabaseDriver;
use Joomla\DI\ContainerAwareInterface;
use Joomla\DI\ContainerAwareTrait;
use Joomla\Form\Form as JoomlaForm;
use Joomla\Form\FormHelper;
use Joomla\Registry\Registry;

/**
 * Extension du formulaire pour ajouter quelques fonctionnalités.
 */
class Form extends JoomlaForm implements ContainerAwareInterface {

    use ContainerAwareTrait;

    /**
     * @var DatabaseDriver $db
     */
    protected $db;

    /**
     * @var AbstractApplication $app
     */
    protected $app;

    public function setDb(DatabaseDriver $db) {
        $this->db = $db;
    }

    public function setApplication(AbstractApplication $app) {
        $this->app = $app;
    }

    public function getDb() {
        return $this->db;
    }

    public function getApplication() {
        return $this->app;
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

    public function validateField(\SimpleXMLElement $element, $group = null, $value = null, Registry $input = null)
    {
        $valid = true;

        // Check if the field is required.
        $required = ((string) $element['required'] == 'true' || (string) $element['required'] == 'required');

        if ($required)
        {
            // If the field is required and the value is empty return an error message.
            if (($value === '') || ($value === null))
            {
                $translate = true;

                if ($element['label'])
                {
                    $message = $translate ? $this->getText()->translate($element['label']) : $element['label'];
                }
                else
                {
                    $message = $translate ? $this->getText()->translate($element['name']) : $element['name'];
                }

                // TODO - Language strings for our packages should be defined and loaded into the language object
                if ($translate)
                {
                    $message = $this->getText()->sprintf('JLIB_FORM_VALIDATE_FIELD_REQUIRED', $message);
                }
                else
                {
                    $message = sprintf('Field required: %s', $message);
                }

                return new InvalidFieldException($message, null, null, (string) $element['name'], (string) $group);
            }
        }

        // Get the field validation rule.
        if ($type = (string) $element['validate'])
        {
            // Load the Rule object for the field.
            $rule = FormHelper::loadRuleClass($type);

            // If the object could not be loaded return an error message.
            if ($rule === false)
            {
                throw new InvalidFieldException(sprintf('%s::validateField() rule `%s` missing.', get_class($this), $type), null, null, (string) $element['name'], (string) $group);
            }

            // Instantiate the Rule object
            /** @var Rule $rule */
            $rule = new $rule;

            // Run the field validation rule test.
            $valid = $rule->test($element, $value, $group, $input, $this);

            // Check for an error in the validation test.
            if ($valid instanceof \Exception)
            {
                return $valid;
            }
        }

        // Check if the field is valid.
        if ($valid === false)
        {
            // Does the field have a defined error message?
            $message   = (string) $element['message'];
            $translate = true;

            if ($message)
            {
                $message = $translate ? $this->getText()->translate($element['message']) : $element['message'];

                return new InvalidFieldException($message, null, null, (string) $element['name'], (string) $group);
            }
            else
            {
                $message = $translate ? $this->getText()->translate($element['label']) : $element['label'];

                // TODO - Language strings for our packages should be defined and loaded into the language object
                if ($translate)
                {
                    $message = $this->getText()->sprintf('JLIB_FORM_VALIDATE_FIELD_INVALID', $message);
                }
                else
                {
                    $message = sprintf('JLIB_FORM_VALIDATE_FIELD_INVALID', $message);
                }

                return new InvalidFieldException($message, null, null, (string) $element['name'], (string) $group);
            }
        }

        return true;
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