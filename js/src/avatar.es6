/**
 * @package     JS
 *
 * @version     2.0.0
 * @copyright   Copyright (C) 2016 ETD Solutions. Tous droits réservés.
 * @license     Apache-2.0
 * @author      ETD Solutions http://etd-solutions.com
 */

import Base from "etdsolutions/base";

// Constantes
// ===============================

const blankGIF = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';

// Méthodes privées
// ===============================

function _blobToBase64(blob, cb) {

    var reader = new FileReader();
    reader.onload = function(evt) {
        cb(evt.target.result);
    };
    reader.readAsDataURL(blob);

}

// Classe publique
// ===============================

class Avatar extends Base {

    browseBtn;
    cancelBtn;
    deleteBtn;
    input;

    constructor(element, options) {

        super(element, options);

        this.browseBtn = this.element.querySelector('.btn-browse');
        this.cancelBtn = this.element.querySelector('.btn-cancel');
        this.deleteBtn = this.element.querySelector('.btn-delete');
        this.input     = this.element.querySelector('.avatar-file');
        this.img       = this.element.querySelector('.avatar-img');

        this.hideFileInput();

        this.cancelBtn.style.display = 'none';
        this.deleteBtn.style.display = 'none';

        this.bindEvents();


    }

    hideFileInput() {

        this.input.style.visibility = 'hidden';
        this.input.style.position = 'absolute';
        this.input.style.height = '1px';
        this.input.style.width = '1px';
        this.input.style.top = '-15000px';
        this.input.style.left = '-15000px';

        return this;
    }

    bindEvents() {

        var self = this;

        this.input.addEventListener("change", function(e) {

            _blobToBase64(this.files[0], function(dataUri) {
                self.img.style.backgroundImage = "url('"+dataUri + "')";
                self.img.style.backgroundPosition = "50% 50%";
                self.img.style.backgroundSize = "cover";
            });

            self.browseBtn.style.display = 'none';
            self.cancelBtn.style.display = 'inline-block';

        });

        this.browseBtn.addEventListener("click", function(e) {
            e.preventDefault();
            self.input.click();
            return false;
        });

        this.cancelBtn.addEventListener("click", function(e) {
            e.preventDefault();
            self.img.style.backgroundImage = "url('" + blankGIF + "')";
            self.browseBtn.style.display = 'inline-block';
            self.cancelBtn.style.display = 'none';
            self.input.value = '';
            return false;
        });

    }

}

export default Avatar;