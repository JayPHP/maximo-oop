function toggle_fields(){
    if ($('input, select').attr('disabled')) {
        $('input, select').removeAttr('disabled');
        $('#submit').attr('disabled','disabled');
        $('#edit, #save').toggleClass('hidden');
    } else {
        $('input, select').attr('disabled', 6);
        $('#submit').removeAttr('disabled');
        $('#edit, #save').toggleClass('hidden');
    }
}