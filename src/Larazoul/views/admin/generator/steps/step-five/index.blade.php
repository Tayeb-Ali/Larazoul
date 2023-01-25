@extends('larazoul::admin.layout.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang('larazoul::larazoul.Larazoul')
            <small>
                @lang('larazoul::larazoul.Translate') {{ $module->name }}
            </small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> @lang('larazoul::larazoul.Home')</a></li>
            <li><a href="{{ route('modules') }}"> @lang('larazoul::larazoul.Module')</a></li>
            <li><a class="active">@lang('larazoul::larazoul.Translate')</a></li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="callout callout-info">
            <h4>@lang('larazoul::larazoul.Translate') !</h4>
            <p>@lang('larazoul::larazoul.Add Edit Remove Translation From') {{ $module->name }} .</p>
        </div>
        <!-- Default box -->
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">@lang('Step One')</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                            title="Collapse">
                        <i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip"
                            title="Remove">
                        <i class="fa fa-times"></i></button>
                </div>
            </div>
            {!! Form::open(['route' => ['store-step-five' , $module->id] , 'role' => 'form']) !!}
            <div class="box-body">
                <div class="row">
                    <div class="col-lg-2">
                        <h3>@lang('larazoul::larazoul.Keys')</h3>
                    </div>
                    @foreach($languages as $lang)
                        <div class="col-lg-3">
                            <h3>{{ $lang['native'] }}</h3>
                        </div>
                    @endforeach
                </div>
                <div class="row">
                    @foreach($languages as $langKey => $lang)
                        @if(key($languages) == $langKey)
                            <div id="keys" class="keys col-lg-2">
                                @endif
                                @foreach($arrays[$langKey] as $key => $ar)
                                    @if(key($languages) == $langKey)
                                        <div class="row" data-key="{{ $key }}">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <input type="text" name="keys[{{ $key }}]" value="{{ $key }}"
                                                           class="form-control keys-count">
                                                    <span class="btn btn-link"
                                                          style="position: absolute;right: 10px;top:0px" onclick="deleteLine('{{ $key }}')"><i
                                                                class="fa fa-trash"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                                @if(key($languages) == $langKey)
                            </div>
                        @endif
                    @endforeach
                    @foreach($languages as $langKey => $lang)
                        <div class="col-lg-3 lang" id="{{ $langKey }}">
                            @foreach($arrays[$langKey] as $key => $ar)
                                <div class="form-group" data-key="{{ $key }}">
                                    <input type="text" name="string[{{ $langKey }}][{{$key}}]" value="{{ $ar }}"
                                           class="form-control">
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- /.box-body -->
            <div class="box-footer">
                {!! Form::submit(trans('larazoul::larazoul.Save') , ['class' => 'btn btn-info']) !!}
                <span class="btn btn-success" onclick="addNewLine()"><i class="fa fa-plus"></i></span>
                <a href="{{ route('modules')}}" class="btn btn-warning"><i
                            class="fa fa-arrow-circle-left"></i> @lang('larazoul::larazoul.Modules')</a>
            </div>
            <!-- /.box-footer-->
            {!! Form::close() !!}
        </div>
        <!-- /.box -->
    </section>
    <!-- /.content -->
@endsection


@push('js')
<script>
    var langs = $('.lang'), keys = $('#keys');
    function addNewLine() {
        var countKeys = parseInt($('.keys-count').length) + 1;

        keys.append('<div class="row" data-key="'+countKeys+'"> <div class="col-lg-12"> <div class="form-group"> <input type="text" name="keys[' + countKeys + ']" placeholder="@lang('larazoul::larazoul.Key')"  class="form-control keys-count"> <span class="btn btn-link"style="position: absolute;right: 10px;top:0px" onclick="deleteLine('+ countKeys + ')"><i class="fa fa-trash"></i></span> </div> </div> </div>');

        $.each(langs, function () {
            id = $(this).attr('id');
            $(this).append('<div class="form-group" data-key="'+countKeys+'"> <input type="text" name="string[' + id + '][' + countKeys + ']" value="" class="form-control" placeholder="' + id + '"> </div>');
        });
    }
    
    function deleteLine(key) {
        $('div[data-key = '+key+']').remove();
    }
</script>
@endpush
