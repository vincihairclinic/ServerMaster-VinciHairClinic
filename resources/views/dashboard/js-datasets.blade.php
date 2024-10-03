@push('js')
    <script>
        var datasets = {
            pluck : function (dataset, param) {
                var res = [];
                $.each(datasets[dataset], function (i, v) {
                    if(!empty(v[param])){
                        res.push(v[param]);
                    }
                });
                return res;
            },
            getById : function (dataset, id) {
                return $.grep(datasets[dataset], function(e){
                    return e.id == id;
                })[0];
            },
            getNameById : function (dataset, id) {
                var name = datasets.getParamById(dataset, id, 'name');
                if(empty(name)){
                    name = datasets.getParamById(dataset, id, 'name_'+userLanguageId);
                }
                if(empty(name)){
                    $.each(datasets.pluck('language', 'id'), function (i, v) {
                        if(userLanguageId != v){
                            name = datasets.getParamById(dataset, id, 'name_'+v);
                            if(!empty(name)){
                                return false;
                            }
                        }
                    });
                }
                return !empty(name) ? name : '';
            },
            getParamById : function (dataset, id, param) {
                return datasets.getById(dataset, id)[param];
            }
        };

        datasets.tmp = [];
        datasets.UserRole = JSON.parse('{!! json_encode(\App\Models\Datasets\UserRole::findAll(), JSON_UNESCAPED_UNICODE) !!}');
        datasets.UserStatus = JSON.parse('{!! json_encode(\App\Models\Datasets\UserStatus::findAll(), JSON_UNESCAPED_UNICODE) !!}');
        datasets.UserLogAction = JSON.parse('{!! json_encode(\App\Models\Datasets\UserLogAction::findAll(), JSON_UNESCAPED_UNICODE) !!}');
    </script>
@endpush