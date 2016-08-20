var old_surprised = null;

/**
 * get the raw markdown summary and display it in a textedit area instead of
 * the rendered HTML
 * param: optional text to append to the textedit area
 */
function make_five_whys_editable() {
    var $summary = $("#five_whys");

    // if a textarea already, append to it
    if ($summary.is('textarea')) {
        $summary.val(function(index, value){
                return value;
            });

        // if not a textarea already, create one and replace the original div with it
    } else {
        $.getJSON(
                  "/events/"+get_current_event_id()+"/five_whys",
                  function(data) {
                      var textarea = $("<textarea></textarea>")
                          .attr({
                                  "id": "five_whys",
                                  "name": "five_whys",
                                  "class": "input-xxlarge editable",
                                  "rows": "10"
                              })
                          .val(data.five_whys);
                      $summary.replaceWith(textarea);
                      $("#five_whys").on("save", five_whys_save);
                  },
                  'json' // forces return to be json decoded
                  );
    }
}


/**
 * Depending on the current state either show the editable summary form or
 * save the markdown summary and render as HTML
 */
function five_whys_save(e, event, history) {
    var new_surprised = $("#five_whys").val();

    var Diff = new diff_match_patch();
    var diff = Diff.diff_main(old_surprised, new_surprised);
    Diff.diff_cleanupSemantic(diff);
    diff = Diff.diff_prettyHtml(diff);
    history.five_whys = diff;
    event.five_whys = new_surprised;

    var html = $("<div></div>");
    html.attr("id", "five_whys");
    html.attr("name", "five_whys");
    html.attr("class", "input-xxlarge editable");
    html.attr("rows", "10");
    html.html(markdown.toHTML($("#five_whys").val()));
    $("#five_whys").remove();
    $("#five_whys_wrapper").append(html);
    $("#five_whys_undobutton").hide();
    $("#five_whys").on("edit", make_five_whys_editable);
}

/**
 * just abort editing and display the stored data as rendered HTML
 */
function five_whys_undo_button() {
    $.getJSON("/events/"+get_current_event_id()+"/five_whys", function(data) {
            $('#five_whys').val(data.five_whys);
        });
}

$("#five_whys").on("edit", make_five_whys_editable);
$("#five_whys_undobutton").on("click", five_whys_undo_button);
$.getJSON("/events/"+get_current_event_id()+"/five_whys", function(data) {
        old_surprised = data.five_whys;
    $("#five_whys").html(markdown.toHTML(data.five_whys));
});

