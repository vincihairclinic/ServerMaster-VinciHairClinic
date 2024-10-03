function defaultChosenInit(id, params) {
    id = id ? id : '.chosen-select';
    let chosen = $(id).chosen({
        width: '100%',
        search_contains : true,
        hide_results_on_select: false,
        include_group_label_in_selected: true,
        display_selected_options: false,
        allow_single_deselect: true,
        disable_search_threshold: 10,
        placeholder_text_single: ' ',
        placeholder_text_multiple: ' ',
        create_option: false,
        persistent_create_option: true,
        skip_no_results: true,
    }).on('change', function(evt, params) {
        if($(this).val() !== null && $(this).val() !== ''){
            $(this).parent().addClass('is-dirty');
        }else {
            $(this).parent().removeClass('is-dirty');
        }
    }).on('chosen:showing_dropdown', function() {
        $(this).parent().addClass('is-focused');
    }).on('chosen:hiding_dropdown', function(evt, params) {
        $(this).parent().removeClass('is-focused');
    }).data("chosen");

    if(chosen){
        let autoClose = false;
        let chosen_resultSelect_fn = chosen.result_select;
        chosen.result_select = function(evt) {
            var resultHighlight = null;

            if(autoClose == false){
                evt["metaKey"] = true;
                evt["ctrlKey"] = true;
                resultHighlight = chosen.result_highlight;
            }

            let result = chosen_resultSelect_fn.call(chosen, evt);
            if(autoClose == false && resultHighlight != null)
                resultHighlight.addClass("result-selected");

            return result;
        };
    }
}


