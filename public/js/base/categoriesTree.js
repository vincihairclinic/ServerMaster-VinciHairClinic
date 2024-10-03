var categoriesTree = {
    urlGetCategory: null,
    urlGetCategoryAll: null,
    isLoaded: false,
    isAllDataLoaded: 0,
    lastNodeSelected: null,
    isClickNodeDisabled: true,
    isScrolledToWholerow: false,
    ajaxData: {},

    categorySearch: function (e) {
        if(categoriesTree.isAllDataLoaded === 2){
            categoriesTree.isLoaded = false;
            var val = $(e).val();
            if(val && val !== ''){
                $('#jstree').jstree('search', val);
            }else {
                $('#jstree').jstree('clear_search');
            }
            setTimeout(function () {
                $("#jstree").jstree(true).clear_state();
            }, 10);
            setTimeout(function () {
                categoriesTree.isLoaded = true;
            }, 150);
        }else if(categoriesTree.isAllDataLoaded === 0){
            categoriesTree.isAllDataLoaded = 1;
            categoriesTree.isLoaded = false;

            $.ajax({
                url: categoriesTree.urlGetCategoryAll,
                data: categoriesTree.ajaxData,
                dataType: 'json',
                success: function (response) {
                    if(response && response instanceof Array){
                        $('#jstree').jstree(true).settings.core.data = response;
                        $('#jstree').jstree(true).refresh();
                    }

                    setTimeout(function () {
                        categoriesTree.isLoaded = true;
                        categoriesTree.isAllDataLoaded = 2;
                        categoriesTree.categorySearch(e);
                    }, 500);
                },
            });
        }
    },

    expandAllNodes: function() {
        $("#jstree").jstree("open_all");
    },

    closeAllNodes: function () {
        $("#jstree").jstree("close_all");
    },
};

$(function () {

    $('#categoriesTree_search_input').change(function () {
        categoriesTree.categorySearch(this);
    }).on('search', function () {
        categoriesTree.categorySearch(this);
    });

    $('#jstree').bind("loaded.jstree", function (event, data) {
    }).bind("ready.jstree", function (event, data) {
        if(localStorage.getItem('categoriesTree') !== null){
            var state = JSON.parse(localStorage.getItem('categoriesTree'));
            if(state && state.state && state.state.core && state.state.core.open && state.state.core.open.length > 6){
                $("#jstree").jstree(true).clear_state();
            }
        }

        setTimeout(function () {
            categoriesTree.isLoaded = true;
        }, 150);
    }).jstree({
        core: {
            data: {
                url: categoriesTree.urlGetCategory,
                data: function (node) {
                    return {
                        category_id: node.id
                    };
                },
                animation: 400,
            }
        },
        search : {
            show_only_matches : true
        },
        types : {
            '0' : {icon: 'glyphicon glyphicon-align-left'},
            '1' : {icon: 'glyphicon glyphicon-folder-close gray'},
        },
        sort : function(a, b) {
            a1 = this.get_node(a);
            b1 = this.get_node(b);
            if(a1.text == '+'){
                return 1;
            }else if (a1.text == b1.text){
                return (a1.type < b1.type) ? 1 : -1;
            } else {
                if (a1.type == b1.type){
                    return (a1.text > b1.text) ? 1 : -1;
                } else {
                    return (a1.type < b1.type) ? 1 : -1;
                }
            }
        },
        state : {key : "categoriesTree"},
        plugins : ['search', 'changed', 'state', 'types', 'wholerow', 'sort']
    }).on("loaded_node.jstree", function(event, data) {
    }).on('hover_node.jstree', function(e, data) {
        var element = $('#' + data.node.id);
        element.children('.jstree-wholerow').css('height', element.children('a').height() + 'px');
    }).on('select_node.jstree', function (e, data) {

        var isSelectedNode = false;
        if(localStorage.getItem('categoriesTree') !== null){
            localStorage_categoriesTree = JSON.parse(localStorage.getItem('categoriesTree'));
            if(localStorage_categoriesTree && data.node.id == parseInt(localStorage_categoriesTree.state.core.selected)){
                isSelectedNode = true;
            }
        }

        if(isSelectedNode && categoriesTree.lastNodeSelected && categoriesTree.lastNodeSelected.node.id === data.node.id){
            categoriesTree.isClickNodeDisabled = true;
            return true;
        }

        if(!categoriesTree.lastNodeSelected){
            categoriesTree.isClickNodeDisabled = true;
        }else {
            categoriesTree.isClickNodeDisabled = false;
        }

        categoriesTree.lastNodeSelected = data;

        var element = $('#' + data.node.id);
        element.children('.jstree-wholerow').css('height', element.children('a').height() + 'px');

        if(categoriesTree.isLoaded){
            if(!isSelectedNode && data.node.type !== '0'){
                data.instance.toggle_node(data.node);
            }

            var nodesToKeepOpen = [];
            $('#'+data.node.id).parents('.jstree-node').each(function() {
                nodesToKeepOpen.push(this.id);
            });
            nodesToKeepOpen.push(data.node.id);

            $('.jstree-node').each(function() {
                if(nodesToKeepOpen.indexOf(this.id) === -1){
                    $("#jstree").jstree().close_node(this.id);
                }
            });
        }
    }).on('deselect_node.jstree', function (e, data) {
    }).on('changed.jstree', function (e, data) {
    }).on('open_node.jstree', function (e, data) {
        data.instance.set_icon(data.node, 'glyphicon glyphicon-folder-open'+(data.node.type === '1' ? ' gray' : ''));

        setTimeout(function () {
            if(!categoriesTree.isScrolledToWholerow && $('.jstree-wholerow-clicked').get(0)){
                setTimeout(function () {
                    $('#jstree').parent().parent().animate({scrollTop : $('.jstree-wholerow-clicked').position().top}, 300);
                }, 150);
                categoriesTree.isScrolledToWholerow = true;
            }
        }, 1);
    }).on('close_node.jstree', function (e, data) {
        if(data.node.type !== '0'){
            data.instance.set_icon(data.node, 'glyphicon glyphicon-folder-close'+(data.node.type === '1' ? ' gray' : ''));
        }
    }).on('click', function (e) {
        if(!categoriesTree.isClickNodeDisabled && categoriesTree.isLoaded && categoriesTree.lastNodeSelected){
            setTimeout(function () {
                var href = categoriesTree.lastNodeSelected.node.a_attr.href;
                if(href !== '#' && !categoriesTree.isClickNodeDisabled){
                    location.href = href;
                }
            }, categoriesTree.lastNodeSelected.node.type === '0' ? 101 : 11);
        }
    });
});