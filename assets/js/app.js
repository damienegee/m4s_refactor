/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
// import './styles/app.css';
// start the Stimulus application
import '../bootstrap';
//const $ = require('jquery');
// require jQuery normally
import $ from 'jquery';
// create global $ and jQuery variables
global.$ = global.jQuery = $;

// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
require('bootstrap');
require('bootstrap-select');

//require('datatables.net-bs4')();
// or you can include specific pieces
// require('bootstrap/js/dist/tooltip');
// require('bootstrap/js/dist/popover');

import Stepper from 'bs-stepper';
import * as SignaturePad from 'signature_pad';

$(document).ready(function() {
    $('[data-toggle="popover"]').popover();

    // close every alert after 2 seconds
    if($(".flashalert")) {
        setTimeout(function() {
            $(".flashalert").alert('close');
        }, 2000);
    }

    if(document.getElementById('signature-pad')) {
        var canvas = document.getElementById('signature-pad');
        var signaturePad = new SignaturePad.default(canvas, {
            backgroundColor: 'rgba(255, 255, 255, 0)',
            penColor: 'rgb(0, 0, 0)'
        });

        window.addEventListener("resize", canvas);
        resizeCanvas(canvas, signaturePad);
        
        var saveButton = document.getElementById('save');
        var cancelButton = document.getElementById('loan_reset');
    
        saveButton.addEventListener('click', (event) => {
            if( signaturePad.isEmpty()) {
                return false;
            }
            var data = signaturePad.toDataURL('image/png');
            var input = document.getElementById('loan_signature');
            input.value = data;
        });
    
        cancelButton.addEventListener('click', (event) => {
            signaturePad.clear();
        });
    }

    var searchbox = document.getElementsByClassName("btn dropdown-toggle btn-light");
    if(searchbox !== undefined) {
        $(searchbox[0]).on('click', function(){
            var element = document.getElementsByClassName('dropdown-menu dropdown-menu-right show');
            if(element[0]) {
                //console.dir(element[0]);
                var input = element[0].getElementsByTagName('input');
                console.log(input);
                input[0].focus();
            }
        });
    }

    if (document.querySelector('.bs-stepper')) {
        var stepper = new Stepper(document.querySelector('.bs-stepper'), {
            linear: false
        });
        // stepper.next();
        // stepper.to(1);

        $("[name='btnNext1']").on('click', function(){
            stepper.next();
        });
    }

});

function resizeCanvas(canvas, signaturePad) {
    var ratio =  Math.max(window.devicePixelRatio || 1, 1);
    canvas.width = canvas.offsetWidth * ratio;
    canvas.height = canvas.offsetHeight * ratio;
    canvas.getContext("2d").scale(ratio, ratio);
    signaturePad.clear(); // otherwise isEmpty() might return incorrect value
}

import 'bs-stepper/dist/css/bs-stepper.min.css'
import '../styles/global.scss';
import '../styles/prism.css';
import '../styles/bootstrap-select.min.css'


console.log('Hello Webpack Encore! Edit me in assets/app.js');
