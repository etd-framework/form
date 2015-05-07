<?php
/**
 * Part of the ETD Framework Form Package
 *
 * @copyright   Copyright (C) 2015 ETD Solutions, SARL Etudoo. Tous droits réservés.
 * @license     Apache License 2.0; see LICENSE
 * @author      ETD Solutions http://etd-solutions.com
 */

namespace EtdSolutions\Form\Field;

use EtdSolutions\Utility\RequireJSUtility;
use Joomla\Form\Html\Select as HtmlSelect;
use Joomla\Utilities\ArrayHelper;

class TagsField extends ChosenListField {

    protected $type = 'Tags';

    /**
     * Drapeau pour travailler avec un champ de tag imbriqué.
     *
     * @var boolean
     */
    public $isNested = null;

    protected function getInput() {

        // La valeur est un tableau
        if (is_array($this->value) && count($this->value)) {

            // On regarde si ce n'est pas des objets.
            if (is_object($this->value[0])) {
                $this->value = ArrayHelper::getColumn($this->value, 'id');
            }

        } elseif (is_string($this->value) && !empty($this->value)) { // Valeur au format 2,5,4
            $this->value = explode(',', $this->value);
        }

        // On appel le parent pour ajouter les scripts Chosen avant celui en dessous.
        $input = parent::getInput();

        // Valeurs personnalisés ?
        if ($this->allowCustom()) {

            $minTermLength = $this->element['minTermLength'] ? $this->element['minTermLength'] : 3;

            $js = "var customTagPrefix = '#etdnew#';

// Méthode pour ajouter des tags quand on appuie sur la touche entrer.
$('#" . $this->id . "_chosen input').keyup(function(event) {

    // Le tag est plus long que le minimum requis et la touche entrer est appuyée.
    if (this.value && this.value.length >= " . $minTermLength . " && (event.which === 13 || event.which === 188)) {

        // On cherche un le résultat en surbrillance.
        var highlighted = $('#" . $this->id . "_chosen').find('li.active-result.highlighted').first();

        // On ajoute l'option en surbrillance
        if (event.which === 13 && highlighted.text() !== '') {

            // Contrôle supplémentaire. Si on a ajouté un tag personnalisé avec ce texte, on le retire.
            var customOptionValue = customTagPrefix + highlighted.text();
            $('" . $this->id . " option').filter(function () { return $(this).val() == customOptionValue; }).remove();

            // On sélection le résultat mis en surbrillance.
            var tagOption = $('#" . $this->id . " option').filter(function () { return $(this).html() == highlighted.text(); });
            tagOption.attr('selected', 'selected');

        } else { // On ajoute l'option du tag personnalisé.

            var customTag = this.value;

            // Contrôle supplémentaire. On recherche si le tag personnalisé existe déjà (tapé plus rapidement que la requête AJAX)
            var tagOption = $('#" . $this->id . " option').filter(function () { return $(this).html() == customTag; });
            if (tagOption.text() !== '') {
                tagOption.attr('selected', 'selected');
            } else {
                var option = $('<option>');
                option.text(this.value).val(customTagPrefix + this.value);
                option.attr('selected','selected');

                // On ajoute l'option et on met à jour le champ chosen.
                $('#" . $this->id . "').append(option);

            }
        }

        this.value = '';
        $('#" . $this->id . "').trigger('chosen:updated');
        event.preventDefault();

    }
});";

            // On charge le JS.
            (new RequireJSUtility())
                ->addDomReadyJS($js, false, "chosen");

        }

        return $input;
    }

