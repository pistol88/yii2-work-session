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
};

pistol88.worksess_graph.init();
