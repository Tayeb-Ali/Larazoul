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
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> @lang('larazoul::larazoul.Home')</a></li>
            <li><a href="{{ route('modules') }}"> @lang('larazoul::larazoul.Module')</a></li>
            <li><a class="active">@lang('larazoul::larazoul.Step One')</a></li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="callout callout-info">
            <h4>@lang('larazoul::larazoul.Step One')!</h4>
            <p>@lang('larazoul::larazoul.Step One Description')</p>
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
            @if($module)
                {!! Form::open(['route' => ['update-step-one' , $module->id] , 'role' => 'form']) !!}
            @else
                {!! Form::open(['route' => 'store-step-one' , 'role' => 'form']) !!}
            @endif
                <div class="box-body">
                    @include('larazoul::fileds.php.text' , ['name' => 'name' , 'label' => trans('larazoul::larazoul.Module').' '.trans('larazoul::larazoul.Name') , 'value' => $module ? $module->name : '' ])
                    <div class="row">
                        <div class="col-lg-3">
                            @include('larazoul::fileds.php.select' , ['name' => 'admin' , 'label' => trans('larazoul::larazoul.Generate').' '.trans('larazoul::larazoul.Admin') , 'array' => yesNoWordArray() , 'value' => $module ?  $module->admin : null])
                        </div>
                        <div class="col-lg-3">
                            @include('larazoul::fileds.php.select' , ['name' => 'website' , 'label' => trans('larazoul::larazoul.Generate').' '.trans('larazoul::larazoul.Website') ,'array' => yesNoWordArray() , 'value' => $module ?  $module->website : null  ])
                        </div>
                        <div class="col-lg-3">
                            @include('larazoul::fileds.php.select' , ['name' => 'api' , 'label' => trans('larazoul::larazoul.Generate').' '.trans('larazoul::larazoul.Api') ,'array' => yesNoWordArray() , 'value' => $module ?  $module->api : null])
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    {!! Form::submit(trans('larazoul::larazoul.Save') , ['class' => 'btn btn-info']) !!}
                    @if($module)
                        <a href="{{ route('view-step-two' , ['id' => $module->id ]) }}" class="btn btn-warning"><i class="fa fa-arrow-circle-right"></i> @lang('larazoul::larazoul.Next')</a>
                        <a href="{{ route('view-step-three' , ['id' => $module->id ]) }}" class="btn btn-warning"><i class="fa fa-arrow-circle-right"></i> @lang('larazoul::larazoul.Step Three')</a>
                        <a href="{{ route('view-step-four' , ['id' => $module->id ]) }}" class="btn btn-warning"><i class="fa fa-arrow-circle-right"></i> @lang('larazoul::larazoul.Step Four')</a>
                    @endif
                </div>
                <!-- /.box-footer-->
            {!! Form::close() !!}
        </div>
        <!-- /.box -->
    </section>
    <!-- /.content -->
@endsection
