function run_method(lib, method, params, arg = []) {
    return $.ajax({
        type: "POST",
        url: "php/archive/reciever.php",
        data: {
            'class': lib,
            'method': method,
            'params': params,
            'arg': arg
        },
        dataType: 'json',
        success: function () {}
    });
}

function delay(fn, ms) {
    let timer = 0;
    return function (...args) {
        clearTimeout(timer);
        timer = setTimeout(fn.bind(this, ...args), ms || 0);
    };
}