
<!DOCTYPE html>
<head>
    <title>Pusher Test</title>
    <script src="//js.pusher.com/4.0/pusher.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
    <script>


        Pusher.logToConsole = true;

        var pusher = 0;
        var channel = 0;
        var panel_id = 1;
        var user_id = '';
        var filter = '';
        var content = '';
        var scroll_flag = 1;
        var users_arr = [];
        function run() {

            if(pusher){
                pusher.disconnect();
            }

            pusher = new Pusher('e1971a6494fa3904eb50', {
                encrypted: true
            });

            /*pusher = new Pusher('c26b8f2fc59e727b11c9', {
                encrypted: true
            });*/


            var user_channel = user_id + '-channel';
            if(user_channel == 'null-channel') {
                user_channel = '-channel';
            }

            channel = pusher.subscribe(user_channel);
            channel.bind(filter + '-event', function (data) {
                var date = new Date();
                var datetime = '';
                if(date.getHours()<10){
                    datetime += '0' + date.getHours();
                }else {
                    datetime += date.getHours();
                }
                if(date.getMinutes()<10){
                    datetime += ':0' + date.getMinutes();
                }else {
                    datetime += ':' + date.getMinutes();
                }
                if(date.getSeconds()<10){
                    datetime += ':0' + date.getSeconds();
                }else {
                    datetime += ':'+date.getSeconds();
                }

                content = '<div class="panel panel-default" id="panel'+panel_id+'"><button type="button" class="close" data-target="#panel'+panel_id+'" data-dismiss="alert"> <span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>';
                content += '<div class="panel-body"><pre><b>' + datetime + ' >> </b>' + data.data + '</pre></div>';
                if(data.comment != ''){
                    content += '<div class="panel-footer" style="background-color: #5e5e5e; color: #fff;">'+data.comment+'</div>';
                }
                content += '</div>';
                $('#code_container').append(content);
                panel_id++;
                if(checkScroll()){
                    $('body').scrollTop(99999999999);
                }
            });

            if(user_id == ''){
                $.each(users_arr, function( index, value ) {
                    channel = pusher.subscribe(value[0]+ '-channel');
                    channel.bind(filter + '-event', function (data) {

                        var date = new Date();
                        var datetime = '';
                        if(date.getHours()<10){
                            datetime += '0' + date.getHours();
                        }else {
                            datetime += date.getHours();
                        }
                        if(date.getMinutes()<10){
                            datetime += ':0' + date.getMinutes();
                        }else {
                            datetime += ':' + date.getMinutes();
                        }
                        if(date.getSeconds()<10){
                            datetime += ':0' + date.getSeconds();
                        }else {
                            datetime += ':'+date.getSeconds();
                        }

                        content = '<div class="panel panel-default" id="panel'+panel_id+'"><button type="button" class="close" data-target="#panel'+panel_id+'" data-dismiss="alert"> <span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>';
                        content += '<div class="panel-body"><pre><b>' + datetime + ' >> </b>' + data.data + '</pre></div>';
                        if(data.comment != ''){
                            content += '<div class="panel-footer" style="background-color: #5e5e5e; color: #fff;">'+data.comment+'</div>';
                        }
                        content += '</div>';
                        $('#code_container').append(content);
                        panel_id++;
                        if(checkScroll()){
                            $('body').scrollTop(99999999999);
                        }
                    });
                });
            }
        }

        function checkScroll() {
            if(scroll_flag) {
                return true;
            }
        }

        $(function() {
            $.each(users_arr, function( index, value ) {
                $('#select_user').append('<option value="'+value[0]+'">'+value[0]+' | '+value[1]+'</option>');
            });


            $('#select_user').change(function(){
                user_id = $('#select_user').val();
                run();
            });

            $('#filter_val').change(function(){
                $('#start_btn').removeClass('btn-success').addClass('btn-default');
                $('#stop_btn').removeClass('btn-default').addClass('btn-danger');
                if(pusher){
                    pusher.disconnect();
                }
            });

            $('#start_btn').click(function(){
                $(this).removeClass('btn-default').addClass('btn-success');
                $('#stop_btn').removeClass('btn-danger').addClass('btn-default');
                user_id = $('#select_user').val();
                filter = $('#filter_val').val();
                run();
            });

            $('#stop_btn').click(function(){
                $(this).removeClass('btn-default').addClass('btn-danger');
                $('#start_btn').removeClass('btn-success').addClass('btn-default');
                pusher.disconnect();
            });

            $('#clear_btn').click(function(){
                $('#code_container').empty();
            });

            $(document).keyup(function(e){
                if(e.keyCode == 27){
                    scroll_flag = scroll_flag ? 0 : 1;
                    if(scroll_flag){
                        $('body').scrollTop(99999999999);
                    }
                }
            });
        });

    </script>
    <style>
        *{
            font-size: 12px;
        }
        .panel, .panel-default{
            margin-bottom: 5px;
        }
        .panel-body, .panel-footer{
            padding: 0;
            margin: 0;
        }
        pre{
            margin: 0;
            border: none;
            padding: 0 !important;
            font-size: 12px;
        }
    </style>
</head>
<body style="overflow: auto; background-color: #393939;">

<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">Test</a>
        </div>
        <div class="navbar-form navbar-right">
            <button id="clear_btn" class="btn btn-default">Clear</button>
            <select class="form-control" id="select_user">
                <option value="">all users</option>
                <option value="null">users null</option>
            </select>
            <label for="select_user">&nbsp;&nbsp;</label>
            <input id="filter_val" type="text" class="form-control" placeholder="filter">
            <button id="start_btn" class="btn btn-default">Start</button>
            <button id="stop_btn" class="btn btn-danger">Stop</button>
        </div>


    </div>
</nav>


<div id="code_container" class="" style="padding-top: 70px; overflow: hidden;">

</div>



</body>


