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

class ColorField extends \Joomla\Form\Field {

    protected $type = 'Color';

    protected function getInput() {

        $class = $this->element['class'] ? ' class="color-input ' . (string) $this->element['class'] . '"' : ' class="color-input"';

        $value = empty($this->value) ? 'ffffff' : $this->value;

        $js = "
var \$input = $('#" . $this->id . "');
\$input.on('click', function(e) {
    e.preventDefault();
    var \$container = \$('#" . $this->id . "_picker');
    if (\$container.hasClass('open')) {
        \$container.removeClass('open');
    } else {
        if (!\$input.data('joe')) {
            var joe = colorjoe.rgb('" . $this->id . "_inner', '" . $value . "');
            joe.on('change', function(color) {
                $('input[name=\"" . $this->name . "\"]').val(color.hex().substr(1));
                \$input.css('background-color', color.hex());
            });
            joe.on('done', function() {
                \$container.removeClass('open');
            });
            \$input.data('joe', joe);
        }
        \$container.css({
            top: \$input.position().top + \$input.height(),
            left: \$input.position().left + \$input.width()
        }).addClass('open');
    }
});";

        (new RequireJSUtility())
            ->addRequireJSModule("onecolor", "js/vendor/one-color.min", true)
            ->addRequireJSModule("colorjoe", "js/vendor/colorjoe.min", false, ["onecolor"])
            ->addDomReadyJS($js, false, "colorjoe, css!/css/vendor/colorjoe.min.css");

        if ($this->element['class']) {
            $this->element['class'] .= " color-input";
        } else {
            $this->element['class'] = "color-input";
        }

        return '<span id="' . $this->id . '" '. $class . ' style="background-color:#' . $value . '"></span><div class="color-input-picker" id="' . $this->id . '_picker"><div id="' . $this->id . '_inner"></div></div><input type="hidden" name="' . $this->name . '" value="' . $this->value . '">';

    }

}
