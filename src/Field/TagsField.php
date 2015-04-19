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

class TagsField extends \Joomla\Form\Field {

    protected $type = 'Tags';

    protected function getInput() {

        $attr = '';
        $value = $this->value;

        // Initialize some field attributes.
        $attr .= $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';

        // To avoid user's confusion, readonly="true" should imply disabled="true".
        if ((string) $this->element['readonly'] == 'true' || (string) $this->element['disabled'] == 'true') {
            $attr .= ' disabled="disabled"';
        }

        $attr .= $this->element['size'] ? ' size="' . (int) $this->element['size'] . '"' : '';

        // Initialize JavaScript field attributes.
        $attr .= $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';

        // Get the field options.
        $items = (array) $this->getItems();

        $js = "var \$input = $('#".$this->id."');
var ".$this->id."_values = new Bloodhound({
  datumTokenizer: Bloodhound.tokenizers.obj.whitespace('text'),
  queryTokenizer: Bloodhound.tokenizers.whitespace,
  local: " . json_encode(array_values($items)) . "
});
".$this->id."_values.initialize();
\$input.tagsinput({
    itemValue: 'value',
    itemText: 'text',
    typeaheadjs : {
        name: '".$this->id."_values',
        displayKey: 'text',
        source: ".$this->id."_values.ttAdapter()
    }
});";

        if (is_array($value)) {
            foreach ($value as $val) {
                $js .= "\$input.tagsinput('add', " . json_encode($items[$val]) . ");";
            }
            $value = implode(",", $value);
        } elseif (is_string($value) && !empty($value)) {
            $ids = explode(",", $value);
            foreach ($ids as $val) {
                $js .= "\n\$input.tagsinput('add', " . json_encode($items[$val]) . ");";
            }
        }

        // On charge le JS.
        (new RequireJSUtility())
            ->addRequireJSModule("typeahead", "js/vendor/typeahead.bundle.min", true, array("jquery"))
            ->addRequireJSModule("bootstraptagsinput", "js/vendor/bootstrap-tagsinput.min", true, array("jquery", "typeahead"))
            ->addDomReadyJS($js, false, "bootstraptagsinput");

        return '<input type="text" id="' . $this->id . '" name="' . $this->name . '" value="' . htmlspecialchars($value) . '" ' . $attr . '>';

    }


    /**
     * Méthode pour récupérer les tags disponibles.
     *
     * @return  array  Les tags disponibles.
     */
    protected function getItems() {

        return array();
    }

}
