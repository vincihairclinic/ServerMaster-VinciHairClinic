function showImagePreview(input) {
    if(input.files && input.files[0]){
        if(['image/png', 'image/jpeg', 'image/jpg', 'image/gif'].indexOf(input.files[0].type) !== -1){
            var reader = new FileReader();
            reader.onload = function (e) {
                $(input).attr('value', e.target.result)
                    .parent().find('img').attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }else {
            $(input).val('');
        }
    }
}

function showMediaPreview(input) {
    if(input.files && input.files[0]){
        if(['audio/mp3', 'audio/mpeg', 'video/mp4'].indexOf(input.files[0].type) !== -1){
            var reader = new FileReader();
            reader.onload = function (e) {
                $(input).attr('value', e.target.result)
                    .parent().find('video, audio').attr('src', e.target.result).css({display:'block'})
                    .parent().find('img').css({display:'none'});
            };
            reader.readAsDataURL(input.files[0]);
        }else {
            $(input).val('');
        }
    }
}


function getMediaDuration(e, durationField) {
    if(durationField && durationField != '') {
        $('input[name*="' + durationField + '"').val(e.duration);
    }else {
        return e.duration;
    }
}

$(function () {
    $('input[type="text"], textarea').on('change', function () {
        this.value=this.value.trim();
    });

    autosize($('textarea'));
    autosize.update($('textarea'));

    $('.base-form input').on('keypress', function(e){
        if (e.which == 13){
            e.preventDefault();
        }
    });

    defaultChosenInit();
});