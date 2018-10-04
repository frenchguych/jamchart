(function($) {

    'use strict';

    $.prototype.track = function(rownum, move, data) {
        this.find(".position").html(rownum);
        this.find(".move").html(move);
        this.find(".artist_name").html(data.artist_name);
        this.find(".name").html(data.name);
        this.find(".album_name").html(data.album_name);
    };

    $(document).ready(function() {
        jQuery.get(
            "rest/data", {}, function(data, textStatus, jqXHR) {
                console.log(data);
                $("#chart_date").html(data.latest_date);
                $("#bestentry").track(data.best_entry.rownum, data.best_entry.move, data.tracks[data.best_entry.id]);
                $("#bestexit").track(data.best_exit.rownum, data.best_exit.move, data.tracks[data.best_exit.id]);
                var chartentry = $("#chartentry");
                var tbody = chartentry.parents("tbody");
                chartentry.remove();
                for (var i = 49; i >= 0; i--)
                {
                    var entry = chartentry.clone();
                    entry.attr("id", "rownum-" + (1 + i));
                    entry.track(data.chart[i].rownum, data.chart[i].move, data.tracks[data.chart[i].id]);
                    tbody.append(entry);
                }
                for (i = 0; i < 10; i++)
                {
                    var mover_rownum = data.biggest_movers[i];
                    $("#rownum-" + mover_rownum).addClass("big_mover");
                }
                $(".hidden").removeClass("hidden");
                $(".visible").removeClass("visible").addClass("hidden");
            }
        );
    });

})(window.jQuery);