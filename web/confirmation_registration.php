<?php
/**
 * Created by PhpStorm.
 * User: LC
 * Date: 20/04/2017
 * Time: 07:45
 */
require_once standart_path.'core/RequestActionConstant.php';
require_once standart_path.'action/Registration.php';
?>
<!-- === BEGIN CONTENT === -->
<div id="content">
    <div class="container background-white">
        <div class="row margin-vert-30">
            <!-- Register Box -->
            <div class="col-md-6 col-md-offset-3 col-sm-offset-3">
                <form class="signup-page margin-top-20" onsubmit="return check()">
                    <hr>
                    <div class="row">
                        <div class="col-lg-8">
                            <label class="checkbox">
                                <a href="<?php echo getUrlByRequest(); ?>">Kembali Kehalaman Awal</a>
                            </label>
                        </div>
                    </div>
                </form>
            </div>
            <!-- End Register Box -->
        </div>
    </div>
</div>
<!-- === END CONTENT === -->
<script>
    window.onload = function () {
        var fd = new FormData();
        fd.append("<?php echo Registration::REGISTRATION_TOKEN; ?>", "<?php echo htmlentities(filter_input(INPUT_GET, Registration::REGISTRATION_TOKEN)); ?>");
        var http = new XMLHttpRequest();
        http.open("POST", "<?php echo getUrlByRequest(RequestActionConstant::REGISTRATION); ?>", false);
        http.onreadystatechange = function() {
            alert(this.responseText);
            var json = JSON.parse(http.responseText);
            if(json.status === 1){
                alert("Konfirmasi Pendaftaran Sukses");
                window.location.href = "<?php echo getUrlByRequest(RequestPageConstant::LOGIN); ?>";
            }
            else alert(json.message);
        };
        http.send(fd);
    }
</script>