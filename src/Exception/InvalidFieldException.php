<?php
/**
 * Part of the ETD Framework Form Package
 *
 * @copyright   Copyright (C) 2015 ETD Solutions, SARL Etudoo. Tous droits réservés.
 * @license     Apache License 2.0; see LICENSE
 * @author      ETD Solutions http://etd-solutions.com
 */

namespace EtdSolutions\Form\Exception;

use Exception;

class InvalidFieldException extends \UnexpectedValueException {

    /**
     * @var string Le nom du champ en erreur.
     */
    protected $field;

    /**
     * @var string Le nom du groupe du champ en erreur.
     */
    protected $group;

    public function __construct($message = "", $code = 0, Exception $previous = null, $field = null, $group = null) {

        $this->field = $field;
        $this->group = $group;

        parent::__construct($message, $code, $previous);
    }

    /**
     * @return string
     */
    public function getField() {

        return $this->field;
    }

    /**
     * @return string
     */
    public function getGroup() {

        return $this->group;
    }



}