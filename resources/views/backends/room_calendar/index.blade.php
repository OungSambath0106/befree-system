@extends('backends.master')

@push('css')
    <link rel="stylesheet" href="{{asset('backend/plugins/fullcalendar-4.2.0/core/main.css')}}">
    <link rel="stylesheet" href="{{asset('backend/plugins/fullcalendar-4.2.0/daygrid/main.css')}}">
    <link rel="stylesheet" href="{{asset('backend/plugins/daterangepicker/daterangepicker.css')}}">
    <style>
        /* .preview {
            margin-block: 12px;
            text-align: center;
        }
        .tab-pane {
            margin-top: 20px
        } */

        .flex-column {
            display: flex;
            flex-direction: column;
        }
        .event-name{
            text-overflow: ellipsis;
            white-space: nowrap;
            overflow: hidden;
        }
        .fc-view > table {
            border-inline: 1px solid #DDDDDD;
        }
        .dates-calendar .fc-event {
            position: relative;
            height: 72px;
            border-radius: 0;
            top: -25px;
            padding-top: 25px;
            background: none!important;
            border: none;
            display: flex;
            font-size: 14px;
            font-weight: 500;
            align-items: center;
            justify-content: center;
            padding-bottom: 25px;
        }
        tr:first-child > td > .fc-day-grid-event {
            margin-top: 2px;
        }
        .dates-calendar .fc-event .fc-title {
            padding: 1px 5px;
            border-radius: 2px;
            background: #008824;
            color: #fff;
            display: inline-block;
            cursor: pointer;
        }
        .dates-calendar .fc-event.active-event .fc-title {
            background: #A63D00;
            color: #fff;
        }
        .dates-calendar .fc-event.blocked-event .fc-title {
            background: #717171;
            color: #fff;
        }
        .dates-calendar .fc-event.full-book-event .fc-title {
            background: #CE1E1E;
            color: #fff;
        }

        .nav-stacked>li.active>a, .nav-stacked>li.active>a:hover {
            border-left: none;
        }

        #dates-calendar .loading{

        }

        .toggle.btn {
            min-width: 91px;
            min-height: 34px;
        }
        .checkbox, .radio {
            margin-top: 0px;
            margin-bottom: 0px;
        }

        .onoffswitch {
            position: relative; width: 90px;
            -webkit-user-select:none; -moz-user-select:none; -ms-user-select: none;
        }

        .onoffswitch-checkbox {
            display: none;
        }

        .onoffswitch-label {
            display: block; overflow: hidden; cursor: pointer;
            border: 2px solid #999999; border-radius: 20px;
        }

        .onoffswitch-inner {
            display: block; width: 200%; margin-left: -100%;
            -moz-transition: margin 0.3s ease-in 0s; -webkit-transition: margin 0.3s ease-in 0s;
            -o-transition: margin 0.3s ease-in 0s; transition: margin 0.3s ease-in 0s;
        }

        .onoffswitch-inner:before, .onoffswitch-inner:after {
            display: block; float: left; width: 50%; height: 30px; padding: 0; line-height: 30px;
            font-size: 14px; color: white; font-family: Trebuchet, Arial, sans-serif; font-weight: bold;
            -moz-box-sizing: border-box; -webkit-box-sizing: border-box; box-sizing: border-box;
        }

        .onoffswitch-inner:before {
            content: "Open";
            padding-left: 10px;
            background-color: #ff6d00; color: #FFFFFF;
        }

        .onoffswitch-inner:after {
            content: "Close";
            padding-right: 10px;
            background-color: #EEEEEE; color: #999999;
            text-align: right;
        }

        .onoffswitch-switch {
            display: block; width: 18px; margin: 6px;
            background: #FFFFFF;
            border: 2px solid #999999; border-radius: 20px;
            position: absolute; top: 0; bottom: 0; right: 56px;
            -moz-transition: all 0.3s ease-in 0s; -webkit-transition: all 0.3s ease-in 0s;
            -o-transition: all 0.3s ease-in 0s; transition: all 0.3s ease-in 0s;
        }

        .onoffswitch-checkbox:checked + .onoffswitch-label .onoffswitch-inner {
            margin-left: 0;
        }

        .onoffswitch-checkbox:checked + .onoffswitch-label .onoffswitch-switch {
            right: 0px;
        }

        .onoffswitch4 {
            position: relative; width: 90px;
            -webkit-user-select:none; -moz-user-select:none; -ms-user-select: none;
        }

        .onoffswitch4-checkbox {
            display: none;
        }

        .onoffswitch4-label {
            display: block; overflow: hidden; cursor: pointer;
            border: 2px solid #ff6d00; border-radius: 0px;
        }

        .onoffswitch4-inner {
            display: block; width: 200%; margin-left: -100%;
            -moz-transition: margin 0.3s ease-in 0s; -webkit-transition: margin 0.3s ease-in 0s;
            -o-transition: margin 0.3s ease-in 0s; transition: margin 0.3s ease-in 0s;
        }

        .onoffswitch4-inner:before, .onoffswitch4-inner:after {
            display: block; float: left; width: 50%; height: 30px; padding: 0; line-height: 26px;
            font-size: 14px; color: white; font-family: Trebuchet, Arial, sans-serif; font-weight: bold;
            -moz-box-sizing: border-box; -webkit-box-sizing: border-box; box-sizing: border-box;
            border: 2px solid transparent;
            background-clip: padding-box;
        }

        .onoffswitch4-inner:before {
            content: "Open";
            padding-left: 10px;
            background-color: #FFFFFF; color: #ff6d00;
        }

        .onoffswitch4-inner:after {
            content: "Close";
            padding-right: 10px;
            background-color: #FFFFFF; color: #666666;
            text-align: right;
        }

        .onoffswitch4-switch {
            display: block; width: 25px; margin: 0px;
            background: #ff6d00;
            position: absolute; top: 0; bottom: 0; right: 65px;
            -moz-transition: all 0.3s ease-in 0s; -webkit-transition: all 0.3s ease-in 0s;
            -o-transition: all 0.3s ease-in 0s; transition: all 0.3s ease-in 0s;
        }

        .onoffswitch4-checkbox:checked + .onoffswitch4-label .onoffswitch4-inner {
            margin-left: 0;
        }

        .onoffswitch4-checkbox:checked + .onoffswitch4-label .onoffswitch4-switch {
            right: 0px;
        }

        .cmn-toggle
        {
            position: absolute;
            margin-left: -9999px;
            visibility: hidden;
        }

        .cmn-toggle + label
        {
            display: block;
            position: relative;
            cursor: pointer;
            outline: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        input.cmn-toggle-round-flat + label
        {
            padding: 2px;
            width: 75px;
            height: 30px;
            background-color: #dddddd;
            -webkit-border-radius: 60px;
            -moz-border-radius: 60px;
            -ms-border-radius: 60px;
            -o-border-radius: 60px;
            border-radius: 60px;
            -webkit-transition: background 0.4s;
            -moz-transition: background 0.4s;
            -o-transition: background 0.4s;
            transition: background 0.4s;
        }

        input.cmn-toggle-round-flat + label:before, input.cmn-toggle-round-flat + label:after
        {
            display: block;
            position: absolute;
            content: "";
        }

        input.cmn-toggle-round-flat + label:before
        {
            top: 2px;
            left: 2px;
            bottom: 2px;
            right: 2px;
            background-color: #fff;
            -webkit-border-radius: 60px;
            -moz-border-radius: 60px;
            -ms-border-radius: 60px;
            -o-border-radius: 60px;
            border-radius: 60px;
            -webkit-transition: background 0.4s;
            -moz-transition: background 0.4s;
            -o-transition: background 0.4s;
            transition: background 0.4s;
        }

        input.cmn-toggle-round-flat + label:after
        {
            top: 4px;
            left: 4px;
            bottom: 4px;
            width: 22px;
            background-color: #dddddd;
            -webkit-border-radius: 52px;
            -moz-border-radius: 52px;
            -ms-border-radius: 52px;
            -o-border-radius: 52px;
            border-radius: 52px;
            -webkit-transition: margin 0.4s, background 0.4s;
            -moz-transition: margin 0.4s, background 0.4s;
            -o-transition: margin 0.4s, background 0.4s;
            transition: margin 0.4s, background 0.4s;
        }

        input.cmn-toggle-round-flat:checked + label
        {
            background-color: #ff6d00;
        }

        input.cmn-toggle-round-flat:checked + label:after
        {
            margin-left: 45px;
            background-color: #ff6d00;
        }
    </style>
@endpush
@section('contents')

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>{{ __('Room Calendar') }}</h3>
            </div>
            <div class="col-sm-6" style="text-align: right">
            </div>
        </div>
    </div>
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-sm-6">
                                <h3 class="card-title">{{ __('Room Calendar List') }}</h3>
                            </div>

                        </div>
                    </div>
                    <!-- /.card-header -->

                    {{-- table --}}
                    <div class="card-body">
                        <div class="row" style="margin-inline: 1px">

                            @if(!isset($rooms) || $rooms->isEmpty())
                                <div class="col-sm-12">
                                    <div class="alert alert-warning" style="padding: 12px">
                                        <strong>No rooms found!</strong>
                                    </div>
                                </div>
                            @else
                                <div class="col-12 d-flex">
                                    <div class="col-md-12" style="background: white;padding: 15px;">
                                        <div id="dates-calendar" class="dates-calendar"></div>
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div id="bravo_modal_calendar" class="modal fade">
        <div class="modal-dialog modal-lg  modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{ __('Date information') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form class="form_modal_calendar  " novalidate onsubmit="return false">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label >{{__('Date Ranges')}}</label>
                                    <input readonly type="text" class="form-control has-daterangepicker">
                                </div>
                            </div>
                            <div class="col-md-6" style="margin-bottom: 7px;">
                                <div class="form-group">
                                    <label >{{__('Available for booking')}}</label>
                                    <br>
                                    {{-- <label ><input true-value=1 false-value=0 type="checkbox" v-model="form.is_active"> {{__('Available for booking?')}}</label> --}}
                                    {{-- <div class="checkbox">
                                        <label>
                                            <input data-toggle="toggle" true-value=1 false-value=0 data-on="OPEN" data-off="CLOSE" type="checkbox" v-model="form.is_active" class="available_toggle" value="1">
                                        </label>
                                    </div> --}}
                                    <div class="onoffswitch4">
                                        <input type="checkbox" name="onoffswitch4" true-value=1 false-value=0 class="onoffswitch4-checkbox available_toggle" id="myonoffswitch4" v-model="form.is_active">
                                        <label class="onoffswitch4-label" for="myonoffswitch4">
                                            <span class="onoffswitch4-inner"></span>
                                            <span class="onoffswitch4-switch"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 price_div" v-show="form.is_active">
                                <div class="form-group">
                                    <label >{{__('Price')}}</label>
                                    <input type="number"  v-model="form.price" class="form-control">
                                </div>
                            </div>
                            {{-- <div class="col-md-6 number_div" v-show="form.is_active">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label >{{__('Total rooms')}}</label>
                                            <input type="number"  v-model="form.total_number" class="form-control" min="0">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label >{{__('Available rooms')}}</label>
                                            <input type="number"  v-model="form.number" class="form-control" min="0" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div> --}}
                            {{-- <div class="col-md-6 d-none" v-show="form.is_active">
                                <div class="form-group">
                                    <label >{{__('Instant Booking?')}}</label>
                                    <br>
                                    <label><input true-value=1 false-value=0  type="checkbox"  v-model="form.is_instant" > {{__("Enable instant booking")}}</label>
                                </div>
                            </div> --}}
                        </div>
                    </form>
                    <div v-if="lastResponse.message">
                        <br>
                        <div  class="alert" :class="!lastResponse.status ? 'alert-danger':'alert-success'">@{{ lastResponse.message }}</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times-circle"></i> {{__('Close')}}</button>
                    <button type="button" class="btn btn-primary" @click="saveForm"><i class="fa fa-pencil-alt"></i> {{__('Save changes')}}</button>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="modal fade modal_form" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>

@endsection
@push('js')
{{-- @php
    $fullcalendar_lang_file = session()->get('user.language', config('app.locale') ) . '.js';
@endphp --}}
<!-- TODO -->
{{-- @if(file_exists(public_path() . '/plugins/fullcalendar/locale/' . $fullcalendar_lang_file))
    <script src="{{ asset('plugins/fullcalendar/locale/' . $fullcalendar_lang_file . '?v=' . $asset_v) }}"></script>
@endif --}}

<script src="{{asset('backend/plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('backend/plugins/daterangepicker/daterangepicker.js')}}"></script>
<script src="{{asset('backend/plugins/fullcalendar-4.2.0/core/main.js')}}"></script>
<script src="{{asset('backend/plugins/fullcalendar-4.2.0/interaction/main.js')}}"></script>
<script src="{{asset('backend/plugins/fullcalendar-4.2.0/daygrid/main.js')}}"></script>

<script src="https://cdn.jsdelivr.net/npm/vue@2"></script>


<script>
    $(document).on('change', '.available_toggle', function () {
        if ($(this).is(':checked')) {
            $('.price_div').removeClass('hidden');
            $('.number_div').removeClass('hidden');
        } else {
            $('.price_div').addClass('hidden');
            $('.number_div').addClass('hidden');
        }
    })
    var calendarEl,calendar,lastId,formModal;
    // formModal = $('.modal');

    $(document).ready(function (e) {
        calendarEl = document.getElementById('dates-calendar');
        lastId = $(e.target).data('id');
        if(calendar){
            calendar.destroy();
        }
        calendar = new FullCalendar.Calendar(calendarEl, {
            buttonText:{
                today:  '{{ __('Today') }}',
            },
            locale: "{{ session()->get('user.language', config('app.locale') ) }}",
            plugins: [ 'dayGrid' ,'interaction'],
            header: {},
            selectable: true,
            selectMirror: false,
            allDay:false,
            editable: false,
            eventLimit: true,
            defaultView: 'dayGridMonth',
            // firstDay: daterangepickerLocale.first_day_of_week,
            firstDay: 1,
            events:{
                url:"{{route('admin.room.room_rate_calendar.load_dates')}}",
                extraParams:{
                    // id:lastId,
                    id: `{{ request('rate_plan_id') }}`,
                }
            },
            loading:function (isLoading) {
                if(!isLoading){
                    $(calendarEl).removeClass('loading');
                }else{
                    $(calendarEl).addClass('loading');
                }
            },
            select: function(arg) {
                formModal.show({
                    start_date:moment(arg.start).format('MM/DD/YYYY'),
                    end_date:moment(arg.end).format('MM/DD/YYYY'),
                });
            },
            eventClick:function (info) {
                var form = Object.assign({},info.event.extendedProps);
                form.start_date = moment(info.event.start).format('MM/DD/YYYY');
                form.end_date = moment(info.event.start).format('MM/DD/YYYY');
                if (!form.total_number) {
                    form.total_number = form.number;
                }
                console.log(form);
                formModal.show(form);
            },
            eventRender: function (info) {
                $(info.el).find('.fc-title').html(info.event.title);
            }
        });
        calendar.render();
    });

    @if (request('room_id'))
        $(`.room_{{ request('room_id') }} a`).trigger('click');
    @else
        $('.event-name:first-child a').trigger('click');
    @endif

    formModal = new Vue({
        el:'#bravo_modal_calendar',
        data:{
            lastResponse:{
                status:null,
                message:''
            },
            form:{
                id:'',
                price:'',
                start_date:'',
                end_date:'',
                is_instant:'',
                enable_person:0,
                min_guests:0,
                max_guests:0,
                is_active:0,
                number:1
            },
            formDefault:{
                id:'',
                price:'',
                start_date:'',
                end_date:'',
                is_instant:'',
                enable_person:0,
                min_guests:0,
                max_guests:0,
                is_active:0,
                number:1
            },
            person_types:[

            ],
            person_type_item:{
                name:'',
                desc:'',
                min:'',
                max:'',
                price:'',
            },
            onSubmit:false
        },
        methods:{
            show:function (form) {
                $(this.$el).modal('show');
                this.lastResponse.message = '';
                this.onSubmit = false;

                if(typeof form !='undefined'){
                    this.form = Object.assign({},form);
                    if(typeof this.form.person_types == 'object'){
                        this.person_types = Object.assign({},this.form.person_types);
                    }

                    if(form.start_date){
                        var drp = $('.has-daterangepicker').data('daterangepicker');
                        drp.setStartDate(moment(form.start_date).format("MM/DD/YYYY"));
                        drp.setEndDate(moment(form.end_date).format("MM/DD/YYYY"));

                    }
                }
            },
            hide:function () {
                $(this.$el).modal('hide');
                this.form = Object.assign({},this.formDefault);
                this.person_types = [];
            },
            saveForm:function () {
                this.form.target_id = `{{ request('rate_plan_id') }}`;
                var me = this;
                me.lastResponse.message = '';
                if(this.onSubmit) return;

                if(!this.validateForm()) return;

                this.onSubmit = true;
                this.form.person_types = Object.assign({},this.person_types);

                // Get the value of the date range picker
                var dateRangePickerValue = $('.has-daterangepicker').val();

                // Split the date range into start and end dates
                var dates = dateRangePickerValue.split(' - ');
                this.form.start_date = moment(dates[0], 'MM/DD/YYYY').format('YYYY-MM-DD');
                this.form.end_date = moment(dates[1], 'MM/DD/YYYY').format('YYYY-MM-DD');

                $.ajax({
                    url:'{{route('admin.room.room_rate_calendar.store')}}',
                    data:this.form,
                    dataType:'json',
                    method:'post',
                    success:function (json) {
                        if(json.status){
                            if(calendar)
                            calendar.refetchEvents();
                            me.hide();
                            toastr.success(json.message);
                        }
                        if (json.success == false) {
                            toastr.error(json.message)
                        }
                        me.lastResponse = json;
                        me.onSubmit = false;
                        toastr.error(json.message);
                    },
                    error:function (e) {
                        me.onSubmit = false;
                    }
                });
            },
            validateForm:function(){
                if(!this.form.start_date) return false;
                if(!this.form.end_date) return false;

                return true;
            },
            addItem:function () {
                console.log(this.person_types);
                this.person_types.push(Object.assign({},this.person_type_item));
            },
            deleteItem:function (index) {
                this.person_types.splice(index,1);
            }
        },
        created:function () {
            var me = this;
            this.$nextTick(function () {
                $('.has-daterangepicker').daterangepicker({ "locale": {"format": "MM/DD/YYYY"}})
                    .on('apply.daterangepicker',function (e,picker) {
                        console.log(picker);
                        me.form.start_date = picker.startDate.format('MM/DD/YYYY');
                        me.form.end_date = picker.endDate.format('MM/DD/YYYY');
                    });

                $(me.$el).on('hide.bs.modal',function () {

                    this.form = Object.assign({},this.formDefault);
                    this.person_types = [];

                });

            })
        },
        mounted:function () {
            // $(this.$el).modal();
        }
    });

</script>
@endpush
