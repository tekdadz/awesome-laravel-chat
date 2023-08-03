@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('cruds.transaction.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <form action="" id="filtersForm">
                    <div class="input-group">
                        <input type="text" name="from-to" class="form-control mr-2" id="date_filter">
                        <span class="input-group-btn">
                    <input type="submit" class="btn btn-primary" value="Submit">
                </span>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="box box-info">
                    <div class="box-header">
                        <h3 class="box-title">Asteroid Chart</h3>
                    </div>
                    <div class="box-body">
                        {!! ChartJS::renderCanvas('asteroids') !!}
                    </div>
                </div>
            </div>
        </div>
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-asteroids">
            <thead>
            <tr>
                <th width="10">

                </th>
                <th>
                    {{ trans('cruds.transaction.fields.id') }}
                </th>
                <th>
                    {{ trans('cruds.transaction.fields.velocity') }}
                </th>
                <th>
                    {{ trans('cruds.transaction.fields.close') }}
                </th>
                <th>
                    {{ trans('cruds.transaction.fields.average') }}
                </th>
            </tr>
            </thead>
        </table>
    </div>
</div>



@endsection

@section('styles')
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css">
@endsection

@section('scripts')
@parent
<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<script>
    $(function () {
        let searchParams = new URLSearchParams(window.location.search)
        let dateInterval = searchParams.get('from-to');
        let start = moment().subtract(6, 'days');
        let end = moment();

        if (dateInterval) {
            dateInterval = dateInterval.split(' - ');
            start = dateInterval[0];
            end = dateInterval[1];
        }

        $('#date_filter').daterangepicker({
            "showDropdowns": true,
            "showWeekNumbers": true,
            "alwaysShowCalendars": true,
            startDate: start,
            endDate: end,
            locale: {
                format: 'YYYY-MM-DD',
                firstDay: 1,
            },
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()]
            }
        });

        let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)


        let dtOverrideGlobals = {
            buttons: dtButtons,
            processing: true,
            serverSide: true,
            retrieve: true,
            aaSorting: [],
            ajax: {
                url: "{{ route('admin.asteroids.index') }}",
                data: {
                    'from-to': searchParams.get('from-to'),
                }
            },
            columns: [
                { data: 'placeholder', name: 'placeholder' },
                { data: 'id', name: 'id' },
                { data: 'velocity', name: 'velocity' },
                { data: 'close', name: 'close' },
                { data: 'average', name: 'average' }
        ],
        order: [[ 1, 'asc' ]],
            pageLength: 100,
    };
        $('.datatable-asteroids').DataTable(dtOverrideGlobals);
        $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
            $($.fn.dataTable.tables(true)).DataTable()
                .columns.adjust();
        });
    });

</script>
<script src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.min.js"></script>

{!! ChartJS::renderScripts('asteroids') !!}
@endsection
