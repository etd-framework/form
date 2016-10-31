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
use Joomla\Form\Field;

class FileField extends Field {

    /**
     * @var RequireJSUtility
     */
    protected $requirejs;
    protected $modules = "";

    /**
     * Method to get the radio button field input markup.
     *
     * @return  string  The field input markup.
     */
    protected function getInput() {

        $this->requirejs = new RequireJSUtility();
        $this->modules = "";

        $options = $this->getOptions();

        // JS
        $js = "$('#" . $this->id . "').fileinput(" . json_encode($options) . ").on('filecleared', function() {
    \$('input[name=\"" . $this->name . "\"]').val('###DELETED###');
})";

        if (isset($this->element['uploadUrl'])) {
            $js .= ".on('fileuploaded', function(event, data, previewId, index) {
    \$('input[name=\"" . $this->name . "\"]').val(data.response.name);
})";
        }

        $js .= ";";

        // On ajoute les ressources.
        $this->requirejs
            ->addRequireJSModule("fileinput", "js/vendor/fileinput.min", true, array("jquery"))
            ->addDomReadyJS($js, false, "fileinput". $this->modules  .", css!/css/vendor/fileinput.min.css");

        // Initialize some field attributes.
        $accept = $this->element['accept'] ? ' accept="' . (string) $this->element['accept'] . '"' : '';
        $size = $this->element['size'] ? ' size="' . (int) $this->element['size'] . '"' : '';
        $class = $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';
        $disabled = ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';

        // Initialize JavaScript field attributes.
        $onchange = $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';

        return '<input type="file" name="' . $this->fieldname . '_upload" id="' . $this->id . '"' . ' value=""' . $accept . $disabled . $class . $size
        . $onchange . '><input type="hidden" name="' . $this->name . '" data-fileinput="' . $this->id . '" value="' . $this->value . '">';

    }

    protected function getOptions() {

        // Options
        $options = [
            'browseIcon'         => '<span class="fa fa-folder-open"></span>&nbsp;',
            'browseLabel'        => $this->getText()->translate('APP_GLOBAL_BROWSE'),
            'removeIcon'         => '<span class="fa fa-trash-o"></span>&nbsp;',
            'removeLabel'        => $this->getText()->translate('APP_GLOBAL_DELETE'),
            'uploadIcon'         => '<span class="fa fa-upload"></span>&nbsp;',
            'uploadLabel'        => $this->getText()->translate('APP_GLOBAL_UPLOAD'),
            'previewFileIcon'    => '<span class="fa fa-eye"></span>&nbsp;',
            'previewFileLabel'   => $this->getText()->translate('APP_GLOBAL_PREVIEW'),
            'cancelIcon'         => '<span class="fa fa-ban"></span>&nbsp;',
            'cancelLabel'        => $this->getText()->translate('APP_GLOBAL_CANCEL'),
            'fileActionSettings' => [
                'removeIcon'            => '<span class="fa fa-trash-o"></span>',
                'removeTitle'           => $this->getText()->translate('APP_GLOBAL_DELETE'),
                'uploadIcon'            => '<span class="fa fa-upload"></span>',
                'uploadTitle'           => $this->getText()->translate('APP_GLOBAL_UPLOAD'),
                'indicatorNew'          => '<span class="fa fa-star"></span>',
                'indicatorSuccess'      => '<span class="fa fa-check-circle"></span>',
                'indicatorError'        => '<span class="fa fa-exclamation-triangle"></span>',
                'indicatorLoading'      => '<span class="fa fa-spin fa-spinner></span>',
                'indicatorNewTitle'     => $this->getText()->translate('APP_GLOBAL_NOT_UPLOADED_YET'),
                'indicatorSuccessTitle' => $this->getText()->translate('APP_GLOBAL_UPLOADED'),
                'indicatorErrorTitle'   => $this->getText()->translate('APP_GLOBAL_UPLOAD_ERROR'),
                'indicatorLoadingTitle' => $this->getText()->translate('APP_GLOBAL_UPLOADING'),
            ]
        ];

        // showCaption
        if (isset($this->element['showCaption'])) {
            $options['showCaption'] = ((string) $this->element['showCaption'] == 'true');
        }

        // showPreview
        if (isset($this->element['showPreview'])) {
            $options['showPreview'] = ((string) $this->element['showPreview'] == 'true');
        }

        // showRemove
        if (isset($this->element['showRemove'])) {
            $options['showRemove'] = ((string) $this->element['showRemove'] == 'true');
        }

        // showUpload
        if (isset($this->element['showUpload'])) {
            $options['showUpload'] = ((string) $this->element['showUpload'] == 'true');
        }

        // showCancel
        if (isset($this->element['showCancel'])) {
            $options['showCancel'] = ((string) $this->element['showCancel'] == 'true');
        }

        // showClose
        if (isset($this->element['showClose'])) {
            $options['showClose'] = ((string) $this->element['showClose'] == 'true');
        }

        // showUploadedThumbs
        if (isset($this->element['showUploadedThumbs'])) {
            $options['showUploadedThumbs'] = ((string) $this->element['showUploadedThumbs'] == 'true');
        }

        // autoReplace
        if (isset($this->element['autoReplace'])) {
            $options['autoReplace'] = ((string) $this->element['autoReplace'] == 'true');
        }

        // captionClass
        if (isset($this->element['captionClass'])) {
            $options['captionClass'] = (string) $this->element['captionClass'];
        }

        // previewClass
        if (isset($this->element['previewClass'])) {
            $options['previewClass'] = (string) $this->element['previewClass'];
        }

        // mainClass
        if (isset($this->element['mainClass'])) {
            $options['mainClass'] = (string) $this->element['mainClass'];
        }

        // initialDelimiter
        if (isset($this->element['initialDelimiter'])) {
            $options['initialDelimiter'] = (string) $this->element['initialDelimiter'];
        }

        // initialPreview
        if (isset($this->element['initialPreview'])) {
            $options['initialPreview'] = (string) $this->element['initialPreview'];
        }

        // initialPreviewCount
        if (isset($this->element['initialPreviewCount'])) {
            $options['initialPreviewCount'] = (int) $this->element['initialPreviewCount'];
        }

        // initialPreviewDelimiter
        if (isset($this->element['initialPreviewDelimiter'])) {
            $options['initialPreviewDelimiter'] = (string) $this->element['initialPreviewDelimiter'];
        }

        // initialPreviewConfig
        if (isset($this->element['initialPreviewConfig'])) {
            $options['initialPreviewConfig'] = $this->element['initialPreviewConfig'];
        }

        // initialPreviewShowDelete
        if (isset($this->element['initialPreviewShowDelete'])) {
            $options['initialPreviewShowDelete'] = ((string) $this->element['initialPreviewShowDelete'] == 'true');
        }

        // deleteUrl
        if (isset($this->element['deleteUrl'])) {
            $options['deleteUrl'] = (string) $this->element['deleteUrl'];
        }

        // initialCaption
        if (isset($this->element['initialCaption'])) {
            $options['initialCaption'] = (string) $this->element['initialCaption'];
        }

        // overwriteInitial
        if (isset($this->element['overwriteInitial'])) {
            $options['overwriteInitial'] = ((string) $this->element['overwriteInitial'] == 'true');
        }

        // allowedFileTypes
        if (isset($this->element['allowedFileTypes'])) {
            $options['allowedFileTypes'] = explode(',', (string) $this->element['allowedFileTypes']);
        }

        // allowedFileExtensions
        if (isset($this->element['allowedFileExtensions'])) {
            $options['allowedFileExtensions'] = explode(',', (string) $this->element['allowedFileExtensions']);
        }

        // allowedPreviewTypes
        if (isset($this->element['allowedPreviewTypes'])) {
            $options['allowedPreviewTypes'] = explode(',', (string) $this->element['allowedPreviewTypes']);
        }

        // allowedPreviewMimeTypes
        if (isset($this->element['allowedPreviewMimeTypes'])) {
            $options['allowedPreviewMimeTypes'] = explode(',', (string) $this->element['allowedPreviewMimeTypes']);
        }

        // defaultPreviewContent
        if (isset($this->element['defaultPreviewContent'])) {
            $options['defaultPreviewContent'] = (string) $this->element['defaultPreviewContent'];
        }

        // uploadUrl
        if (isset($this->element['uploadUrl'])) {
            $options['uploadAsync'] = true;
            $options['uploadUrl']   = (string) $this->element['uploadUrl'];

            if ($id = $this->form->getValue('id')) {
                $options['uploadExtraData'] = array(
                    'id' => (int) $id
                );
            }

        } else {
            $options['uploadAsync'] = false;
        }

        // minImageWidth
        if (isset($this->element['minImageWidth'])) {
            $options['minImageWidth'] = (int) $this->element['minImageWidth'];
        }

        // minImageHeight
        if (isset($this->element['minImageHeight'])) {
            $options['minImageHeight'] = (int) $this->element['minImageHeight'];
        }

        // maxImageWidth
        if (isset($this->element['maxImageWidth'])) {
            $options['maxImageWidth'] = (int) $this->element['maxImageWidth'];
        }

        // maxImageHeight
        if (isset($this->element['maxImageHeight'])) {
            $options['maxImageHeight'] = (int) $this->element['maxImageHeight'];
        }

        // resizeImage
        if (isset($this->element['resizeImage'])) {
            $options['resizeImage'] = ((string) $this->element['resizeImage'] == 'true');
            $this->requirejs->addRequireJSModule('canvastoblob', 'js/vendor/canvas-to-blob.min', true);
            $this->modules .= ", canvastoblob";
        }

        // resizePreference
        if (isset($this->element['resizePreference'])) {
            $options['resizePreference'] = (string) $this->element['resizePreference'];
        }

        // resizeQuality
        if (isset($this->element['resizeQuality'])) {
            $options['resizeQuality'] = (float) $this->element['resizeQuality'];
        }

        // resizeDefaultImageType
        if (isset($this->element['resizeDefaultImageType'])) {
            $options['resizeDefaultImageType'] = (string) $this->element['resizeDefaultImageType'];
        }

        // maxFileSize
        if (isset($this->element['maxFileSize'])) {
            $options['maxFileSize'] = (float) $this->element['maxFileSize'];
        }

        // minFileCount
        if (isset($this->element['minFileCount'])) {
            $options['minFileCount'] = (int) $this->element['minFileCount'];
        }

        // maxFileCount
        if (isset($this->element['maxFileCount'])) {
            $options['maxFileCount'] = (int) $this->element['maxFileCount'];
        }

        // validateInitialCount
        if (isset($this->element['validateInitialCount'])) {
            $options['validateInitialCount'] = ((string) $this->element['validateInitialCount'] == 'true');
        }

        // dropZoneEnabled
        if (isset($this->element['dropZoneEnabled'])) {
            $options['dropZoneEnabled'] = ((string) $this->element['dropZoneEnabled'] == 'true');
        }

        // dropZoneEnabled
        if (isset($this->element['dropZoneTitle'])) {
            $options['dropZoneTitle'] = $this->getText()->translate((string) $this->element['dropZoneTitle']);
        }

        // dropZoneTitleClass
        if (isset($this->element['dropZoneTitleClass'])) {
            $options['dropZoneTitleClass'] = (string) $this->element['dropZoneTitleClass'];
        }

        // textEncoding
        if (isset($this->element['textEncoding'])) {
            $options['textEncoding'] = (string) $this->element['textEncoding'];
        }

        return $options;

    }

}