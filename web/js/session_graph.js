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
    },
    render: function(worker_id, start, stop) {
        $('.worker-line-'+worker_id+' .hourContainer > div').each(function() {
            var timestamp = parseInt($(this).data('timestamp'));
            if(start < timestamp && stop > timestamp) {
                $(this).addClass('active');
            }
        });
    }
};

pistol88.worksess_graph.init();
