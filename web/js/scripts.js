if (typeof pistol88 == "undefined" || !pistol88) {
    var pistol88 = {};
}

pistol88.worksess = {
    init: function() {
        $(document).on('click', '.worksess-button', this.control)
    },
    control: function() {
        if($(this).hasClass('worksess-start')) {
            pistol88.worksess.start($(this).attr('href'), this);
        } else if($(this).hasClass('worksess-stop')) {
            pistol88.worksess.stop($(this).attr('href'), this);
        }

        return false;
    },
    start: function(url, link) {
        pistol88.worksess.sendData(url, link);
    },
    stop: function(url, link) {
        pistol88.worksess.sendData(url, link);
    },
    sendData: function(url, link) {
        $.post(url, {ajax: true},
            function(json) {
                if(json.result == 'fail') {
                    console.log(json.error);
                }
                else {
                    $(link).replaceWith(json.button);
                }
                
                var userId = $(link).data('user-id');
                
                if(userId) {
                    $('.worksess-info'+userId).replaceWith(json.info);
                } else {
                    $('.worksess-info').replaceWith(json.info);
                }
                
            }, "json");
    },
};

pistol88.worksess.init();
