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
@php $selectedArray = isset($selectedArray) ? (is_array($selectedArray) ?  $selectedArray : (array) $selectedArray) : (old($name) ? (array) old($name) :  []) @endphp
<div class="form-group {{ $errors->has($name) ? "has-error" : ""  }} {{ isset($divClass) ? $divClass : '' }}"
     id="{{ isset($divId) ? $divId : '' }}" data-key="{{ isset($key) ? $key : '' }}">
    @if($label != '')
        {!! Form::label($name, $label , ['class' => isset($labelClass) ? $labelClass : '' , 'id' =>  isset($labelId) ? $labelId : $name   ]) !!}
    @endif
    @if(isset($array))
        @foreach($array as $key => $value)
            {!!  Form::checkbox($name , $key, in_array($key , $selectedArray) , ['id' => $value , 'class' => $inputClass]) !!}
            <label for="{{ $value }}">{{ $value }}</label>
        @endforeach
    @endif
    @if ($errors->has($name))
        <div class="help-block">
            <small>{{ $errors->first($name) }}</small>
        </div>
    @endif
</div>