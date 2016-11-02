define(['module', 'exports', 'etdsolutions/base'], function (module, exports, _base) {
    'use strict';

    Object.defineProperty(exports, "__esModule", {
        value: true
    });

    var _base2 = _interopRequireDefault(_base);

    function _interopRequireDefault(obj) {
        return obj && obj.__esModule ? obj : {
            default: obj
        };
    }

    function _classCallCheck(instance, Constructor) {
        if (!(instance instanceof Constructor)) {
            throw new TypeError("Cannot call a class as a function");
        }
    }

    var _createClass = function () {
        function defineProperties(target, props) {
            for (var i = 0; i < props.length; i++) {
                var descriptor = props[i];
                descriptor.enumerable = descriptor.enumerable || false;
                descriptor.configurable = true;
                if ("value" in descriptor) descriptor.writable = true;
                Object.defineProperty(target, descriptor.key, descriptor);
            }
        }

        return function (Constructor, protoProps, staticProps) {
            if (protoProps) defineProperties(Constructor.prototype, protoProps);
            if (staticProps) defineProperties(Constructor, staticProps);
            return Constructor;
        };
    }();

    function _possibleConstructorReturn(self, call) {
        if (!self) {
            throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
        }

        return call && (typeof call === "object" || typeof call === "function") ? call : self;
    }

    function _inherits(subClass, superClass) {
        if (typeof superClass !== "function" && superClass !== null) {
            throw new TypeError("Super expression must either be null or a function, not " + typeof superClass);
        }

        subClass.prototype = Object.create(superClass && superClass.prototype, {
            constructor: {
                value: subClass,
                enumerable: false,
                writable: true,
                configurable: true
            }
        });
        if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass;
    }

    // Constantes
    // ===============================

    var blankGIF = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';

    // Méthodes privées
    // ===============================

    function _blobToBase64(blob, cb) {

        var reader = new FileReader();
        reader.onload = function (evt) {
            cb(evt.target.result);
        };
        reader.readAsDataURL(blob);
    }

    // Classe publique
    // ===============================

    var Avatar = function (_Base) {
        _inherits(Avatar, _Base);

        function Avatar(element, options) {
            _classCallCheck(this, Avatar);

            var _this = _possibleConstructorReturn(this, (Avatar.__proto__ || Object.getPrototypeOf(Avatar)).call(this, element, options));

            _this.browseBtn = _this.element.querySelector('.btn-browse');
            _this.cancelBtn = _this.element.querySelector('.btn-cancel');
            _this.deleteBtn = _this.element.querySelector('.btn-delete');
            _this.input = _this.element.querySelector('.avatar-file');
            _this.img = _this.element.querySelector('.avatar-img');

            _this.hideFileInput();

            _this.cancelBtn.style.display = 'none';
            _this.deleteBtn.style.display = 'none';

            _this.bindEvents();

            return _this;
        }

        _createClass(Avatar, [{
            key: 'hideFileInput',
            value: function hideFileInput() {

                this.input.style.visibility = 'hidden';
                this.input.style.position = 'absolute';
                this.input.style.height = '1px';
                this.input.style.width = '1px';
                this.input.style.top = '-15000px';
                this.input.style.left = '-15000px';

                return this;
            }
        }, {
            key: 'bindEvents',
            value: function bindEvents() {

                var self = this;

                this.input.addEventListener("change", function (e) {

                    _blobToBase64(this.files[0], function (dataUri) {
                        self.img.style.backgroundImage = "url('" + dataUri + "')";
                        self.img.style.backgroundPosition = "50% 50%";
                        self.img.style.backgroundSize = "cover";
                    });

                    self.browseBtn.style.display = 'none';
                    self.cancelBtn.style.display = 'inline-block';
                });

                this.browseBtn.addEventListener("click", function (e) {
                    e.preventDefault();
                    self.input.click();
                    return false;
                });

                this.cancelBtn.addEventListener("click", function (e) {
                    e.preventDefault();
                    self.img.style.backgroundImage = "url('" + blankGIF + "')";
                    self.browseBtn.style.display = 'inline-block';
                    self.cancelBtn.style.display = 'none';
                    self.input.value = '';
                    return false;
                });
            }
        }]);

        return Avatar;
    }(_base2.default);

    exports.default = Avatar;
    module.exports = exports['default'];
});
