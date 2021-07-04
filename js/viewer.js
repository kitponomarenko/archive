let mouse_down = false,
        posX = 0,
        posY = 0,
        innerX = 0,
        innerY = 0,
        moveable = false;

$("#doc_viewport>div").on("mousedown", function (e) {
    innerX = e.pageX - $(this).offset().left + ($(this).css('width').replace('px', '') / 2);
    innerY = e.pageY - $(this).offset().top;
    mouse_down = true;
}).on("mouseup", function () {
    mouse_down = false;
});

$(document).on("mousemove", function (e) {
    if ((mouse_down) && (moveable)) {
        let width_mod = ($('#doc_viewport>div').css('width').replace('px', '') / 2),
            m = {x: (e.pageX - innerX) + width_mod, y: (e.pageY - innerY)};
        $('#doc_viewport>div').css({left: m.x, top: m.y});
    }
});

$(document).on('click', 'html', function () {
    if (mouse_down) {
        mouse_down = false;
    }
});

$(document).on('click', '#doc_zoom_in', function () {
    zoom_in();
});

$(document).on('click', '#doc_zoom_out', function () {
    zoom_out();
});

$('#doc_viewport>div').bind('mousewheel', function (e) {
    if (e.originalEvent.wheelDelta / 120 > 0) {
        zoom_in();
    } else {
        zoom_out();
    }
});

function zoom_in() {
    let new_zoom = 1,
            current_zoom = define_zoom();
    if (current_zoom < 3) {
        new_zoom = current_zoom + 0.25;
        new_height = $('#doc_viewport>div').data('base_height') * new_zoom;
        new_width = $('#doc_viewport>div').data('base_width') * new_zoom;
        $('#doc_zoom_scale').html(new_zoom * 100);
        $('#doc_viewport>div').css('height', new_height + 'px');
        $('#doc_viewport>div').css('width', new_width + 'px');
        if (new_zoom > 1) {
            moveable = true;
        } else {
            moveable = false;
        }
    }
}

function zoom_out() {
    let new_zoom = '100%',
            current_zoom = define_zoom();
    if (current_zoom >= 1.25) {
        new_zoom = current_zoom - 0.25;
        new_height = $('#doc_viewport>div').data('base_height') * new_zoom;
        new_width = $('#doc_viewport>div').data('base_width') * new_zoom;
        $('#doc_zoom_scale').html(new_zoom * 100);
        $('#doc_viewport>div').css('height', new_height + 'px');
        $('#doc_viewport>div').css('width', new_width + 'px');
        $('#doc_viewport>div').css('top', 0);
        $('#doc_viewport>div').css('left', 0);
        if (new_zoom <= 1) {
            moveable = false;
        } else {
            moveable = true;
        }
    }
}

$(document).on('click', '#doc_zoom_reset', function () {
    $('#doc_viewport>div').css('height', $('#doc_viewport>div').data('base_height') + 'px');
    $('#doc_viewport>div').css('width', $('#doc_viewport>div').data('base_width') + 'px');
    $('#doc_viewport>div').css('top', 0);
    $('#doc_viewport>div').css('left', 0);
    $('#doc_zoom_scale').html('100');
    moveable = false;
});

function define_zoom() {
    let height = $('#doc_viewport').css('height').replace('px', ''),
            current_height = $('#doc_viewport>div').css('height').replace('px', ''),
            current_width = $('#doc_viewport>div').css('width').replace('px', '');
            current_zoom = current_height / height;
            if(current_zoom === 1){
                $('#doc_viewport>div').data('base_height', current_height);
                $('#doc_viewport>div').data('base_width', current_width);
            }

    return current_zoom;
}

$('#doc_viewport>div').bind('contextmenu', function () {
    return false;
});

$(document).on('click', '#doc_close', function () {
    window.location.href = 'lib';
});

$(document).on('click', '[name="doc_page_switch"]', function () {
    window.location.href = $(this).data('link');
});