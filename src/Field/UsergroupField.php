<?php
/**
 * Part of the ETD Framework Form Package
 *
 * @copyright   Copyright (C) 2015 ETD Solutions, SARL Etudoo. Tous droits réservés.
 * @license     Apache License 2.0; see LICENSE
 * @author      ETD Solutions http://etd-solutions.com
 */

namespace EtdSolutions\Form\Field;

use EtdSolutions\Acl\Acl;
use Joomla\Form\Field\ListField;
use Joomla\Form\Html\Select as HtmlSelect;

class UsergroupField extends ListField {

    /**
     * The form field type.
     *
     * @var    string
     */
    protected $type = 'Usergroup';

    protected function getOptions() {

        $options = parent::getOptions();
        $db      = $this->form->getContainer()->get('db');
        $user    = $this->form->getContainer()->get('user')->load();
        $acl     = Acl::getInstance($db);

        $query = $db->getQuery(true)
                    ->select('a.id AS value, a.title AS text, a.level')
                    ->from('#__usergroups AS a');

        $query->order('a.lft ASC');

        // Get the options
        $items = $db->setQuery($query)->loadObjectList();

        // Pad the option text with spaces using depth level as a multiplier.
        $htmlselect = new HtmlSelect();
        for ($i = 0, $n = count($items); $i < $n; $i++) {
            if ($user->authorise('app','admin') || !$acl->checkGroup($items[$i]->value, 'app', 'admin')) {
                $items[$i]->text = str_repeat('--', $items[$i]->level) . ' ' . $items[$i]->text;
                $options[] = $htmlselect->option($items[$i]->value, $items[$i]->text);
            }
        }

        return $options;

    }

}