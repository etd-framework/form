<?php
/**
 * Part of the ETD Framework Form Package
 *
 * @copyright   Copyright (C) 2015-2016 ETD Solutions. Tous droits réservés.
 * @license     Apache License 2.0; see LICENSE
 * @author      ETD Solutions http://etd-solutions.com
 */

namespace EtdSolutions\Form\Field;

use EtdSolutions\Utility\RequireJSUtility;
use Joomla\Form\Field;

class AvatarField extends Field {

    /**
     * The form field type.
     *
     * @var    string
     */
    protected $type = 'Avatar';

    protected function getInput() {

        $requirejs = new RequireJSUtility();
        $html      = [];
        $js        = [];

        $wrapperClass   = $this->element['wrapperClass'] ? 'avatar-upload ' . (string) $this->element['wrapperClass'] : 'avatar-upload';
        $avatarClass    = $this->element['avatarClass'] ? 'avatar ' . (string) $this->element['avatarClass'] : 'avatar';
        $initialsClass  = $this->element['initialsClass'] ? 'avatar-initials ' . (string) $this->element['initialsClass'] : 'avatar-initials';
        $imgClass       = $this->element['imgClass'] ? 'avatar-img ' . (string) $this->element['imgClass'] : 'avatar-img';
        $btnClass       = $this->element['btnClass'] ? 'filepicker btn ' . (string) $this->element['btnClass'] : 'filepicker btn';
        $chooseBtnLabel = $this->element['chooseBtnLabel'] ? $this->getText()->translate((string) $this->element['chooseBtnLabel']) : $this->getText()->translate('AVATAR_UPLOAD_CHOOOSE_BTN_LABEL');
        $cancelBtnLabel = $this->element['cancelBtnLabel'] ? $this->getText()->translate((string) $this->element['cancelBtnLabel']) : $this->getText()->translate('AVATAR_UPLOAD_CANCEL_BTN_LABEL');
        $deleteBtnLabel = $this->element['deleteBtnLabel'] ? $this->getText()->translate((string) $this->element['deleteBtnLabel']) : $this->getText()->translate('AVATAR_UPLOAD_DELETE_BTN_LABEL');
        $defaultInitials = $this->element['defaultInitials'] ? (string) $this->element['defaultInitials'] : '';

        // Image
        $imgUrl = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';

        // Initials
        $initials = $this->element['initials'] ? (string) $this->element['initials'] : $defaultInitials;

        $html[] = '<div id="' . $this->id . '" class="' . $wrapperClass . '">';
        $html[] = '<div class="' . $avatarClass . '">';
        $html[] = '<div class="' . $imgClass . '" style="background-image:url(' . $imgUrl . ');"></div>';
        $html[] = '<div class="' . $initialsClass . '">' . $initials .'</div>';
        $html[] = '</div>';
        $html[] = '<button type="button" class="btn-browse ' . $btnClass . '">' . $chooseBtnLabel . '</button>';
        $html[] = '<button type="button" class="btn-cancel ' . $btnClass . '">' . $cancelBtnLabel . '</button>';
        $html[] = '<button type="button" class="btn-delete ' . $btnClass . '">' . $deleteBtnLabel . '</button>';
        $html[] = '<input name="' . $this->name . '" type="file" accept="image/*" class="avatar-file">';
        $html[] = '</div>';

        $options = [

        ];

        $js[] = "new avatar('" . $this->id . "', " . json_encode($options) . ");";

        $requirejs->addRequireJSModule("avatar", "js/vendor/avatar.min");
        $requirejs->addDomReadyJS(implode("\n", $js), false, "avatar", false);

        return implode($html);

    }

}