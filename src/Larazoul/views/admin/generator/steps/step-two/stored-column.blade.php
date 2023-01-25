@foreach($module->columns as $column)
    <div class="column">
        <div class="row">
            <div class="col-lg-3">
                @include("larazoul::fileds.php.text" , ["name" => "name[]" , "label" => trans("larazoul::larazoul.Column")." ".trans("larazoul::larazoul.Name") , 'value' => $column->name])
            </div>
            <div class="col-lg-3">
                @include("larazoul::fileds.php.select", ["name" => "column[]" , "array" => $migrationTypes , "label" => trans("larazoul::larazoul.Column") , 'value' => $column->column])
            </div>
            <div class="col-lg-3">
                @include("larazoul::fileds.php.select" , ["name" => "modifiers[]", 'array' => $migrationModifiers + ['' => 'None'] , "label" => trans("larazoul::larazoul.Column")." ".trans("larazoul::larazoul.modifiers") , 'value' => $column->modifiers])
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
                @include("larazoul::fileds.php.select" , ["name" => "multi_lang[]", 'array' => yesNoArray() , "label" => trans("larazoul::larazoul.Language") , 'value' => $column->multi_lang])
            </div>
            <div class="col-lg-2">
                <a href="{{ route('delete-column' , ['id' => $column]) }}" class="btn btn-danger" onclick="removeThisColumn(this)" style="margin-top: 25px"><i class="fa fa-trash"></i></a>
            </div>
        </div>
        @if(!$loop->last)
            <hr>
        @endif
    </div>
@endforeach