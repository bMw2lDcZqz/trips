@extends('header')

@section('content')
    @if ($errors->first('trip'))
        <div class="alert alert-danger">
            <li>{{ trans('texts.trip_errors') }}</li>
        </div>
    @endif

    {!! Former::open($url)
            ->addClass('col-lg-10 col-lg-offset-1 warn-on-exit trip-form')
            ->onsubmit('return onFormSubmit(event)')
            ->method($method)
            ->autocomplete('off')
            ->rules([]) !!}

    @if ($trip)
      {!! Former::populate($trip) !!}
      {!! Former::populateField('id', $trip->public_id) !!}
    @endif

    <div style="display:none">
        @if ($trip)
            {!! Former::text('id') !!}
        @endif
    </div>

    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-body">
                    {!! Former::select('client')->addOption('', '')->addGroupClass('client-select') !!}
                
                    {!! Former::text('trip_date')->data_bind("datePicker: trip_date, valueUpdate: 'afterkeydown'")->label('Date')
                            ->data_date_format(Session::get(SESSION_DATE_PICKER_FORMAT, DEFAULT_DATE_PICKER_FORMAT))->appendIcon('calendar')->addGroupClass('trip_date') !!}

                    {!! Former::text('vehicle')->label('Vehicle') !!}

                    {!! Former::textarea('purpose')->rows(4)->label('Purpose') !!}

                    {!! Former::text('start_odometer')->label('Start Odometer') !!}
                    {!! Former::text('end_odometer')->label('End Odometer') !!}

                    {!! Former::textarea('notes')->rows(4) !!}
                </div>
            </div>
        </div>
    </div>

    <center class="buttons">

        {!! Button::normal(trans('texts.cancel'))
                ->large()
                ->asLinkTo(URL::to('/trips'))
                ->appendIcon(Icon::create('remove-circle')) !!}

        {!! Button::success(trans('texts.save'))
                ->submit()
                ->large()
                ->appendIcon(Icon::create('floppy-disk')) !!}

    </center>

    {!! Former::close() !!}


    <script type="text/javascript">

    var clients = {!! $clients !!};

    function onFormSubmit(event) {
        @if (Auth::user()->canCreateOrEdit('trip', $trip))
            return true;
        @else
            return false
        @endif
    }

    $(function() {
        // setup clients combobox
        var clientId = {{ $clientPublicId }};

        var clientMap = {};
        var $clientSelect = $('select#client');

        for (var i=0; i<clients.length; i++) {
          var client = clients[i];
          clientMap[client.public_id] = client;
        }

        $clientSelect.append(new Option('', ''));
        for (var i=0; i<clients.length; i++) {
          var client = clients[i];
          var clientName = getClientDisplayName(client);
          if (!clientName) {
              continue;
          }
          $clientSelect.append(new Option(clientName, client.public_id));
        }

        if (clientId) {
          $clientSelect.val(clientId);
        }

        $clientSelect.combobox({highlighter: comboboxHighlighter});
        
        $clientSelect.trigger('change');

        @if (!$trip && !$clientPublicId)
            $('.client-select input.form-control').focus();
        @else
            $('#vehicle').focus();
        @endif

        $('#trip_date').datepicker();
    });

    </script>
    

@stop
