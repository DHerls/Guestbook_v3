let SignaturePad = require("signature_pad");

Vue.config.ignoredElements = ['canvas']
const app = new Vue({
    el: "#app",
    data: {

    }
});


var canvas = document.querySelector("canvas");

var signaturePad = new SignaturePad(canvas);