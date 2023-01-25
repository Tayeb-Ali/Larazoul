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
            <h4>@lang('larazoul::larazoul.Step Three')!</h4>
            <p>@lang('larazoul::larazoul.Step Three Description')</p>
        </div>
        <!-- Default box -->
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">@lang('larazoul::larazoul.Step Three')</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                            title="Collapse">
                        <i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip"
                            title="Remove">
                        <i class="fa fa-times"></i></button>
                </div>
            </div>
            {!! Form::open(['route' => ['store-step-three' ,  $module->id] , 'role' => 'form']) !!}
            <div class="box-body">
                <table class="table table-bordered table-responsive">
                    <tr>
                        <th width="250">
                            @lang('larazoul::larazoul.Name')
                        </th>
                        <th>
                            @lang('larazoul::larazoul.Validation')
                        </th>
                        <th width="200">#</th>
                    </tr>
                    @foreach($module->columns as $column)
                        @if(isset($column->details))
                            @php $validation  =   json_decode($column->details->validation , true) ?? [] @endphp
                        @else
                            @php $validation  = [] @endphp
                        @endif
                        <tr>
                            <td>
                                {{ $column->name }}
                            </td>
                            <td>
                                @foreach($validationRules as $key => $value)
                                    <div class="col-lg-6">
                                        @if(!$value)
                                            {!!  Form::checkbox('validation['.$column->id.']['.$key.']' , $key, in_array($key , $validation) , ['id' => $key]) !!}
                                            <label for="{{ $value }}">{{ $key }}</label>
                                        @else
                                            <label for="{{ $value }}">{{ $key }}</label>
                                            @include('larazoul::fileds.php.text' , ['name' =>'validation['.$column->id.']['.$key.']' ,'value' => key_exists($key , $validation) ? $validation[$key] : '' ])
                                        @endif
                                    </div>
                                @endforeach
                                <div class="col-lg-12">
                                    @include('larazoul::fileds.php.text' , ['name' =>'custom_validation['.$column->id.']', 'label' => trans('larazoul::larazoul.custom validation')  ,'value' => isset($column->details->custom_validation) ? $column->details->custom_validation :  null ])
                                </div>

                            </td>
                            <td width="400">
                                @if($module->admin == 'yes')
                                    <div class="col-lg-6">
                                        @include('larazoul::fileds.php.select' , ['name' => 'admin_crud['.$column->id.']' ,'array' => yesNoWordArray() , 'label' => trans('larazoul::larazoul.Show in admin crud') , 'value' => isset($column->details->admin_crud) ? $column->details->admin_crud :  null ])
                                    </div>
                                    <div class="col-lg-6">
                                        @include('larazoul::fileds.php.select' , ['name' => 'admin_filter['.$column->id.']' ,'array' => yesNoWordArray() , 'label' => trans('larazoul::larazoul.Show in admin Filter') , 'value' => isset($column->details->admin_filter) ? $column->details->admin_filter :  null ])
                                    </div>
                                @endif
                                @if($module->website == 'yes')
                                    <div class="col-lg-6">
                                        @include('larazoul::fileds.php.select' , ['name' => 'site_crud['.$column->id.']' ,'array' => yesNoWordArray(), 'label' => trans('larazoul::larazoul.Show in website crud') , 'value' => isset($column->details->site_crud) ? $column->details->site_crud :  null ])
                                    </div>
                                    <div class="col-lg-6">
                                        @include('larazoul::fileds.php.select' , ['name' => 'website_filter['.$column->id.']' ,'array' => yesNoWordArray(), 'label' => trans('larazoul::larazoul.Show in website Filter') , 'value' => isset($column->details->website_filter) ? $column->details->website_filter :  null ])
                                    </div>
                                @endif
                                @if($module->api == 'yes')
                                    <div class="col-lg-6">
                                        @include('larazoul::fileds.php.select' , ['name' => 'transformer['.$column->id.']' ,'array' => yesNoWordArray() , 'label' => trans('larazoul::larazoul.Show in transformers') , 'value' => isset($column->details->transformer) ? $column->details->transformer :  null ])
                                    </div>
                                @endif
                                <div class="col-lg-6">
                                    @include('larazoul::fileds.php.select' , ['name' => 'html_type['.$column->id.']' ,'array' => $htmlInputType, 'label' => trans('larazoul::larazoul.Input Type') , 'value' => isset($column->details->html_type) ? $column->details->html_type :  null ])
                                </div>
                            </td>
                            {!! Form::hidden('column_id[]' , $column->id) !!}
                        </tr>
                    @endforeach
                </table>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                {!! Form::submit(trans('larazoul::larazoul.Save') , ['class' => 'btn btn-info']) !!}
                <a href="{{ route('view-step-two' , ['id' => $module->id ]) }}" class="btn btn-warning"><i class="fa fa-arrow-circle-left"></i> @lang('larazoul::larazoul.Back')</a>
                <a href="{{ route('view-step-one' , ['id' => $module->id ]) }}" class="btn btn-warning"><i class="fa fa-arrow-circle-left"></i> @lang('larazoul::larazoul.Step One')</a>
                <a href="{{ route('view-step-four' , ['id' => $module->id ]) }}" class="btn btn-warning"><i class="fa fa-arrow-circle-right"></i> @lang('larazoul::larazoul.Step Four')</a>

            </div>
            <!-- /.box-footer-->
            {!! Form::close() !!}
        </div>
        <!-- /.box -->
    </section>
    <!-- /.content -->

@endsection
