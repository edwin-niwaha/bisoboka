@include('layouts/layout');
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="http://code.jquery.com/jquery-3.3.1.min.js"
               integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
               crossorigin="anonymous">
</script>
{{csrf_field()}}
<p>
    <input type="button" value="Fill SELECT Dropdown List with JSON" id="bt" />
</p>
<input type="button" value="create" class="btn btn-primary" id="create"/>
<select id="sel">
    <option value="">-- Select --</option>
</select>

<p id="msg"></p>
</body>
<script>
    $(document).ready(function () {
        $('#bt').click(function () {
            
            var url = "CodeGenerator/tblnames";

            $.getJSON(url, function (data) {
                $.each(data, function (index, value) {
                  
                    $('#sel').append('<option value="' + value + '">' + value + '</option>');
                });
            });
        });

        // SHOW SELECTED VALUE.
        $('#sel').change(function () {
            $('#msg').text('Selected Item: ' + this.options[this.selectedIndex].text);
        });
    });
    $(document).ready(function(){
$("#create").click(function(){
var table=$("#sel").val();
var url="CodeGenerator/send";
$.ajax({
url:url,
method:'POST',
data:{'table':table,'_token':$('input[name=_token]').val()}


});

});

    });
</script>
</html>

