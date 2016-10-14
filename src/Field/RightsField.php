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
use EtdSolutions\Utility\RequireJSUtility;
use Joomla\Form\Field;
use Joomla\Registry\Registry;
use SimpleAcl\Role\RoleAggregate;
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

        @set_time_limit(120);
        @ini_set('max_execution_time', 120);

        // Initialisation.
        $html = array();
        $text = $this->getText();

        // JS
        (new RequireJSUtility())
            ->addDomReadyJS("$('[data-toggle=\"tooltip\"], .hasTooltip').tooltip({container:'body',html:true});", false, "bootstrap");

        if (!empty($this->value)) {
            $value = new Registry($this->value);
        }

        $class = $this->element['class'] ? ' ' . (string)$this->element['class'] : '';

        // On récupère l'instance de gestion ACL.
        $acl    = Acl::getInstance($this->form->getDb());
	    $simple = $acl->getSimpleAcl();

        // On récupère les sections et les actions.
        $actions = $this->getActions();

        // On charge les groupes utilisateurs.
        $groups = $this->getUserGroups();

	    // On charge les rôles.
	    $roles = $acl->getRoles();

        // Building tab nav
        $html[] = '<div role="tabpanel" class="nav-tabs-left">';

        $html[] = '<ul class="nav nav-tabs" role="tablist">';

        foreach ($groups as $i => $group) {
            $tab_class = $i == 0 ? ' class="active"' : '';
            $html[] = '<li role="presentation"' . $tab_class . '><a href="#group-' . $group->id . '" aria-controls="group-' . $group->id . '" role="tab" data-toggle="tab">';
            $html[] = str_repeat('<span class="level">&ndash;</span> ', $curLevel = $group->level) . $group->title;
            $html[] = '</a></li>';
        }

        $html[] = '</ul>';
        $html[] = '<div class="tab-content">';

        foreach ($groups as $i => $group) {

            $tab_class = $i == 0 ? ' active' : '';

            $html[] = '<div role="tabpanel" class="tab-pane' . $tab_class .'" id="group-' . $group->id . '">';

            $html[] = '<table class="rights table table-condensed' . $class . '">';

            $html[] = '<thead>';
            $html[] = '<tr>';
            $html[] = '<th>' . $text->translate('APP_GLOBAL_RIGHTS_HEADING_SECTION') . '</th>';
            $html[] = '<th>' . $text->translate('APP_GLOBAL_RIGHTS_HEADING_ACTION') . '</th>';
            $html[] = '<th>' . $text->translate('APP_GLOBAL_RIGHTS_HEADING_RIGHT') . '</th>';

            if ($group->parent_id) {
                $html[] = '<th id="aclactionth' . $group->id . '">';
                $html[] = '<span class="acl-action">' . $text->translate('APP_GLOBAL_RIGHTS_HEADING_CALCULATED_SETTING') . '</span>';
                $html[] = '</th>';
            }

            $html[] = '</tr>';
            $html[] = '</thead>';

            $html[] = '<tbody>';

            // On parcourt les sections.
            foreach ($actions as $section) {

                $rowspan = count($section->actions);

                foreach ($section->actions as $i => $action) {

                    $html[] = '<tr>';

                    if ($i == 0) {
                        $html[] = '<td class="section" rowspan="' . $rowspan . '"><span class="hasTooltip" title="' . $text->translate($section->description) . '">' . $text->translate($section->title) . '</td>';
                    }

                    $html[] = '<td class="action"><span class="hasTooltip" title="' . $text->translate($action->description) . '">' . $text->translate($action->title) . '</td>';
                    $html[] = '<td class="right">';

                    $html[] = '<select class="form-control input-sm"'
                        . ' name="' . $this->name . '[' . $section->name . '][' . $action->name . '][' . $group->id . ']"'
                        . ' id="' . $this->id . '_' . $section->name . '_' . $action->name	. '_' . $group->id . '"'
                        . ' title="' . $text->sprintf('APP_GLOBAL_RIGHTS_SELECT_ALLOW_DENY_GROUP', $text->translate($action->title), trim($group->title)) . '">';

                    // On récupère les droits pour l'action hérités pour le groupe.
                    $inheritedRule = $acl->checkGroup($group->id, $section->name, $action->name);

                    // On récupère les droits pour l'action NON HÉRITÉS pour le groupe.
                    $assetRule = null;

	                $groups = new RoleAggregate();
	                $groups->addRole($roles[$group->id]);
                    $res = $simple->isAllowedReturnResult($groups, $section->name, $action->name)->collection;

                    if ($res->count() > 0) {
                        $res->top();
                        while($res->valid()) {
                            $priority = $res->current()->getPriority();

                            // La règle est appliqué à ce groupe en particulier si la priorité est à zero.
                            if ($priority == 0) {
                                $assetRule = $res->current()->getAction();
                            }

                            $res->next();
                        }
                    }

                    // Build the dropdowns for the permissions sliders

                    // The parent group has "Not Set", all children can rightly "Inherit" from that.
                    $html[] = '<option value=""' . ($assetRule === null ? ' selected="selected"' : '') . '>'
                        . $text->translate(empty($group->parent_id) ? 'APP_GLOBAL_RIGHTS_NOT_SET' : 'APP_GLOBAL_RIGHTS_INHERITED') . '</option>';
                    $html[] = '<option value="1"' . ($assetRule === true ? ' selected="selected"' : '') . '>' . $text->translate('APP_GLOBAL_RIGHTS_ALLOWED')
                        . '</option>';
                    $html[] = '<option value="0"' . ($assetRule === false ? ' selected="selected"' : '') . '>' . $text->translate('APP_GLOBAL_RIGHTS_DENIED')
                        . '</option>';

                    $html[] = '</select>&#160; ';

                    // If this asset's rule is allowed, but the inherited rule is deny, we have a conflict.
                    if (($assetRule === true) && ($inheritedRule === false)) {
                        $html[] = $text->translate('APP_GLOBAL_RIGHTS_CONFLICT');
                    }

                    $html[] = '</td>';

                    // Build the Calculated Settings column.
                    // The inherited settings column is not displayed for the root group in global configuration.
                    if ($group->parent_id) {

                        $html[] = '<td headers="aclactionth' . $group->id . '">';

                        // This is where we show the current effective settings considering currrent group, path and cascade.
                        // Check whether this is a component or global. Change the text slightly.

                        if ($acl->checkGroup($group->id, "app", "admin") !== true) {

                            if ($inheritedRule === true) {
                                $html[] = '<span class="label label-success">' . $text->translate('APP_GLOBAL_RIGHTS_ALLOWED') . '</span>';
                            } elseif ($inheritedRule === false) {
                                $html[] = '<span class="label label-danger">' . $text->translate('APP_GLOBAL_RIGHTS_NOT_ALLOWED') . '</span>';
                            }

                        } elseif ($section->name != 'app') {
                                $html[] = '<span class="label label-success"><i class="fa fa-lock"></i> ' . $text->translate('APP_GLOBAL_RIGHTS_ALLOWED_ADMIN')
                                    . '</span>';
                        } else {

                            // Special handling for  groups that have global admin because they can't  be denied.
                            // The admin rights can be changed.

                            if ($section->name === 'app' && $action->name === 'admin') {
                                $html[] = '<span class="label label-success">' . $text->translate('APP_GLOBAL_RIGHTS_ALLOWED') . '</span>';
                            } elseif ($inheritedRule === false) {
                                // Other actions cannot be changed.
                                $html[] = '<span class="label label-danger"><i class="fa fa-lock"></i> '
                                    . $text->translate('APP_GLOBAL_RIGHTS_NOT_ALLOWED_ADMIN_CONFLICT') . '</span>';
                            } else {
                                $html[] = '<span class="label label-success"><i class="fa fa-lock"></i> ' . $text->translate('APP_GLOBAL_RIGHTS_ALLOWED_ADMIN')
                                    . '</span>';
                            }
                        }

                        $html[] = '</td>';
                    }

                    $html[] = '</tr>';

                }

            }


            $html[] = '';
            $html[] = '';
            $html[] = '';
            $html[] = '</tbody>';

            $html[] = '</table>';

            $html[] = '</div>';

        }

        $html[] = '</div>';
        $html[] = '</div>';

        return implode($html);

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

    /**
     * Récupère les groupes utilisateurs.
     *
     * @return mixed
     */
    protected function getUserGroups() {

        $db = $this->form->getDb();

        $query = $db->getQuery(true)
            ->select('a.id, a.title, COUNT(DISTINCT b.id) AS level, a.parent_id')
            ->from('#__usergroups AS a')
            ->join('LEFT', $db->quoteName('#__usergroups') . ' AS b ON a.lft > b.lft AND a.rgt < b.rgt')
            ->group('a.id, a.title, a.lft, a.rgt, a.parent_id')
            ->order('a.lft ASC');
        $db->setQuery($query);
        $options = $db->loadObjectList();

        return $options;

    }
}
