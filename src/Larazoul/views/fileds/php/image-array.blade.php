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
<ul class="list-group">
    @if($value)
        @foreach($value as $v)
            <li class="list-group-item">
                <a href="{{ Storage::url($v) }}">
                    <img src="{{ Storage::url($v) }}" alt="" width="50">
                </a>
                <a href="{{ route($url.'.delete' , ['id' => $row->id , 'field' => $name ]).'?file='.$v }}"
                   class="pull-{{ rROL() }}">
                    <i class="fa fa-trash"></i>
                </a>
            </li>
        @endforeach
    @endif
    <li class="list-group-item">
        <div class="form-group {{ $errors->has($name) ? "has-error" : ""  }} {{ isset($divClass) ? $divClass : '' }}"
             id="{{ isset($divId) ? $divId : '' }}" data-key="{{ isset($key) ? $key : '' }}">
            @if($label != '')
                {!! Form::label($name, $label , ['class' => isset($labelClass) ? $labelClass : '' , 'id' =>  isset($labelId) ? $labelId : $name  ]) !!}
            @endif
            {!! Form::file($name  ,['id' => isset($inputId) ? $inputId :  $name , 'class' => $inputClass  , 'accept' => 'image/*' , 'multiple' => 'multiple' ]) !!}
            @php $name = str_replace('[]' , '' , $name) @endphp
            @if ($errors->has($name))
                <div class="help-block">
                    <small>{{ $errors->first($name) }}</small>
                </div>
            @endif
        </div>
    </li>
</ul>