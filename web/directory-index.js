// filesize fpr human readable file sizes
/* @see http://cdn.filesizejs.com/filesize.min.js */
"use strict";!function(a){function b(a){var b=arguments.length<=1||void 0===arguments[1]?{}:arguments[1],e=[],f=0,g=void 0,h=void 0,i=void 0,j=void 0,k=void 0,l=void 0,m=void 0,n=void 0,o=void 0,p=void 0,q=void 0;if(isNaN(a))throw new Error("Invalid arguments");return i=b.bits===!0,o=b.unix===!0,h=b.base||2,n=void 0!==b.round?b.round:o?1:2,p=void 0!==b.spacer?b.spacer:o?"":" ",q=b.symbols||b.suffixes||{},m=b.output||"string",g=void 0!==b.exponent?b.exponent:-1,l=Number(a),k=0>l,j=h>2?1e3:1024,k&&(l=-l),0===l?(e[0]=0,e[1]=o?"":i?"b":"B"):((-1===g||isNaN(g))&&(g=Math.floor(Math.log(l)/Math.log(j)),0>g&&(g=0)),g>8&&(g=8),f=2===h?l/Math.pow(2,10*g):l/Math.pow(1e3,g),i&&(f=8*f,f>j&&8>g&&(f/=j,g++)),e[0]=Number(f.toFixed(g>0?n:0)),e[1]=10===h&&1===g?i?"kb":"kB":d[i?"bits":"bytes"][g],o&&(e[1]=e[1].charAt(0),c.test(e[1])&&(e[0]=Math.floor(e[0]),e[1]=""))),k&&(e[0]=-e[0]),e[1]=q[e[1]]||e[1],"array"===m?e:"exponent"===m?g:"object"===m?{value:e[0],suffix:e[1],symbol:e[1]}:e.join(p)}var c=/^(b|B)$/,d={bits:["b","Kb","Mb","Gb","Tb","Pb","Eb","Zb","Yb"],bytes:["B","KB","MB","GB","TB","PB","EB","ZB","YB"]};"undefined"!=typeof exports?module.exports=b:"function"==typeof define&&define.amd?define(function(){return b}):a.filesize=b}("undefined"!=typeof window?window:global);

var sizes = document.getElementsByClassName("file-size");
for (var i = 0; i < sizes.length; i++) {
    sizes[i].textContent = filesize(sizes[i].getAttribute("data-src"));
}

// Moment JS for modification time stamps
var now = moment();
$('time').each(
    function(i, e) {
        var time = moment($(e).attr('datetime'));
        $(e).html('<span>' + time.from(now) + '</span>');
    }
);
