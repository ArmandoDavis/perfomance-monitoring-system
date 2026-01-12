
var date_shortcut_id = '';
$(".date_shortcut").click(function(){
    var element_id = this.id;
    var date_shortcut_id = element_id.substr(13);
    var number_selected = $('#number_selected').val();
    getDateRangeShortcut(date_shortcut_id,number_selected);
});

//add number on selected date shortcut
$('.number_list').on('change',function () {

    var element_id = $('.date_shortcut.badge-success').attr('id');
    if(element_id == null){
         date_shortcut_id = 7
    }else{
         date_shortcut_id = element_id.substr(13);
    }

    var number_selected = $('#number_selected').val();
    getDateRangeShortcut(date_shortcut_id,number_selected);
});


/*Get date range shortcut*/
function getDateRangeShortcut(date_shortcut_id,number_selected)
{
    posting = $.post( base_url + "/user/task/date_range_shortcut" , {
        // client_id: client_id,
        assigned_date: element_id_value('assigned_date'),
        date_shortcut_id: date_shortcut_id,
        number_selected: number_selected,
        _method : "GET" } );
    posting.done(function( data ) {
        //Action/Response
        $('#assigned_date').val(data.assigned_date).change();
        $('#end_date').val(data.end_date).change();

        /*Class*/
        $(".date_shortcut").removeClass("badge-success");
        $(".date_shortcut").addClass("badge-default");
        $("#date_shortcut"+date_shortcut_id).removeClass("badge-default");
        $("#date_shortcut"+date_shortcut_id).addClass("badge-success");

    });
}
