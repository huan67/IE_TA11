var tableHtml = '<tr style="cursor: pointer;"> <td style="width: 47%;"> <input required="" class="keyboardInput" style="width: 95%;" type="text" name="flashcard_foreground_word_flashcard_index" value=""/></td><td style="width: 47%;"> <input required="" class="source_word" style="width: 95%;" type="text" name="flashcard_background_word_flashcard_index" value=""/></td><td style="width: 6%;"> <a href="javascript:void(0)" class="add_flashcard"><span class="dashicons dashicons-insert"></span></a> <a href="javascript:void(0)" class="remove_flashcard"><span class="dashicons dashicons-remove"></span></a> </td></tr>';

$ = jQuery;
var custom_uploader;
var caller;


//check if word exist
$(document).on('focusout', '.source_word', function () {
    var thisField = $(this);
    var newText = thisField.val();
    thisField.parent().find('.wrod-exist-error').remove();
    $('.source_word').each(function () {
        console.log(thisField);
        console.log($(this));
        var oldText = $(this).val();
        if (thisField[0] !== $(this)[0] && newText == oldText) {
            thisField.after('<p style="color: red;" class="wrod-exist-error">Word already exist, please change it to avoid losing data.</p>');
            //alert("Word already exist, please change it to avoid losing data.")
        }
    });
});
//show / hide flashcard section
$(document).on('click', '.flashcard-header', function () {
    if ($(this).next().is(':hidden')) {
//hide all open tables
        $('.flashcard_table').slideUp(100);
        $(this).next().slideDown(100);
    } else {
        $(this).next().slideUp(100);
    }
});

//remove falshcard
$(document).on('click', '.remove_flashcard', function () {
    $(this).parent().parent().remove();
});
//add more cards
$(document).on('click', '.add_flashcard', function () {
//get last table
    var lastTabelIndex = $('#flashcard_table_sortable tr').length;
    if (lastTabelIndex <= 9) {
        pretableHtml = tableHtml.replace(/flashcard_index_plus/g, parseInt(lastTabelIndex));
        $('.flashcard_table tbody').append(pretableHtml.replace(/flashcard_index/g, parseInt(lastTabelIndex)));
        $("#flashcard_table_sortable").sortable("refresh");
    }else{
        alert('Maximum of 10 terms allowed.');
    }
});
jQuery(document).ready(function ($) {


    $("#flashcard_table_sortable").sortable({
        update: function (event, ui) {
            //adjust the index
            var index = 0;
            $('#flashcard_table_sortable tr').each(function () {
                $(this).find('.source_word').attr('name', 'flashcard_background_word_' + index);
                $(this).find('.keyboardInput').attr('name', 'flashcard_foreground_word_' + index);
                index = index + 1;
            });
        }
    });

});
