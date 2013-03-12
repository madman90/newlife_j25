/*
# mod_sp_quickcontact - Ajax based quick contact Module by JoomShaper.com
# -----------------------------------------------------------------------	
# Author    JoomShaper http://www.joomshaper.com
# Copyright (C) 2010 - 2012 JoomShaper.com. All Rights Reserved.
# License - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomshaper.com
*/
var sp_sc = new Class({
    version: '1.0.0',
    Implements: [Options, Events],
    options: {}, initialize: function (submit, options) {
        this.setOptions(options);
        this.submit_btn = document.id(submit);
        this.submit_btn.addEvent('click', function (e) {
            this.sendemail()
        }.bind(this))
    }, checkEmail: function () {
        var check = /^[\w\.\+-]{1,}\@([\da-zA-Z-]{1,}\.){1,}[\da-zA-Z-]{2,6}$/;
        if (!check.test(this.options.email.get('value'))) {
            return false
        }
        return true
    }, sendemail: function () {
        var modId = this.options.modId;
        var yname = this.options.name.get('value');
        var yemail = this.options.email.get('value');
//        var subject = this.options.subject.get('value');
        var ymessage = this.options.message.get('value');
        var status = this.options.status;
        var err_msg = this.options.err_msg;
        var email_warn = this.options.email_warn;
        var wait_text = this.options.wait_text;
        var failed_text = this.options.failed_text;

        var message_el = this.options.message;
        var email_el = this.options.email;
        var name_el = this.options.name;

        if ((yname == '') || (yname == this.options.name_text)) {
            this.options.name.set("class", "sp_qc_error")
        } else {
            this.options.name.erase('class')
        }
        if ((yemail == '') || (yemail == this.options.email_text)) {
            this.options.email.set("class", "sp_qc_error")
        } else {
            this.options.email.erase('class')
        }
        if ((ymessage == '') || (ymessage == this.options.msg_text)) {
            this.options.message.set("class", "sp_qc_error")
        } else {
            this.options.message.erase('class')
        }
        if ((yname == '') || (yname == this.options.name_text) || (yemail == '') || (yemail == this.options.email_text) || (ymessage == '') || (ymessage == this.options.msg_text)) {
//            status.innerHTML = '<p class="sp_qc_warn">' + err_msg + '</p>';
            return false
        }
        if (!this.checkEmail()) {
//            status.innerHTML = '<p class="sp_qc_warn">' + email_warn + '</p>';
            this.options.email.set("class", "sp_qc_error");
            return false
        }
//        var param = "name=" + yname + "&email=" + yemail + "&subject=" + subject + "&message=" + ymessage + "&modId=" + modId;
        var param = "name=" + yname + "&email=" + yemail + "&message=" + ymessage + "&modId=" + modId;
        var sendmail = new Request({
            url: this.options.ajax_url,
            method: 'get',
            onRequest: function () {
                status.set('html', '<p class="sp_qc_loading">' + wait_text + '</p>')
            }, onSuccess: function (responseText) {
                status.set('html', responseText);
                message_el.set('value','');
                email_el.set('value','');
                name_el.set('value','');
            }, onFailure: function () {
                status.set('html', '<p class="sp_qc_warn">' + failed_text + '</p>')
            }
        }).send(param)
    }
});