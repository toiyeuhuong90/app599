/**
 * FORM LOGIN
 **/
var LoginPopup = Class.create({
    initialize: function (options) {
        this.options = options;
        this.popup_content = $('magegiant-sociallogin-popup');
        this.popup_email = $('magegiant-sociallogin-popup-email');
        this.email_error = $('magegiant-email-error');
        this.popup_pass = $('magegiant-sociallogin-popup-pass');
        this.pass_error = $('magegiant-pass-error');
        this.image_login = $('progress_image_login');
        this.invalid_email = $('magegiant-invalid-email');
        this.email = this.options.email;
        this.pass = this.options.pass;

        this.login_form_div = $('magegiant-sociallogin-authentication');
        this.login_button = $('magegiant-button-sociallogin');
        this.login_form = $('magegiant-sociallogin-form');
        this.login_form_forgot = $('magegiant-sociallogin-form-forgot');
        this.forgot_a = $('magegiant-forgot-password');
        this.forgot_title = $('sociallogin-forgot');
        this.forgot_button = $('magegiant-button-sociallogin-forgot');
        this.forgot_a_back = $('magegiant-forgot-back');
        this.email_forgot_message = $('magegiant-email-forgot-message');
        this.ajax_forgot = $('progress_image_login_forgot');

        this.create_customer = $('magegiant-socialogin-create-user');
        this.create_customer_click = $('magegiant-sociallogin-create-new-customer');
        this.create_customer_form = $('magegiant-sociallogin-form-create');
        this.create_form_backto_login = $('magegiant-create-back');
        this.create_button = $('magegiant-button-sociallogin-create');
        this.create_ajax = $('progress_image_login_create');
        this.create_invalid = $('magegiant-invalid-create');

        this.mode = 'form_login';
        this.bindEventHandlers();

    },

    login_handler: function () {
        var login_validator = new Validation('magegiant-sociallogin-form');
        if (login_validator.validate()) {
            var parameters = this.login_form.serialize(true);
            var url = this.options.login_url;
            this.showLoginLoading();
            new Ajax.Request(url, {
                method: 'post',
                parameters: parameters,
                onSuccess: function (transport) {
                    var result = transport.responseText.evalJSON();
                    if (result.success) {
                        location.reload();
                    } else {
                        this.hideLoginLoading();
                        this.showLoginError(result.error);
                    }
                }.bind(this)
            });
        }
    },
    sendpass_handler: function () {
        var login_validator_forgot = new Validation('magegiant-sociallogin-form-forgot');
        this.showSendPassMessage('', true);
        if (login_validator_forgot.validate()) {
            var parameters = this.login_form_forgot.serialize(true);
            var url = this.options.send_pass_url;
            this.showLoginLoading();
            new Ajax.Request(url, {
                    method: 'post',
                    parameters: parameters,
                    onSuccess: function (transport) {
                        var result = transport.responseText.evalJSON();
                        if (result.success) {
                            this.showSendPassMessage(result.message, true);
                        } else {
                            this.showSendPassMessage(result.error, false);
                        }
                        this.hideLoginLoading();
                    }.bind(this)}
            );
        }
    },
    forgot_handler: function () {
        this.hideFormLogin();
        this.mode = 'form_forgot';
        this.showFormForgot();
    },
    showLogin_handler: function () {
        this.hideFormForgot();
        this.hideCreateForm();
        this.mode = 'form_login';
        this.showFormLogin();
    },
    showCreate_handler: function () {
        this.hideFormLogin();
        this.hideFormForgot();
        this.mode = 'form_create';
        this.showCreateForm();
    },
    createAcc_handler: function () {
        var login_validator_create = new Validation('magegiant-sociallogin-form-create');
        if (login_validator_create.validate()) {
            var parameters = this.create_customer_form.serialize(true);
            var url = this.options.create_url;
            this.showLoginLoading();
            new Ajax.Request(url, {
                    method: 'post',
                    parameters: parameters,
                    onSuccess: function (transport) {
                        var result = transport.responseText.evalJSON();
                        if (result.success) {
                            window.location = window.location;
                        } else {
                            this.showCreateError(result.error);
                            this.hideLoginLoading();
                        }
                    }.bind(this)}
            );
        }
    },
    bindEventHandlers: function () {
        /* Now bind the submit button for logging in */
        if (this.login_button) {
            this.login_button.observe(
                'click', this.login_handler.bind(this));
        }
        if (this.forgot_a) {
            this.forgot_a.observe(
                'click', this.forgot_handler.bind(this));
        }
        if (this.forgot_a_back) {
            this.forgot_a_back.observe(
                'click', this.showLogin_handler.bind(this));
        }
        if (this.forgot_button) {
            this.forgot_button.observe(
                'click', this.sendpass_handler.bind(this));
        }
        if (this.create_customer_click) {
            this.create_customer_click.observe(
                'click', this.showCreate_handler.bind(this));
        }
        if (this.create_form_backto_login) {
            this.create_form_backto_login.observe(
                'click', this.showLogin_handler.bind(this));
        }
        if (this.create_button) {
            this.create_button.observe(
                'click', this.createAcc_handler.bind(this));
        }
        document.observe('keypress', this.keypress_handler.bind(this));
    },
    keypress_handler: function (e) {
        var code = e.keyCode || e.which;
        if (code == 13) {
            if (this.mode == 'form_login') {
                this.login_handler();
            } else if (this.mode == 'form_forgot') {
                this.sendpass_handler();
            } else if (this.mode == 'form_create') {
                this.createAcc_handler();
            } else {
            }
        }
    },
    showLoginLoading: function () {
        this.image_login.style.display = "block";
        this.ajax_forgot.style.display = "block";
        this.create_ajax.style.display = "block";
        this.popup_content.style.opacity = 0.4
    },
    hideLoginLoading: function () {
        this.image_login.style.display = "none";
        this.ajax_forgot.style.display = "none";
        this.create_ajax.style.display = "none";
        this.popup_content.style.opacity = 1
    },
    showLoginError: function (error) {
        this.invalid_email.show();
        this.invalid_email.update(error);
    },
    hideFormLogin: function () {
        this.login_form_div.style.display = "none";
    },
    showFormLogin: function () {
        this.login_form_div.style.display = "";
    },
    hideFormForgot: function () {
        this.forgot_title.style.display = "none";
        this.login_form_forgot.style.display = "none";
    },
    showFormForgot: function () {
        this.forgot_title.style.display = "";
        this.login_form_forgot.style.display = "";
    },
    showSendPassMessage: function (message, type) {
        if (type) {
            this.email_forgot_message.removeClassName('magegiant-invalid-email');
            this.email_forgot_message.addClassName('magegiant-success-email');
        }
        else {
            this.email_forgot_message.removeClassName('magegiant-success-email');
            this.email_forgot_message.addClassName('magegiant-invalid-email');
        }
        this.email_forgot_message.show();
        this.email_forgot_message.update(message);
    },
    showCreateForm: function () {
        this.login_form_div.style.display = "none";
        this.create_customer_click.style.display = "none";
        this.create_customer.style.display = "";
    },
    hideCreateForm: function () {
        this.create_customer.style.display = "none";
        this.login_form_div.style.display = "";
        this.create_customer_click.style.display = "";
    },
    showCreateError: function (error) {
        this.create_invalid.show();
        this.create_invalid.update(error);
    }
});
/*****************Social Popup***********************/
var SocialPopup = Class.create({
    initialize: function (w, h, l, t) {
        this.screenX = typeof window.screenX != 'undefined' ? window.screenX : window.screenLeft;
        this.screenY = typeof window.screenY != 'undefined' ? window.screenY : window.screenTop;
        this.outerWidth = typeof window.outerWidth != 'undefined' ? window.outerWidth : document.body.clientWidth;
        this.outerHeight = typeof window.outerHeight != 'undefined' ? window.outerHeight : (document.body.clientHeight - 22);
        this.width = w ? w : 500;
        this.height = h ? h : 270;
        this.left = l ? l : parseInt(this.screenX + ((this.outerWidth - this.width) / 2), 10);
        this.top = t ? t : parseInt(this.screenY + ((this.outerHeight - this.height) / 2.5), 10);
        this.features = (
            'width=' + this.width +
                ',height=' + this.height +
                ',left=' + this.left +
                ',top=' + this.top
            );
    },
    openPopup: function (url, title) {
        window.open(url, title, this.features);
    },
    closePopup: function () {
        window.close();
    }
});
function openSocial(url, title, w, h, l, t) {
    var ssPopup = new SocialPopup(w, h, l, t);
    ssPopup.openPopup(url, title);
}