    /**
     * Méthode pour récupérer la liste des tags.
     *
     * @return  array
     */
    protected function getOptions() {

        $published = $this->element['published'] ? (string)$this->element['published'] : array(0, 1);
        $table = $this->element['table'] ? (string)$this->element['table'] : false;

        $db    = $this->form->getDb();
        $query = $db->getQuery(true)
                    ->select('DISTINCT a.id AS value, a.path, a.title AS text, a.level')
                    ->from('#__tags AS a')
                    ->join('LEFT', '#__tags AS b ON a.lft > b.lft AND a.rgt < b.rgt');

        $query->where('a.lft > 0');
        // Filtre par état de publication
        if (is_numeric($published)) {
            $query->where('a.published = ' . (int)$published);
        } elseif (is_array($published)) {
            ArrayHelper::toInteger($published);
            $query->where('a.published IN (' . implode(',', $published) . ')');
        }

        // Filtre par association.
        if ($table) {
            $query->leftJoin('#__tags_map AS c ON c.tag_id = a.id');
            $query->where('c.table_name = ' . $db->quote($table));
        }

        $query->order('a.lft ASC');
        $db->setQuery($query);

        try {
            $options = $db->loadObjectList();
        } catch (\RuntimeException $e) {
            return false;
        }

        // On bloque la possibilité de définir un tag comme son propre parent.
        if ($this->form->getName() == 'tag') {
            $id = (int)$this->form->getValue('id', 0);

            foreach ($options as $option) {
                if ($option->value == $id) {
                    $option->disable = true;
                }
            }
        }

        // On fusionne les options additionnelles venant du XML.
        $options = array_merge(parent::getOptions(), $options);

        // On prépare les options imbriquées.
        if ($this->isNested()) {
            $this->prepareOptionsNested($options);
        } else {
            $options = $this->convertPathsToNames($options);
        }

        return $options;
    }

    /**
     * Ajoute un "-" avant les tags imbriqués, suivant leur niveau.
     *
     * @param   array  &$options  tableau de tags
     *
     * @return  array
     */
    protected function prepareOptionsNested(&$options) {

        if ($options) {
            foreach ($options as &$option) {
                $repeat = (isset($option->level) && $option->level - 1 >= 0) ? $option->level - 1 : 0;
                $option->text = str_repeat('- ', $repeat) . $option->text;
            }
        }

        return $options;
    }

    /**
     * Détermine si le champ fonctionne avec les tags imbriqués.
     *
     * @return  boolean
     */
    public function isNested() {

        if (is_null($this->isNested)) {
            if ((isset($this->element['mode']) && $this->element['mode'] == 'nested')) {
                $this->isNested = true;
            }
        }

        return $this->isNested;
    }

    /**
     * Détermine si le champ autorise ou interdit les valeurs personnalisées.
     *
     * @return  boolean
     */
    public function allowCustom() {

        if (isset($this->element['custom']) && $this->element['custom'] == 'allow') {
            return true;
        }

        return false;
    }

    /**
     * Function pour convertir des chemins de tags en chemin de noms.
     *
     * @param   array  $tags
     *
     * @return  array
     */
    protected function convertPathsToNames($tags) {

        if ($tags) {

            // On crée un tableau avec tous les alias des résultats.
            $aliases = array();

            foreach ($tags as $tag) {
                if (!empty($tag->path)) {
                    if ($pathParts = explode('/', $tag->path)) {
                        $aliases = array_merge($aliases, $pathParts);
                    }
                }
            }

            // On récupère les titres des alias dans une seule requête et on map les résultats.
            if ($aliases) {

                // On retire les doublons.
                $aliases = array_unique($aliases);

                $db = $this->form->getDb();

                $query = $db->getQuery(true)
                    ->select('alias, title')
                    ->from('#__tags')
                    ->where('alias IN (' . implode(',', array_map(array($db, 'quote'), $aliases)) . ')');
                $db->setQuery($query);

                try {
                    $aliasesMapper = $db->loadAssocList('alias');
                }
                catch (\RuntimeException $e) {
                    return false;
                }

                // On reconstruit les chemins.
                if ($aliasesMapper) {
                    foreach ($tags as $tag) {
                        $namesPath = array();

                        if (!empty($tag->path)) {
                            if ($pathParts = explode('/', $tag->path)) {
                                foreach ($pathParts as $alias) {
                                    if (isset($aliasesMapper[$alias])) {
                                        $namesPath[] = $aliasesMapper[$alias]['title'];
                                    } else {
                                        $namesPath[] = $alias;
                                    }
                                }

                                $tag->text = implode('/', $namesPath);
                            }
                        }
                    }
                }
            }
        }

        return $tags;
    }

}
