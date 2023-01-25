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
            <h4>@lang('larazoul::larazoul.Step Four')!</h4>
            <p>@lang('larazoul::larazoul.Step Four Description')</p>
        </div>

        <div class="row">

            <div class="col-lg-7">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">@lang('larazoul::larazoul.Relation')</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                                    title="Collapse">
                                <i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip"
                                    title="Remove">
                                <i class="fa fa-times"></i></button>
                        </div>
                    </div>

                    <table class="table table-bordered ">

                        <tr>
                            <th>
                                @lang('larazoul::larazoul.Related Modules')
                            </th>
                            <th>
                                @lang('larazoul::larazoul.Type')
                            </th>
                            <th>
                                @lang('larazoul::larazoul.Input Type')
                            </th>
                            <th>
                                @lang('larazoul::larazoul.Edit')
                            </th>
                            <th>
                                @lang('larazoul::larazoul.Delete')
                            </th>
                        </tr>

                        @if(!empty($relations))
                            @foreach($relations as $relation)
                                <tr>
                                    <td>
                                        {{ $relation->module_to->name }}
                                    </td>
                                    <td>
                                        {{ $relation->type }}
                                    </td>
                                    <td>
                                        {{ $relation->input_type }}
                                    </td>
                                    <td>
                                        {{ $relation->module_to->name }}
                                    </td>
                                    <td>
                                        <a href="{{ route('delete-relation' , $relation->id) }}" class="btn btn-danger"><i
                                                    class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif

                    </table>

                    <!-- /.box-body -->
                    <div class="box-footer">
                        {!! Form::open(['route' => ['build-module' ,  $module->id] , 'role' => 'form' , 'class' => 'pull-left']) !!}
                        {!! Form::submit(trans('larazoul::larazoul.Build') , ['class' => 'btn btn-info']) !!}
                        {!! Form::close() !!}
                        {!! Form::open(['route' => ['migrate-module' ,  $module->id] , 'role' => 'form' , 'method' => 'post' ,'class' => 'pull-left' , 'style' => 'margin:0px 10px;']) !!}
                        {!! Form::submit(trans('larazoul::larazoul.Migrate') , ['class' => 'btn btn-info']) !!}
                        {!! Form::close() !!}

                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <!-- Default box -->
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">@lang('larazoul::larazoul.Add New Relation')</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                                    title="Collapse">
                                <i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip"
                                    title="Remove">
                                <i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    {!! Form::open(['route' => ['store-step-four' ,  $module->id] , 'role' => 'form']) !!}
                    <div class="box-body">
                        {!! Form::hidden('module_id_from' , $module->id) !!}
                        <div class="row">

                            <div class="col-lg-12">
                                <h3>
                                    {{ trans('larazoul::larazoul.Related Module')  }}
                                </h3>
                            </div>

                            <div class="col-lg-6">
                                @include('larazoul::fileds.php.select' , ['name' => 'module_to_id' ,'array' => $modules->toArray(), 'label' => trans('larazoul::larazoul.Related Module')  ])
                            </div>

                            <div class="col-lg-6">
                                @include('larazoul::fileds.php.select' , ['name' => 'column_id' ,'array' => [], 'label' => trans('larazoul::larazoul.Related Module Columns')])
                            </div>

                        </div>
                        <div class="row">

                            <div class="col-lg-12">
                                <h3>
                                    {{ trans('larazoul::larazoul.Relation')  }}
                                </h3>
                            </div>

                            <div class="col-lg-6">
                                @include('larazoul::fileds.php.select' , ['name' => 'type' ,'array' => $relationType, 'label' => trans('larazoul::larazoul.Relation type') ])
                            </div>

                            <div class="col-lg-6">
                                @include('larazoul::fileds.php.select' , ['name' => 'input_type' ,'array' => $relationInput, 'label' => trans('larazoul::larazoul.Relation type') ])
                            </div>


                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        {!! Form::submit(trans('larazoul::larazoul.Save') , ['class' => 'btn btn-info']) !!}
                        <a href="{{ route('view-step-three' , ['id' => $module->id ]) }}" class="btn btn-warning"><i
                                    class="fa fa-arrow-circle-left"></i> @lang('larazoul::larazoul.Back')</a>
                        <a href="{{ route('view-step-two' , ['id' => $module->id ]) }}" class="btn btn-warning"><i
                                    class="fa fa-arrow-circle-left"></i> @lang('larazoul::larazoul.Step Two')</a>
                        <a href="{{ route('view-step-one' , ['id' => $module->id ]) }}" class="btn btn-warning"><i
                                    class="fa fa-arrow-circle-left"></i> @lang('larazoul::larazoul.Step One')</a>
                    </div>
                    <!-- /.box-footer-->
                    {!! Form::close() !!}
                </div>
                <!-- /.box -->
            </div>

        </div>

    </section>
    <!-- /.content -->
@endsection

@push('js')
@include('larazoul::admin.generator.steps.step-four.scripts.related-model')
@endpush
