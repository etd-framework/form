<?php
/**
 * Part of the ETD Framework Form Package
 *
 * @copyright   Copyright (C) 2015 ETD Solutions, SARL Etudoo. Tous droits réservés.
 * @license     Apache License 2.0; see LICENSE
 * @author      ETD Solutions http://etd-solutions.com
 */

namespace EtdSolutions\Form\Rule;

use EtdSolutions\Form\Form;
use Joomla\Crypt\Password\Simple;
use Joomla\Form\Rule;
use Joomla\Registry\Registry;

class StrongPasswordRule extends Rule {

    public function test(\SimpleXMLElement $element, $value, $group = null, Registry $input = null, Form $form = null) {

        $value = trim($value);

        // Au moins 8 caractères.
        if (strlen($value) < 8) {
            return false;
        }

        // Au moins un chiffre.
        if (!preg_match("/[0-9]+/", $value)) {
            return false;
        }

        // Au moins une lettre.
        if (!preg_match("/[a-z]+/", $value)) {
            return false;
        }

        // Au moins une majuscule.
        if (!preg_match("/[A-Z]+/", $value)) {
            return false;
        }

        // Ne doit pas être identique au précédent.
        $simpleAuth = new Simple();
        $user       = $form->getContainer()->get('user')->load();
        if ($simpleAuth->verify($value, $user->password)) {
            return false;
        }

        return true;
    }

}