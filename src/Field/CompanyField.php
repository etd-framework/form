<?php
/**
 * Part of the ETD Framework Form Package
 *
 * @copyright   Copyright (C) 2015 ETD Solutions, SARL Etudoo. Tous droits réservés.
 * @license     Apache License 2.0; see LICENSE
 * @author      ETD Solutions http://etd-solutions.com
 */

namespace EtdSolutions\Form\Field;

use EtdSolutions\Application\Web;
use Joomla\Form\Field\ListField;
use Joomla\Form\Html\Select as HtmlSelect;

class CompanyField extends ListField {

    /**
     * The form field type.
     *
     * @var    string
     */
    protected $type = 'Company';

    protected function getOptions() {

        $htmlselect = new HtmlSelect();
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
            $options[] = $htmlselect->option($company->value, $company->text);
        }

        return $options;

    }


}