<?php
require_once standart_path.'core/RequestActionConstant.php';
require_once standart_path.'action/ChangePassword.php';
$token = filter_input(INPUT_GET, ChangePassword::TOKEN);
if(empty($token)) {
    echo '<script>alert("Ubah Password Tidak Diperbolehkan"); window.location = "'.getUrlByRequest().'";</script>';
    return;
}
if(!file_exists(standart_path."assets/change_password/".md5($token))) {
    echo '<script>alert("Ubah Password Tidak Diperbolehkan"); window.location = "'.getUrlByRequest().'";</script>';
    return;
}
?>
<script>
    function check(){
        if(document.getElementById("password").value === ""
            || document.getElementById("cpassword").value === "") {
            alert("Tertanda bintang harus terisi");
            return false;
        }
        if(document.getElementById("password").value !== document.getElementById("cpassword").value ) {
            alert("Kata Sandi dan Ulangi Kata Sandi harus sama.");
            return false;
        }
        var http = new XMLHttpRequest();
        var fd = new FormData();
        fd.append("<?php echo ChangePassword::TOKEN; ?>", "<?php echo $token; ?>");
        fd.append("<?php echo ChangePassword::PASSWORD; ?>", document.getElementById("password").value);
        http.open("POST", "<?php echo getUrlByRequest(RequestActionConstant::CHANGE_PASSWORD); ?>", true);
        http.onreadystatechange = function() {
            var json = JSON.parse(http.responseText);
            if(json.status === 1){
                alert("Password Telah Diubah");
                window.location.href = "<?php echo getUrlByRequest(RequestPageConstant::LOGIN); ?>";
            }
            else alert(json.message);
        };
        http.send(fd);
        return false;
    }
</script>
<!-- === BEGIN CONTENT === -->
<div id="content">
    <div class="container background-white">
        <div class="row margin-vert-30">
            <!-- Register Box -->
            <div class="col-md-6 col-md-offset-3 col-sm-offset-3">
                <form class="signup-page margin-top-20" onsubmit="return check()">
                    <div class="signup-header">
                        <h2 class="margin-bottom-20">Ubah Password</h2>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <label>Kata Sandi
                                <span class="color-red">*</span>
                            </label>
                            <input class="form-control margin-bottom-20" type="password" id="password">
                        </div>
                        <div class="col-sm-6">
                            <label>Ulangi Kata Sandi
                                <span class="color-red">*</span>
                            </label>
                            <input class="form-control margin-bottom-20" type="password" id="cpassword">
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-8">
                            <label class="checkbox">
                                <a href="<?php echo getUrlByRequest(); ?>">Kembali Kehalaman Awal</a>
                            </label>
                        </div>
                        <div class="col-lg-4 text-right">
                            <button class="btn btn-primary" type="submit">Selesai</button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- End Register Box -->
        </div>
    </div>
</div>
<!-- === END CONTENT === -->