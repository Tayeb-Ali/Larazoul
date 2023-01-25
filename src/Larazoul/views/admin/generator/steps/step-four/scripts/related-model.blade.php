<script>

    var module = $('#module_to_id') , column = $('#column_id') , module_id , data;

    function changeModule() {
        column.html('');
        module_id = module.val();
        $.getJSON('{{ urll('/admin/generator/module/get-columns/') }}/'+module_id , function (response) {
            data = '';
            $.each(response.columns , function (key , value) {
                data += '<option value="'+value.id+'">'+value.name+'</option>';
            });
            column.html(data);
        });
    }

    module.on('change' , function () {
        changeModule();
    });


    module.trigger('change')

</script>