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
use Joomla\Form\Field_List;
use Joomla\Form\Html\Select as HtmlSelect;

class Field_Company extends Field_List {

    /**
     * The form field type.
     *
     * @var    string
     */
    protected $type = 'Company';

    protected function getOptions() {

        $options = parent::getOptions();
        $db = Web::getInstance()->getDb();

        $db->setQuery(
          $db->getQuery(true)
             ->select('a.id AS value, a.name AS text')
             ->from('#__companies AS a')
             ->where('a.block = 0')
        );

        $companies = $db->loadObjectList();

        foreach ($companies as $company) {
            $options[] = HtmlSelect::option($company->value, $company->text);
        }

        return $options;

    }


}