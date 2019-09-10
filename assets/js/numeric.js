function numericPositiveOnly(id) {
    var widget = document.getElementById(id);
    var val = widget.value;
    var allowed = [
        48, 49, 50, 51, 52, 53, 54, 55, 56, 57
    ];
    var allow;
    var res = "";
    for( var i = val.length - 1; i > -1 ; i--) {
        allow = false;
        for ( var j = 0; j < allowed.length; j++) {
            if(val.charCodeAt(i) === allowed[j]) {
                allow = true;
                break;
            }
        }
        if(allow) res += val[i];
    }
    var separator = ".";
    var stepper = 3;
    var counter = 0;
    var res_ws = "";
    for (i = 0; i < res.length; i++) {
        res_ws += res[i];
        counter++;
        if(counter === stepper && i !== res.length - 1) {
            res_ws += separator;
            counter = 0;
        }
    }
    res = "";
    for( i = res_ws.length - 1; i > -1 ; i--) res += res_ws[i];
    widget.value = res;
}
function addNumericSeperator(input) {
input = input.toString();
var separator = ".";
    var stepper = 3;
    var counter = 0;
    var res = "";
    for (i = 0; i < input.length; i++) {
        res += input[i];
        counter++;
        if(counter === stepper && i !== input.length - 1) {
            res += separator;
            counter = 0;
        }
    }
console.log("for input : "+input+" result : "+res);
return res;
}
function getNumericFromNumericWithSeperator(num_with_separator) {
    var allowed = [
        48, 49, 50, 51, 52, 53, 54, 55, 56, 57
    ];
    var res = "";
    for( var i = 0; i < num_with_separator.length; i++) {
        for ( var j = 0; j < allowed.length; j++) {
            if(num_with_separator.charCodeAt(i) === allowed[j]) {
                res += num_with_separator[i];
                break;
            }
        }
    }
    return parseInt(res);
}