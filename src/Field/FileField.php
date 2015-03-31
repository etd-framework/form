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

class FileField extends \Joomla\Form\Field\FileField {

    /**
     * Method to get the radio button field input markup.
     *
     * @return  string  The field input markup.
     */
    protected function getInput() {

        // Options
        $options = array(
            'browseIcon' => '<span class="fa fa-folder-open"></span>&nbsp;',
            'browseLabel' => $this->getText()->translate('APP_GLOBAL_BROWSE'),
            'removeIcon' => '<span class="fa fa-trash-o"></span>&nbsp;',
            'removeLabel' => $this->getText()->translate('APP_GLOBAL_DELETE'),
            'uploadIcon' => '<span class="fa fa-upload"></span>&nbsp;',
            'uploadLabel' => $this->getText()->translate('APP_GLOBAL_UPLOAD')
        );

        // allowedFileExtensions
        if (isset($this->element['allowedFileExtensions'])) {
            $options['allowedFileExtensions'] = explode(',', (string) $this->element['allowedFileExtensions']);
        }

        // showCaption
        if (isset($this->element['showCaption'])) {
            $options['showCaption'] = ((string) $this->element['showCaption'] == 'true');
        }

        // showRemove
        if (isset($this->element['showRemove'])) {
            $options['showRemove'] = ((string) $this->element['showRemove'] == 'true');
        }

        // showUpload
        if (isset($this->element['showUpload'])) {
            $options['showUpload'] = ((string) $this->element['showUpload'] == 'true');
        }

        // maxFileCount
        if (isset($this->element['maxFileCount'])) {
            $options['maxFileCount'] = (int) $this->element['maxFileCount'];
        }

        // upload_uri
        if (isset($this->element['upload_uri'])) {
            $options['uploadAsync'] = true;
            $options['uploadUrl'] = (string) $this->element['upload_uri'];

            if ($id = $this->form->getValue('id')) {
                $options['uploadExtraData'] = array(
                    'id' => (int) $id
                );
            }

        }

        $options['dropZoneEnabled'] = false;

        // JS
        $js = "$('#" . $this->id . "').fileinput(" . json_encode($options) . ")";

        if (isset($this->element['upload_uri'])) {
            $js .= ".on('fileuploaded', function(event, data, previewId, index) {
    \$('input[name=\"" . $this->name . "\"]').val(data.response.name);
})";
        }

        $js .= ";";

        // On ajoute les ressources.
        (new RequireJSUtility())
            ->addRequireJSModule("fileinput", "js/vendor/fileinput.min", true, array("bootstrap", "jquery"))
            ->addDomReadyJS($js, false, "fileinput, css!/css/vendor/fileinput.min.css");

        // Initialize some field attributes.
        $accept = $this->element['accept'] ? ' accept="' . (string) $this->element['accept'] . '"' : '';
        $size = $this->element['size'] ? ' size="' . (int) $this->element['size'] . '"' : '';
        $class = $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';
        $disabled = ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';

        // Initialize JavaScript field attributes.
        $onchange = $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';

        return '<input type="file" name="' . $this->fieldname . '_upload" id="' . $this->id . '"' . ' value=""' . $accept . $disabled . $class . $size
        . $onchange . '><input type="hidden" name="' . $this->name . '" value="' . $this->value . '">';

    }

}