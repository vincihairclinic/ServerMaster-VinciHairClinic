@if(false) <style> @endif
    .grecaptcha-badge{
        z-index: 9999;
    }

    .layout.grid {
        display: grid;
        grid-template-columns: calc(100% - {{ \App\AppConf::$width_menu[0] }}px) {{ \App\AppConf::$width_menu[0] }}px;
    }

    .layout.grid .content_layer {
        grid-row: 1;
        grid-column: 1;
    }

    .layout.grid .g_menu {
        grid-row: 1;
        grid-column: 2;
        position: relative;
    }


    @media screen and (max-width: {{ \App\AppConf::$width_content[0] + \App\AppConf::$width_menu[0] + 62 }}px) {
        .layout.grid {
            grid-template-columns: calc(100% - {{ \App\AppConf::$width_menu[1] }}px) {{ \App\AppConf::$width_menu[1] }}px;
        }
    }
    @media screen and (max-width: {{ \App\AppConf::$width_content[2] + \App\AppConf::$width_menu[1] + 62 }}px) {
        .layout.grid{
            display: block;
        }
        .layout.grid .g_menu {
            display: none;
        }
        .layout.grid .g_menu .space_close{
            display: block;
        }
    }


    .layout.grid .g_float_btn,
    .layout.grid .g_header .search_block {
        right: {{ \App\AppConf::$width_menu[0] }}px;
    }
    .layout.grid .g_header .search_block{
        max-width: calc(100% - {{ \App\AppConf::$width_menu[0] }}px);
    }
    @media screen and (max-width: {{ \App\AppConf::$width_content[0] + \App\AppConf::$width_menu[0] + 62 }}px) {
        .layout.grid .g_float_btn,
        .layout.grid .g_header .search_block {
            right: {{ \App\AppConf::$width_menu[1] }}px;
        }
        .layout.grid .g_header .search_block{
            max-width: calc(100% - {{ \App\AppConf::$width_menu[1] }}px);
        }
    }
    @media screen and (max-width: {{ \App\AppConf::$width_content[2] + \App\AppConf::$width_menu[1] + 62 }}px) {
        .layout.grid .g_float_btn{
            right: 0;
        }
        .layout.grid .g_header .search_block{
            right: 52px;
            max-width: calc(100% - 52px);
        }
    }

@if(false) </style> @endif