<?php
if (isset($_POST['data'])){
    $data = trim($_POST['data']);
    file_put_contents(__DIR__ . '/include.list', $data);
    echo $data;
    die();
}
?>
<html>
<head>

<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js"></script>


<style>
.subject-info-box-1 select,
.subject-info-box-2 select {
  height: 400px;
  padding: 0;
  width: 100%;
}
.subject-info-box-1 select option,
.subject-info-box-2 select option {
  padding: 4px 10px 4px 10px;
}
.subject-info-arrows input {
  width: 70%;
  margin-bottom: 5px;
}
#success {
    display:none;
}
</style>
<script>
var success = function() {
    $('#success').fadeIn( 300 ).delay( 1000 ).fadeOut( 400 );
}

$( document ).ready(function() {
    $('#btnRight').click(function (e) {
        if ($("#lstBox2").find(":selected").length !== 1) {
            alert("Select an item on the right to insert below");
            e.preventDefault();
            return false;
        }

        var selected = $("#lstBox2").find(":selected:first");
        var selectedOpts = $('#lstBox1 option:selected');
        if (selectedOpts.length == 0) {
            alert("Nothing to move.");
            e.preventDefault();
        }

        selected.prop("selected", false)
        $(selectedOpts).detach().insertAfter(selected);
        e.preventDefault();
    });

    $('#btnAllRight').click(function (e) {
        var selectedOpts = $('#lstBox1 option');
        if (selectedOpts.length == 0) {
            alert("Nothing to move.");
            e.preventDefault();
        }

        $('#lstBox2').append($(selectedOpts).clone());
        $(selectedOpts).remove();
        e.preventDefault();
    });

    $('#btnLeft').click(function (e) {
        var selectedOpts = $('#lstBox2 option:selected');
        if (selectedOpts.length == 0) {
            alert("Nothing to move.");
            e.preventDefault();
        }

        $('#lstBox1').append($(selectedOpts).clone());
        $(selectedOpts).remove();
        e.preventDefault();
    });

    $('#btnAllLeft').click(function (e) {
        var selectedOpts = $('#lstBox2 option');
        if (selectedOpts.length == 0) {
            alert("Nothing to move.");
            e.preventDefault();
        }

        $('#lstBox1').append($(selectedOpts).clone());
        $(selectedOpts).remove();
        e.preventDefault();
    });
    
    
    $('#btnPlus').click(function (e) {
        if ($("#lstBox2").find(":selected").length !== 1) {
            alert("Only select 1 item");
            e.preventDefault();
            return false;
        }
        
        var selected = $("#lstBox2").find(":selected");
        var before = selected.prev();
        if (before.length > 0)
            selected.detach().insertBefore(before);

        e.preventDefault();
    });
    $('#btnMinus').click(function (e) {
        if ($("#lstBox2").find(":selected").length !== 1) {
            alert("Only select 1 item");
            e.preventDefault();
            return false;
        }

        var selected = $("#lstBox2").find(":selected");
        var next = selected.next();
        if (next.length > 0)
            selected.detach().insertAfter(next);

        e.preventDefault();
    });
    
    $('#btnSave').click(function (e) {
        var output = '';
        for (i = 1; i < $('#lstBox2 option').length + 1; i++) { 
            output += $('#lstBox2 option:nth-of-type(' + i + ')').text() + "\n";
        }
        
        $.post('test.php', {data: output}, success);
        e.preventDefault();
    });
    
});
</script>

<title>Pick TV services</title>


</head>

<body>


<form method="post">
<div class="container">

    <div class="row">
        <div class="col-xs-5">
            <h2>Available</h2>
        </div>
        <div class="col-xs-offset-2  col-xs-5">
            <h2>Added</h2>
        </div>
    </div>
    <div class="row">
        <?php
        include_once('common.php');
        ?>
        <div class="subject-info-box-1 col-xs-5">
            <select multiple="multiple" id='lstBox1' class="form-control">
                <?php
                foreach($channels as $key => $channel) {
                    if (!in_array($key, $includeList)) {
                ?>
                    <option value="<?php echo $key ?>"><?php echo $key ?></option>
                <?php
                    }
                }
                ?>
            </select>
        </div>

        <div class="subject-info-arrows text-center col-xs-2">
            <input type='button' id='btnAllRight' value='>>' class="btn btn-default" /><br />
            <input type='button' id='btnRight' value='>' class="btn btn-default" /><br />
            <input type='button' id='btnLeft' value='<' class="btn btn-default" /><br />
            <input type='button' id='btnAllLeft' value='<<' class="btn btn-default" /><br /><br />

            <input type='button' id='btnPlus' value='+' class="btn btn-default" /><br />
            <input type='button' id='btnMinus' value='-' class="btn btn-default" /><br /><br />
            
            <input type='button' id='btnSave' value='Save' class="btn btn-default" /><br /><br />
            <div class="alert alert-success" role="alert" id='success'>
                Channel list saved.
            </div>
        </div>

        <div class="subject-info-box-2 col-xs-5">
            <select multiple="multiple" id='lstBox2' name='lstBox2' class="form-control">
                <?php
                foreach ($includeList as $key) {
                ?>
                    <option value="<?php echo $key ?>"><?php echo $key ?></option>
                <?php
                }
                ?>
            </select>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
</form>

</body>
</html>