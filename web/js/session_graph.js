if (typeof pistol88 == "undefined" || !pistol88) {
    var pistol88 = {};
}

pistol88.worksess_graph = {
    init: function() {
        this.render();
        setInterval(function() {
            //$('.worsess-graph-update').click();
        }, 5000);
    },
    render: function() {
        $('div.hourContainer').each(function(){
            var $div = $(this);
            var height = $div.closest('td').height();
            $div.height(height);
        });
    }
};

pistol88.worksess_graph.init();
