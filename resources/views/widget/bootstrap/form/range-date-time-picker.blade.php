<div class="sorting-time data-table-sorting">
    <label class="title">Date</label>
    <div class="time">
        <div class="time-value" onclick="openCalendar{{ $id }}()" style="white-space: break-spaces;">
            <img src="{{ asset('/images/calendar.svg') }}" />
            <span id="start_date">From</span> - <span id="end_date">To</span>
        </div>
        <div class="col-12 d-flex justify-content-end data-table-button-container">
            <div class="position-relative">
                <div class="calendar-modal" id="calendar_modal_{{ $id }}">
                    <div class="calendar-option">
                        <ul>
                            <li onclick="getLastWeek{{ $id }}()">Last week</li>
                            <li onclick="getLastMonth{{ $id }}()">Last mounth</li>
                            <li onclick="getLastYear{{ $id }}()">Last year</li>
                        </ul>
                    </div>
                    <div class="calendar">
                        <div class="calendar-container">
                            <input class="d-none" id="{{ $id }}-from-filter-input">
                            <input class="d-none" id="{{ $id }}-to-filter-input">
                        </div>
                        <div class="btn-container">
                            <button onclick="clearCalendar{{ $id }}()" class="cancel">Cancel</button>
                            <button onclick="closeCalendar{{ $id }}()" id="click-event-{{ $id }}-filter-input" class="done">Done</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@once
    @push('js')
        <script src="{{ asset('/js/plugins/lightpick.js') }}"></script>
    @endpush
    @push('css_1')
        <link rel="stylesheet" href="{{ asset('/css/plugins/lightpick.css') }}">
    @endpush
@endonce

@push('js')
    <script>
        let {{ $id }}Picker = null;
        function openCalendar{{ $id }}() {
            if (!$('#calendar_modal_{{ $id }}').hasClass('show')) {
                $('#calendar_modal_{{ $id }}').addClass('show');
                setTimeout(function () {
                    window.addEventListener('click',closeCalendar{{ $id }});
                },10);
            }
        }


        function closeCalendar{{ $id }}(e){
            if (!e){
                window.removeEventListener('click',closeCalendar{{ $id }});
                $('#calendar_modal_{{ $id }}').removeClass('show');
            } else if (!e.target.closest('#calendar_modal_{{ $id }}')){
                window.removeEventListener('click',closeCalendar{{ $id }});
                $('#calendar_modal_{{ $id }}').removeClass('show');
            }
        }

        function getLastWeek{{ $id }}() {
            if ({{ $id }}Picker){
                let start = moment().startOf('isoWeek').format('MM/DD/YYYY');
                let end = moment().endOf('isoWeek').format('MM/DD/YYYY');
                {{ $id }}Picker.setDateRange(start, end);
            }
        }
        function getLastMonth{{ $id }}() {
            if ({{ $id }}Picker){
                let start = moment().startOf('month').format('MM/DD/YYYY');
                let end = moment().endOf('month').format('MM/DD/YYYY');
                {{ $id }}Picker.setDateRange(start, end);
            }
        }
        function getLastYear{{ $id }}() {
            if ({{ $id }}Picker){
                let start = moment().startOf('year').format('MM/DD/YYYY');
                let end = moment().endOf('year').format('MM/DD/YYYY');
                {{ $id }}Picker.setDateRange(start, end);
            }
        }
        function clearCalendar{{ $id }}() {
            if ({{ $id }}Picker){
                {{ $id }}Picker.setDateRange('', '');
            }
        }
        function initLightPicker{{ $id }}(){
            let date_from = document.getElementById('created_at-from-filter-input');
            let date_to = document.getElementById('created_at-to-filter-input');
            {{ $id }}Picker = new Lightpick({
                field: date_from,
                secondField: date_to,
                singleDate: false,
                format: 'MM/DD/YYYY',
                dropdowns: false,
                lang: 'en',
                inline: true,
            });
            changeDateInput{{ $id }}(date_from.value, date_to.value);
        }
        function changeDateInput{{ $id }}(date_from, date_to) {
            $('#start_date').html(date_from ? date_from : 'From');
            $('#end_date').html(date_to ? date_to : 'To');
        }
    </script>
