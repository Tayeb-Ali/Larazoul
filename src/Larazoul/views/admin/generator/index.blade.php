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
            <h4>@lang('larazoul::larazoul.Modules')!</h4>
            <p>@lang('larazoul::larazoul.Modules Description')</p>
        </div>
        <!-- Default box -->
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">@lang('larazoul::larazoul.Modules')</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                            title="Collapse">
                        <i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip"
                            title="Remove">
                        <i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <table class="table table-responsive table-bordered ">
                    <tr >
                        <th >
                            @lang('larazoul::larazoul.Name')
                        </th>
                        <th align="center">
                            @lang('larazoul::larazoul.Delete')
                        </th>
                        <th align="center">
                            @lang('larazoul::larazoul.Setting')
                        </th>
                        <th align="center">
                            @lang('larazoul::larazoul.Migration')
                        </th>
                        <th align="center">
                            @lang('larazoul::larazoul.Validation')
                        </th>
                        <th align="center">
                            @lang('larazoul::larazoul.Relations')
                        </th>
                        <th align="center">
                            @lang('larazoul::larazoul.Translation')
                        </th>
                    </tr>
                    @foreach($modules as $module)
                        <tr>
                            <td>{{ $module->name  }}</td>
                            <td width="100" align="center"><a href="{{ route('delete-module' , ['id' => $module->id]) }}" class="btn btn-danger"><i class="fa fa-trash"></i></a></td>
                            <td width="100" align="center"><a href="{{ route('view-step-one' , ['id' => $module->id]) }}" class="btn btn-warning"><i class="fa fa-gear"></i></a></td>
                            <td width="100" align="center"><a href="{{ route('view-step-two' , ['id' => $module->id]) }}" class="btn btn-success"><i class="fa fa-database"></i></a></td>
                            <td width="100" align="center"><a href="{{ route('view-step-three' , ['id' => $module->id]) }}" class="btn btn-success"><i class="fa fa-check"></i></a></td>
                            <td width="100" align="center"><a href="{{ route('view-step-four' , ['id' => $module->id]) }}" class="btn btn-success"><i class="fa fa-link"></i></a></td>
                            <td width="100" align="center"><a href="{{ route('view-step-five' , ['id' => $module->id]) }}" class="btn btn-success"><i class="fa fa-language"></i></a></td>

                        </tr>
                    @endforeach
                </table>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">

            </div>
            <!-- /.box-footer-->
        </div>
        <!-- /.box -->
    </section>
    <!-- /.content -->
@endsection