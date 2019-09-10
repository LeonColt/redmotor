<?php
require_once standart_path.'core/RequestActionConstant.php';
require_once standart_path.'action/PreRegistration.php';
?>
<script>
    function check(){
        if(document.getElementById("username").value === ""
            || document.getElementById("password").value === ""
            || document.getElementById("name").value === ""
            || document.getElementById("no_ktp").value === ""
            || document.getElementById("sex").value === ""
            || document.getElementById("birth_date").value === ""
            || document.getElementById("email").value === ""
            || document.getElementById("telephone").value === ""
            || document.getElementById("address").value === "") {
            alert("Tertanda bintang harus terisi");
            return false;
        }
        var http = new XMLHttpRequest();
        var params = "<?php echo PreRegistration::USERNAME; ?>="+document.getElementById("username").value;
        params += "&<?php echo PreRegistration::PASSWORD; ?>="+document.getElementById("password").value;
        params += "&<?php echo PreRegistration::NAME; ?>="+document.getElementById("name").value;
        params += "&<?php echo PreRegistration::NO_KTP; ?>="+document.getElementById("no_ktp").value;
        params += "&<?php echo PreRegistration::BIRTH_DATE; ?>="+document.getElementById("birth_date").value;
        params += "&<?php echo PreRegistration::SEX; ?>="+document.getElementById("sex").value;
        params += "&<?php echo PreRegistration::ADDRESS; ?>="+document.getElementById("address").value;
        params += "&<?php echo PreRegistration::TELEPHONE; ?>="+document.getElementById("telephone").value;
        params += "&<?php echo PreRegistration::EMAIL; ?>="+document.getElementById("email").value;
        http.open("POST", "<?php echo getUrlByRequest(RequestActionConstant::PRE_REGISTRATION); ?>", false);
        http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        http.onreadystatechange = function() {
            var json = JSON.parse(http.responseText);
            if(json.status == 1){
                alert("Email konfirmasi telah dikirim, silahkan konfirmasi pendaftaran terlebih dahulu sebelum masuk");
                window.location.href = "<?php echo getUrlByRequest(RequestPageConstant::LOGIN); ?>";
            }
            else alert(json.message);
        };
        http.send(params);
        document.getElementById("password").value = "";
        document.getElementById("cpassword").value = "";
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
                        <h2 class="margin-bottom-20">Daftar Sebagai Pengguna</h2>
                    </div>
                    <label>Username</label>
                    <span class="color-red">*</span>
                    <input class="form-control margin-bottom-20" type="text" id="username">
                    <label>No KTP</label>
                    <span class="color-red">*</span>
                    <input class="form-control margin-bottom-20" type="text" id="no_ktp">

                    <label><span class="color-red">*</span> Nama</label>
                    <input class="form-control margin-bottom-20" type="text" id="name">

                    <label>Jenis Kelamin</label>
                    <span class="color-red">*</span>
                    <select class="form-control margin-bottom-20" id="sex">
                        <option value="0">Wanita</option>
                        <option value="1">Pria</option>
                        <option value="2">Lainnya</option>
                    </select>

                    <label>Tanggal Lahir</label>
                    <span class="color-red">*</span>
                    <input class="form-control margin-bottom-20" type="date" id="birth_date">

                    <label>Email
                        <span class="color-red">*</span>
                    </label>
                    <input class="form-control margin-bottom-20" type="email" id="email">

                    <label>Nomor Telepon</label>
                    <span class="color-red">*</span>
                    <input class="form-control margin-bottom-20" type="text" id="telephone">

                    <label> Alamat
                        <span class="color-red">*</span>
                    </label>
                    <textarea class="form-control margin-bottom-60" id="address">

                    </textarea>
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