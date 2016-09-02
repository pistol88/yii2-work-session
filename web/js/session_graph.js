if (typeof pistol88 == "undefined" || !pistol88) {
    var pistol88 = {};
}

pistol88.worksess_graph = {
    init: function() {
        if($('.worsess-graph-update').length) {
            setInterval(function() {
                $('.worsess-graph-update').click();
            }, 40000);
        }

        $(document).on('workSessionStart', function() { $('.worsess-graph-update').click(); })
        $(document).on('click', '.worksession-graph td > div > div.active', this.openInfoWindow);
    },
    openInfoWindow: function() {
        $('#session-info-window').modal('show');
        $.get(session_info_window_data, {userSessionId: $(this).data('user-session-id')}, function(data) {
            $('#session-info-window .modal-body').html(data);
        });
    },
    render: function(worker_id, start, stop, user_session_id) {
        $('.worker-line-'+worker_id+' .hourContainer > div').each(function() {
            var timestamp = parseInt($(this).data('timestamp'));
            if((start < timestamp+60 && stop > timestamp-60)) {
                $(this).addClass('active').data('user-session-id', user_session_id);
            }
        });
    }
};

pistol88.worksess_graph.init();
