{{--
    $name => input name
    $divClass => form-group class
    $divId => form-group id
    $label => input label and place holder
    $labelId => label id
    $labelClass => label class
    $inputId => input id
    $inputClass => input class
    $key => larflat key
    $array => array for select
    $multiSelect = true
--}}

@php
    $inputClass = isset($inputClass)  ? $inputClass : '' ;
    $multiSelect = isset($multiSelect) && $multiSelect == true ? true : false;
@endphp
@php $placeholder = isset($placeholder) ? $placeholder : $label @endphp

<div class="form-group {{ $errors->has($name) ? "has-error" : ""  }} {{ isset($divClass) ? $divClass : '' }}" id="{{ isset($divId) ? $divId : '' }}" data-key="{{ isset($key) ? $key : '' }}">
    @isset($label)
         {!! Form::label($name, $label , ['class' => isset($labelClass) ? $labelClass : '' , 'id' =>  isset($labelId) ? $labelId : ''   ]) !!}
    @endisset
    @if(!$multiSelect)
    {!! Form::select($name, isset($array) ? $array : [] , isset($value) ? $value : old($name) , [
        'id' => isset($inputId) ? $inputId :  $name ,
        'class' => 'form-control select2'.$inputClass ,
        'style' => 'width:100%',
    ]) !!}
    @else
    {!! Form::select($name, isset($array) ? $array : [] , isset($value) ? $value : old($name) , [
            'id' => isset($inputId) ? $inputId :  $name ,
            'class' => 'form-control select2'.$inputClass ,
            'style' => 'width:100%',
            'multiple'
        ]) !!}
    @endif
    @if ($errors->has($name))
        <div class="help-block">
            <small>{{ $errors->first($name) }}</small>
        </div>
    @endif
</div>

