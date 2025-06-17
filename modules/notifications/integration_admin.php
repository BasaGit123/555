<div class="slog" id="scroll">
<?php
    $file = file('../data/cfg/notifications.dat');
    foreach($file as $key => $value){
        $arr = explode('|',trim($value));
        echo'<div style="color: '.$arr[0].';">'.$arr[1].' - '.$arr[2].'</div>';
    }
?>
</div>
<script type="text/javascript">
    window.onload = function(){
        document.getElementById('scroll').scrollTop = document.getElementById('scroll').scrollHeight;
    }
</script>