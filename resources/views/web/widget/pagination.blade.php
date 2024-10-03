<div class="g_pagination noselect">
    <table style="width: 1px; margin: auto;"><tr>
        @if(\App\Repositories\PaginationRepository::$page > 2)
            <td>
                <div class="link_round_border_block">
                    <a href="{{ request()->url()  }}" title="{{ trans('nav.first page') }}">
                        @if(\App\W::$isAmp)
                            <amp-img alt="{{ trans('nav.first page') }}" class="icon" src="{{ asset('images/icon/first_page.svg') }}" width="24" height="24"></amp-img>
                        @else
                            <img data-src="{{ asset('images/icon/first_page.svg') }}" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=" alt="{{ trans('nav.first page') }}" class="lazyload icon" width="24" height="24">
                        @endif
                    </a>
                </div>
            </td>
        @endif
        @if(\App\Repositories\PaginationRepository::$page > 1)
            <td>
                <div class="link_round_border_block">
                    <a href="{{ request()->url().\App\Repositories\PaginationRepository::addUrlParams('-1') }}" title="{{ trans('nav.previous page') }}">
                        @if(\App\W::$isAmp)
                            <amp-img alt="{{ trans('nav.previous page') }}" class="icon" src="{{ asset('images/icon/chevron_left.svg') }}" width="24" height="24"></amp-img>
                        @else
                            <img data-src="{{ asset('images/icon/chevron_left.svg') }}" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=" alt="{{ trans('nav.previous page') }}" class="lazyload icon" width="24" height="24">
                        @endif
                    </a>
                </div>
            </td>
        @endif
        @if(\App\Repositories\PaginationRepository::$page > 1 || !empty(\App\Repositories\PaginationRepository::$isNextPage))
            <td>
                <div class="round_border_block" style="cursor: default;">{{ trans('nav.page_') }}&nbsp;{{ \App\Repositories\PaginationRepository::$page }}</div>
            </td>
        @endif
        @if(!empty(\App\Repositories\PaginationRepository::$isNextPage))
            <td>
                <div class="link_round_border_block">
                    <a href="{{ request()->url().\App\Repositories\PaginationRepository::addUrlParams('+1') }}" title="{{ trans('nav.next page') }}">
                        @if(\App\W::$isAmp)
                            <amp-img alt="{{ trans('nav.next page') }}" class="icon" src="{{ asset('images/icon/chevron_right.svg') }}" width="24" height="24"></amp-img>
                        @else
                            <img data-src="{{ asset('images/icon/chevron_right.svg') }}" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=" alt="{{ trans('nav.next page') }}" class="lazyload icon" width="24" height="24">
                        @endif
                    </a>
                </div>
            </td>
        @endif
    </tr></table>
</div>

@if((\App\W::$isAmp || \App\AppConf::$is_dev_mode) && \App\Application::pushLoad('g_pagination_css'))
    @push('css_1')
        @if(!\App\W::$isAmp) <style> @endif

            .g_pagination{
                text-align: center;
                margin-bottom: 32px;
                margin-top: 64px;
                position: relative;
                z-index: 99;
            }
            .g_pagination table td{
                padding: 0 2px;
            }
            .g_pagination .round_border_block,
            .g_pagination .link_round_border_block{
                border-radius: 2px;
                height: 52px;
                width: 60px;
                overflow: hidden;
                font-size: 16px;
                line-height: 36px;
                margin-bottom: 8px;
                background-color: #fff;
                border: 1px solid #dadce0;
                display: inline-block;
                cursor: pointer;
                color: #777777;
                font-weight: normal;
                text-decoration: none;
                text-transform: lowercase;
            }

            .g_pagination .link_round_border_block:hover {
                background-color: #f1f3f4;
                color: #2f4255;
                -webkit-transition: background-color .25s;
                transition: background-color .25s;
            }

            .g_pagination .link_round_border_block a{
                margin: 6px 8px 6px 0;
                padding: 0 18px;
                display: inline-block;
                color: inherit;
                font-size: inherit;
                font-weight: inherit;
                line-height: inherit;
                text-decoration: inherit;
            }
            .g_pagination .round_border_block{
                background-color: transparent;
                color: #bbb;
                line-height: 52px;
                font-size: 13px;
                border-color: #e8e8e8;
            }
            .g_pagination .link_round_border_block .icon{
                margin-top: 9px;
                margin-bottom: 0;
            }

            .g_pagination .link_round_border_block.arrow_scroll{
                background-color: transparent;
                border-width: 0;
            }

            .g_pagination .link_round_border_block.arrow_scroll img,
            .isAmp .g_pagination .link_round_border_block.arrow_scroll amp-img{
                margin-top: 8px;
            }

            @if(!\App\W::$isAmp) </style> @endif
    @endpush
@endif