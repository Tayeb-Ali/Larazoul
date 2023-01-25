@extends('larazoul::admin.layout.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang('larazoul::larazoul.menu control')
            <small>
                @lang('larazoul::larazoul.Here you Can control your menu')
            </small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> @lang('larazoul::larazoul.Home')</a></li>
            <li><a class="active">    @lang('larazoul::larazoul.menu control')</a></li>
        </ol>
    </section>

    <section class="content">
        <div class="callout callout-info">
            <h4>    @lang('larazoul::larazoul.menu control')!</h4>
            <p> @lang('larazoul::larazoul.Here you Can control your menu')</p>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <!-- Default box -->
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">@lang('larazoul::larazoul.add menu')</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                                    title="Collapse">
                                <i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip"
                                    title="Remove">
                                <i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    {!! Form::open(['route' => 'post-menu', 'role' => 'form']) !!}
                    <div class="box-body">
                        @include('larazoul::fileds.php.text' , ['name' => 'name' , 'label' => trans('larazoul::larazoul.Menu name')])
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        {!! Form::submit(trans('larazoul::larazoul.Save') , ['class' => 'btn btn-info']) !!}
                    </div>
                {!! Form::close() !!}
                <!-- /.box-footer-->
                </div>
                <!-- /.box -->
            </div>
            <div class="col-lg-8">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">   @lang('larazoul::larazoul.menu control')</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                                    title="Collapse">
                                <i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip"
                                    title="Remove">
                                <i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <table class="table table-borderd table-stripped">
                        <tr>
                            <th>
                                @lang('larazoul::larazoul.Name')
                            </th>
                            <th>
                                @lang('larazoul::larazoul.Control')
                            </th>
                        </tr>
                        @foreach($menus as $menu)
                            <tr>
                                <td>
                                    {{ $menu->name }}
                                </td>
                                <td>
                                    <a href="{{ route('build-menu' , ['id' => $menu->id]) }}"
                                       class="btn btn-success"><i
                                                class="fa fa-plus"></i></a>
                                    @if($menu->id != 1)
                                        <a href="{{ route('delete-menu' , ['id' => $menu->id]) }}"
                                           class="btn btn-danger"><i
                                                    class="fa fa-trash"></i></a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </section>


@endsection