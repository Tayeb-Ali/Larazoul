@extends('larazoul::admin.layout.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang('larazoul::larazoul.Larazoul Generator')
            <small>
                @lang('larazoul::larazoul.Here you will generate the Module')
            </small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a class="active">Generator</a></li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="callout callout-info">
            <h4>@lang('larazoul::larazoul.Step Two')!</h4>
            <p>@lang('larazoul::larazoul.Step Two Description')</p>
        </div>
        <!-- Default box -->
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">@lang('larazoul::larazoul.Step Two') @lang('larazoul::larazoul.migration')</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                            title="Collapse">
                        <i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip"
                            title="Remove">
                        <i class="fa fa-times"></i></button>
                </div>
            </div>
            {!! Form::open(['route' => ['store-step-two' , $module->id] , 'role' => 'form']) !!}
                <div class="box-body">
                    @include('larazoul::admin.generator.steps.step-two.stored-column')
                    <div class="all-column"></div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    {!! Form::submit(trans('larazoul::larazoul.Save') , ['class' => 'btn btn-info']) !!}
                    <span  onclick="addNewColumn()" class="btn btn-success"><i class="fa fa-plus"></i> @lang('larazoul::larazoul.Add')</span>
                    <span  onclick="deleteAllColumn()" class="btn btn-danger"><i class="fa fa-close"></i> @lang('larazoul::larazoul.Clear')</span>
                    <a href="{{ route('view-step-one' , ['id' => $module->id ]) }}" class="btn btn-warning"><i class="fa fa-arrow-circle-left"></i> @lang('larazoul::larazoul.Back')</a>
                    <a href="{{ route('view-step-three' , ['id' => $module->id ]) }}" class="btn btn-warning"><i class="fa fa-arrow-circle-right"></i> @lang('larazoul::larazoul.Next')</a>
                    <a href="{{ route('view-step-four' , ['id' => $module->id ]) }}" class="btn btn-warning"><i class="fa fa-arrow-circle-right"></i> @lang('larazoul::larazoul.Step Four')</a>

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

        /*
         * Init value
         */

        var allColumn = $('.all-column');
        var template = '@include("larazoul::admin.generator.steps.step-two.javascript-template")';

        /*
         * add new column
         */

        function addNewColumn() {
            allColumn.append(template);
        }
        
        /*
         * Remove All columns
         */
        
        function deleteAllColumn() {
            allColumn.html('');
        }

        /*
         * Remove on column
         */

        function removeThisColumn(e) {
            $(e).closest('.column').remove();
        }

    </script>
@endpush