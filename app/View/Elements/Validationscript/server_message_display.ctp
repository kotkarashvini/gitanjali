<script>
    <?php if (isset($errarr)) {
        ?>
            function show_error_messages(){
        <?php
        foreach ($errarr as $keyfield => $message) {
            ?>
                $("#<?php echo $keyfield ?>").html("<?php echo $message ?>");
        <?php } ?> }
            $(document).ready(function(){
            show_error_messages();
            });
    <?php } ?>
        
        
        
          <?php if (isset($RequestData)) {
        ?>
            function show_request_data(){
        <?php
        foreach ($RequestData as $keyfield => $value ){
            ?>
                $("#<?php echo $keyfield ?>").val("<?php echo $value ?>");
        <?php } ?> }
            $(document).ready(function(){
            show_request_data();
            });
    <?php } ?>
        
</script>
