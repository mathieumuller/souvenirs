// global javascripts
var $ = require ('jquery');
require('bootstrap-sass');
var selectpicker = require('bootstrap-select');
var toastr = require('toastr');

$(function() {
    $('.selectpicker').selectpicker();

    $('.flash-messages').find('.flash-message').each(function(){
        var method = $(this).data('method');
        var message = $(this).data('message');

        toastr[method](message);
    });
});