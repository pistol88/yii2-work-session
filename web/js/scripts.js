if (typeof pistol88 == "undefined" || !pistol88) {
    var pistol88 = {};
}

pistol88.worksess = {
    init: function() {
        $(document).on('mouseenter','.worksess-table td', this.renderCross);
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
        
        $(document).trigger("workSessionStart", this);
    },
    stop: function(url, link) {
        if(confirm('Действительно?')) {
            pistol88.worksess.sendData(url, link);
            
            $(document).trigger("workSessionStop", this);
        }
    },
    sendData: function(url, link) {
		$('.worksession-graph-container').css('opacity', '0.3');
        $('.worsess-error').remove();
        $.post(url, {ajax: true},
            function(json) {
				$('.worksession-graph-container').css('opacity', '1');
                if(json.result == 'fail') {
                    $(link).after('<p class="worsess-error" style="color: red;">'+json.error+'</p>');
                    console.log(json.error);
                }
                else {
					$(document).trigger("workSessionUpdated", this);
                    var userId = $(link).data('user-id');

                    if(userId) {
                        $('.worksess-info'+userId).replaceWith(json.info);
                    } else {
                        $('.worksess-info').replaceWith(json.info);
                        document.location.reload();
                    }
                    
                    $(link).replaceWith(json.button);
                }
            }, "json");
    },
    renderCross: function () {
        var table = $(this).parents('.worksess-table');
        $('.worksess-table td').removeClass('hover');
        var tr = $(this).parent('tr');
        var Col = tr.find('td').index(this);

        tr.find('td').addClass('hover');
        $(table).find('tr').find('td:eq(' + Col + ')').addClass('hover');
    },
};

pistol88.worksess.init();
