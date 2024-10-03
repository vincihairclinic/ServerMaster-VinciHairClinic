@if(false) <style> @endif

    .hide-scroll::-webkit-scrollbar {width: 0}
    .hide-scroll{
        overflow: -moz-scrollbars-none;
        -ms-overflow-style: none;
        scrollbar-width: none;
        overscroll-behavior: none;
    }

    .ico{
        display: -webkit-flex;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .v_align{
        display: -webkit-flex;
        display: flex;
        align-items: center;
    }
    .flex{
        display: -webkit-flex;
        display: flex;
        -webkit-flex-flow: row wrap;
    }
    .clear{
        font-size: inherit;
        font-weight: inherit;
        line-height: inherit;
        padding: 0;
        margin: 0;
    }


    img,
    button,
    .btn,
    .noselect *,
    .noselect {
        -webkit-touch-callout: none;
        -webkit-user-select: none;
        -khtml-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    *:focus {outline:none}

    /*--------------------------------------------*/

    * {
        line-height: 160%;
        padding: 0;
        margin: 0;
        /*font-family: "Arial", sans-serif;*/
        font-family: 'Roboto', sans-serif;
        /*font-family: 'Open Sans', sans-serif;*/
        color: inherit;
        box-sizing: border-box;
        -webkit-tap-highlight-color: transparent;
        -webkit-user-drag: none;
        -khtml-user-drag: none;
        -moz-user-drag: none;
        -o-user-drag: none;
        user-drag: none;
        word-wrap: break-word;
    }

    /*p{
        font-size: 13px !important;
        line-height: 140% !important;
    }*/

    ::selection {
        background-color: #d4ecff;
    }

    select,
    textarea,
    input {
        -webkit-appearance: none;
        border: none;
        outline: none;
        -webkit-box-shadow: none;
        -moz-box-shadow: none;
        box-shadow: none;
        padding: 0;
        margin: 0;
    }

    textarea{
        overflow: auto;
    }

    h1, h2, h3, h4 {
        color: #374250;
    }

    h1{
        font-size: 40px;
        line-height: 110%;
    }

    h2{
        font-size: 30px;
        line-height: 120%;
    }

    h3{
        font-size: 24px;
        line-height: 130%;
    }

    h4{
        font-size: 18px;
        line-height: 140%;
    }
    body {
        width: 100%;
        color: #374250;
        font-size: 14px;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        /*background-image: url({{ asset('images/web_body_bg.svg') }});*/
        overflow-x: hidden;
        background-color: #fff;
    }

    a{
        color: #1a73e8;
        text-decoration: underline;
    }

    /*body::-webkit-scrollbar {
        width: 1em;
    }
    body::-webkit-scrollbar-track {
        background-color: #fff;
    }
    body::-webkit-scrollbar-thumb {
        background-color: #202125;
    }
    body::-webkit-scrollbar-track-piece:start {
        background-color: #d4ecff;
    }*/
    @media screen and (max-width: {{ \App\AppConf::$width_content[2] + 20 }}px) {
        body{
            font-size: 13px;
            line-height: 145%;
        }
        h1{
            font-size: 32px;
            line-height: 110%;
        }

        h2{
            font-size: 26px;
            line-height: 120%;
        }

        h3{
            font-size: 24px;
            line-height: 130%;
        }

        h4{
            font-size: 18px;
            line-height: 140%;
        }
    }

    .g_content{
        padding-top: 52px;
    }

    /*@media screen and (max-width: 600px) {
        .g_content {
            padding: 0 3px;
        }
    }*/

    table {
        border-spacing: 0;
        border: 0;
    }

    #gtx-trans{
        display: none !important;
    }

    /*-----------------------------*/

    a:active img{
        transform: scale(0.98);
    }
    a.btn:active{
        transform: scale(0.98);
    }

    /*---------------------------------------*/

    /*.input_shift input::placeholder{color: #bbb; font-size: 18px;}
    .input_shift input:-ms-input-placeholder{color: #bbb; font-size: 18px;}
    .input_shift input::-webkit-input-placeholder{color: #bbb; font-size: 18px;}*/

    .input_shift::-webkit-input-placeholder       {text-indent: 0px;   transition: text-indent 0.3s ease;}
    .input_shift::-moz-placeholder                {text-indent: 0px;   transition: text-indent 0.3s ease;}
    .input_shift:-moz-placeholder                 {text-indent: 0px;   transition: text-indent 0.3s ease;}
    .input_shift:-ms-input-placeholder            {text-indent: 0px;   transition: text-indent 0.3s ease;}
    .input_shift:focus::-webkit-input-placeholder {text-indent: 20px; transition: text-indent 0.3s ease;}
    .input_shift:focus::-moz-placeholder          {text-indent: 20px; transition: text-indent 0.3s ease;}
    .input_shift:focus:-moz-placeholder           {text-indent: 20px; transition: text-indent 0.3s ease;}
    .input_shift:focus:-ms-input-placeholder      {text-indent: 20px; transition: text-indent 0.3s ease;}

    /*---------------------------------------*/


    .s_list_d2 label,
    .s_list_d2 .r1 > span,
    .s_list_d2 .r2 a{
        display: -webkit-flex;
        display: flex;
        -webkit-flex-flow: row wrap;
        align-items: center;
    }
    .s_list_d2 ul{
        overflow: hidden;
    }
    .s_list_d2 li{
        display: -webkit-flex;
        display: flex;
        -webkit-flex-flow: row wrap;
        align-items: center;
        /*border-bottom: 1px solid #f1f0f1;*/
        border-bottom: 1px solid rgba(55,66,80,.06);
        border-bottom: 1px solid rgba(195,207,221,.4);
        background-color: #fff;
        padding: 0;
    }
    .s_list_d2 label{
        width: 100%;
        height: 48px;
        cursor: pointer;
        position: absolute;
        top: 0;
    }
    .s_list_d2 .r1{
        /*background-color: #fafafa;
        background-image: url({{ asset('images/web_body_bg.svg') }});*/
        position: relative;
        font-weight: bold;
        min-height: 48px;
    }
    .s_list_d2 .r2{
        /*background-color: #fff;*/
        color: #374250;
        margin-left: 34px;
        position: relative;
        font-weight: bold;
    }

    .s_list_d2 .r1 > input[type=text],
    .s_list_d2 .r1 > span{
        font-size: 15px;
        color: #374250;
        min-height: 48px;
        -webkit-transition:color .2s;
        transition: color .2s;
        font-weight: bold;
    }
    .s_list_d2 .r2 > input[type=text],
    .s_list_d2 .r2 > span{
        color: #374250;
    }
    .s_list_d2 .r2 > input[type=text],
    .s_list_d2 .r2 > span,
    .s_list_d2 .r2 a{
        color: #374250;
        font-size: 13px;
        font-weight: bold;
        text-decoration: none;
        -webkit-transition:color .2s;
        transition: color .2s;
    }
    .s_list_d2 .r2 a{
        min-height: 48px;
        width: 100%;
    }

    .s_list_d2 .r1 label:hover ~ span{
        color: #ff3f00;
        -webkit-transition:color .2s;
        transition: color .2s;
    }
    .s_list_d2 .r2 label:hover a,
    .s_list_d2 .r2 a:hover{
        color: #ff3f00;
        -webkit-transition:color .2s;
        transition: color .2s;
    }

    .s_list_d2.form_mode .r1 label:hover ~ span{
        color: #000;
        -webkit-transition:color .2s;
        transition: color .2s;
    }
    .s_list_d2.form_mode .r2 label:hover a,
    .s_list_d2.form_mode .r2 a:hover{
        color: #ff9800;
        -webkit-transition:color .2s;
        transition: color .2s;
    }

    .s_list_d2 .sub_list{
        width: 100%;
    }

    .s_list_d2 .r2{
        height: 0;
        -webkit-transition: height .2s;
        transition: height .2s;
        border-bottom: none;
    }
    .s_list_d2 .toggle{
        position: absolute;
    }
    .s_list_d2.open_always .sub_list .r2,
    .s_list_d2 .r1 .toggle:checked ~ .sub_list .r2{
        height: 48px;
        -webkit-transition: height .2s;
        transition: height .2s;
        border-bottom: 1px solid rgba(55,66,80,.06);
        border-bottom: 1px solid rgba(195,207,221,.4);
    }
    .s_list_d2.open_always .sub_list .r2:last-of-type,
    .s_list_d2 .r1 .toggle:checked ~ .sub_list .r2:last-of-type{
        border-bottom: none;
    }
    .s_list_d2 .sub_list li:last-of-type{
        border-bottom: none;
    }


    .s_list_d2 .radio input{
        position: absolute;
        opacity: 0;
        cursor: pointer;
        height: 0;
        width: 0;
    }
    .s_list_d2 .radio span{
        display: none;
    }
    .s_list_d2.form_mode label a{
        pointer-events: none;
    }
    .s_list_d2.form_mode .radio input:checked ~ a{
        color: #ff9800;
    }
    .s_list_d2 a.active{
        color: #ff3f00;
    }
    .s_list_d2.form_mode a.active{
        color: #ff9800;
    }


    .s_list_d2.form_mode .radio {
        display: block;
        position: relative;
        cursor: pointer;
    }
    .s_list_d2.form_mode .radio input {
    }
    .s_list_d2.form_mode .radio span {
        position: absolute;
        top: 14px;
        left: -32px;
        height: 20px;
        width: 20px;
        background-color: #f6f7f9;
        border: 1px solid rgba(195,207,221,0.1);
        border-radius: 50%;
        display: block;
        -webkit-transition:background-color .3s;
        transition: background-color .3s;
    }
    /*.s_list_d2.form_mode .radio:hover input ~ span {
        background-color: #e8e9eb;
        border: none;
        -webkit-transition:background-color .3s;
        transition: background-color .3s;
    }*/
    .s_list_d2.form_mode .radio input:checked ~ span {
        background-color: #ff9800;
        border: none
    }
    .s_list_d2.form_mode .radio span:after {
        content: "";
        position: absolute;
        display: none;
    }
    .s_list_d2.form_mode .radio input:checked ~ span:after {
        display: block;
    }
    .s_list_d2.form_mode .radio span:after {
        top: 5px;
        left: 5px;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: white;
    }

    /*--------------------------------*/

    .g_frame{
        margin: 24px auto;
        max-width: {{ \App\AppConf::$width_content[0] }}px;
    }

    .g_frame.grid_2{
        display: grid;
        grid-template-columns: calc(50% - 10px) calc(50% + 10px);
    }
    .g_frame.grid_4_full{
        display: grid;
        grid-template-columns: calc(25% - 10px) calc(25% + 10px) calc(25% + 10px) calc(25% + 10px);
    }

    @if(!\App\W::$isAmp/* && \App\Repositories\PaginationRepository::$page == 1*/)
    .g_frame.grid_4_full > .col:nth-last-of-type(n+5){
        display: -webkit-flex;
        display: flex;
        -webkit-flex-flow: row wrap;
    }
    @endif

    @media screen and (min-width: {{ \App\AppConf::$width_content[0] + \App\AppConf::$width_menu[1] + 62 + 1 }}px) {
        .g_frame.grid_0_full,
        .g_frame.grid_4_full{
            margin: 24px 0;
            max-width: calc(100% - 32px);
            padding: 0 16px;
        }
        .g_frame.grid_0_full{
            padding-right: 0;
        }
    }
    @media screen and (max-width: {{ \App\AppConf::$width_content[0] + \App\AppConf::$width_menu[1] + 62 }}px) {
        .g_frame.grid_4_full{
            grid-template-columns: calc(50% - 10px) calc(50% + 10px);
        }
        .g_frame.grid_4_full > .col_1,
        .g_frame.grid_4_full > .col_2{
            display: -webkit-flex;
            display: flex;
            -webkit-flex-flow: row wrap;
        }
        .g_frame.grid_4_full > .col_3{
            margin-left: 0;
        }
        .g_frame{
            max-width: {{ \App\AppConf::$width_content[1] }}px;
        }
    }
    @media screen and (max-width: {{ \App\AppConf::$width_content[1] + \App\AppConf::$width_menu[1] + 62 }}px) {
        .g_frame{
            max-width: {{ \App\AppConf::$width_content[2] }}px;
        }
    }
    .g_frame > .col{
        margin-left: 20px;
    }
    .g_frame > .col_1{
        margin-left: 0;
    }
    @media screen and (max-width: {{ \App\AppConf::$width_content[2] + 40 }}px) {
        .g_frame > .col,
        .g_frame > .col_1,
        .g_frame.grid_4_full > .col_3{
            margin-left: 10px;
            margin-right: 0;
        }
        .g_frame > .col_1,
        .g_frame.grid_4_full > .col_3{
            margin-left: 0;
        }
        .g_frame.grid_2,
        .g_frame.grid_4_full{
            grid-template-columns: calc(50% - 5px) calc(50% + 5px);
        }
        .g_frame.cell,
        .g_frame.grid_2,
        .g_frame.grid_4_full{
            margin-left: 10px;
            margin-right: 10px;
        }
    }
    @media screen and (max-width: {{ \App\AppConf::$width_content[2] - (\App\AppConf::$width_content[2]/4) }}px) {
        .g_frame.grid_2,
        .g_frame.grid_4_full{
            display: block;
        }
        .g_frame > .col_1,
        .g_frame > .col{
            margin-left: 0;
            margin-right: 0;
        }
        .g_frame.grid_4_full > .col_2{
            display: block;
        }

    }
@if(false) </style> @endif

@if(\App\W::$isAmp || \App\AppConf::$is_dev_mode)
    @push('css_end')
        @if(!\App\W::$isAmp) <style> @endif
            .material-icons{
                text-transform: none;
            }
        @if(!\App\W::$isAmp) </style> @endif
    @endpush
@endif