@endpush
@push('css_1')
    <style>


        .sorting-time .time-value {
            font-family: 'Open Sans', sans-serif;
            font-style: normal;
            font-weight: normal;
            font-size: 18px;
            display: flex;
            align-items: center;
            line-height: 34px;
            letter-spacing: -0.08px;
            color: #000000;
        }
        .sorting-time .time-value img{
            margin-right: 10px;
        }


        .calendar-modal{
            position: absolute;
            right: 100%;
            top: calc(100% + 10px);
            background: #fff;
            padding: 40px 30px;
            z-index: 100;
            display: none;
            width: 660px;
            border-radius: 10px;
            grid-template-columns: 270px 300px;
            transform: translateY(-100px);
            column-gap: 30px;
            animation: openCalendar 0.15s forwards reverse;
            filter: drop-shadow(0px 6px 12px rgba(165, 165, 165, 0.25));
        }
        .calendar-modal.show {
            animation: openCalendar 0.15s forwards;
            display: grid;
        }
        @keyframes openCalendar {
            0% {
                display: none;
                transform: translateY(-100px);
                opacity: 0;
            }
            1% {
                display: grid;
            }
            100% {
                display: grid;
                transform: translateY(0);
                opacity: 1;
            }
        }
        .calendar-modal li{
            text-decoration: none;
            display: block;
            font-family: 'Open Sans', sans-serif;
            font-style: normal;
            font-weight: 600;
            font-size: 18px;
            line-height: 25px;
            cursor: pointer;
            padding: 15px;
            border-bottom: 1px solid #e8e8e8;
            color: #3E3E3E;
        }
        .calendar-modal .btn-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            padding: 20px 30px 10px;
        }

        .calendar-modal .btn-container button.cancel{
            font-family: 'Open Sans', sans-serif;
            font-style: normal;
            font-weight: bold;
            font-size: 14px;
            line-height: 19px;
            background: none;
            outline: none;
            border: 0;
            width: 60px;
            color: #3E3E3E;
        }
        .calendar-modal .btn-container button.done{
            font-family: 'Open Sans', sans-serif;
            font-style: normal;
            font-weight: bold;
            font-size: 14px;
            line-height: 19px;
            background: {{ $color ?? 'rgba(25, 25, 25, 0.4)' }};
            border-radius: 5px;
            color: #FFFFFF;
            width: 100px;
            padding: 10px;
            border: 0;
            outline: 0;
        }
        .lightpick{
            box-shadow: none;
        }
        .lightpick__day {
            display: flex;
            height: 30px;
            background-position: center center;
            background-size: contain;
            background-repeat: no-repeat;
            font-size: 13px;
            justify-content: center;
            align-items: center;
            cursor: default;
        }
        .lightpick__days {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            row-gap: 10px;
        }
        .lightpick__day.is-start-date.is-in-range, .lightpick__day.is-end-date.is-in-range.is-flipped, .lightpick__day.is-end-date.is-in-range, .lightpick__day.is-start-date.is-in-range.is-flipped {
            background-color: {{ $color ?? 'rgba(25, 25, 25, 0.4)' }};
        }
        .lightpick__day.is-in-range{
            background-color: {{ $color ?? 'rgba(25, 25, 25, 0.4)' }};
            color: white;
        }
        .lightpick__day.is-start-date, .lightpick__day.is-end-date, .lightpick__day.is-start-date:hover, .lightpick__day.is-end-date:hover{
            background-blend-mode: soft-light;
        }
        .lightpick__month-title-bar{
            position: relative;
        }
        .lightpick__toolbar{
            position: absolute;
            left: 0;
            right: 0;
            top: 0;
            bottom: 0;
            justify-content: space-between;
        }
        .lightpick__month-title{
            width: 100%;
            text-align: center;
        }
        .lightpick__month-title > .lightpick__select.lightpick__select-years{
            display: none;
        }
        .lightpick__month-title > .lightpick__select:disabled{
            color: #3E3E3E;
            opacity: 1;
            text-align-last: center;
            width: 132px;
        }
        .lightpick__toolbar button{
            background: transparent;
            color: transparent;
            position: relative;
        }
        .lightpick__toolbar button.lightpick__next-action:after{
            position: absolute;
            right: 15px;
            content: url("{{ asset('/images/calendar-shape.svg') }}");
        }
        .lightpick__previous-action, .lightpick__next-action, .lightpick__close-action {
            margin: 0;
        }
        .lightpick__toolbar button.lightpick__previous-action:before{
            position: absolute;
            left: 15px;
            content: url("{{ asset('/images/calendar-shape.svg') }}");
            transform: rotate(180deg);
        }

    </style>
@endpush