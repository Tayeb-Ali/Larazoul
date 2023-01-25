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
--}}

@php $inputClass = isset($inputClass)  ? $inputClass : '' @endphp
@php $label = isset($label) ? $label : '' @endphp
@if($value)
<a href="{{ Storage::url($value) }}">
    <img src="{{ Storage::url($value) }}" alt="" width="100">
</a>
@endif

<div class="form-group {{ $errors->has($name) ? "has-error" : ""  }} {{ isset($divClass) ? $divClass : '' }}" id="{{ isset($divId) ? $divId : '' }}" data-key="{{ isset($key) ? $key : '' }}">
    @if($label != '')
        {!! Form::label($name, $label , ['class' => isset($labelClass) ? $labelClass : '' , 'id' =>  isset($labelId) ? $labelId : $name  ]) !!}
    @endif
    {!! Form::file($name  ,['id' => isset($inputId) ? $inputId :  $name , 'class' => $inputClass , 'accept' => 'image/*' ]) !!}
    @if ($errors->has($name))
        <div class="help-block">
            <small>{{ $errors->first($name) }}</small>
        </div>
    @endif
</div>