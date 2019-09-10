<?php
require_once standart_path.'core/RequestActionConstant.php';
require_once standart_path.'action/Login.php';
require_once standart_path.'action/ForgotPassword.php';
?>
<script>
    function check(){
        if(document.getElementById("username").value === ""
        || document.getElementById("password").value === "") {
            alert("Username dan Password tidak boleh kosong");
            return false;
        }
        var http = new XMLHttpRequest();
        var params = "<?php echo Login::USERNAME; ?>="+document.getElementById("username").value;
        params += "&<?php echo Login::PASSWORD; ?>="+document.getElementById("password").value;
        http.open("POST", "<?php echo getUrlByRequest(RequestActionConstant::LOGIN); ?>", true);
        http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        http.onreadystatechange = function() {
            if(this.readyState === 4 && this.status === 200) {
                var json = JSON.parse(http.responseText);
                if(json.status === 1)
                    window.location.href = "<?php
                            if(filter_input(INPUT_GET, GlobalConst::VAR_URL_REDIRECT) !== null)
                                echo filter_input(INPUT_GET, GlobalConst::VAR_URL_REDIRECT);
                            else echo getUrlByRequest();
                    ?>";
                else alert(json.message);
            }
        };
        http.send(params);
        document.getElementById("password").value = "";
        return false;
    }
    function ForgetPassword() {
        var res = prompt("ID atau Username");
        if(res === null || res === "") return;
        var fd = new FormData();
        fd.append("<?php echo ForgotPassword::ID_USERNAME; ?>", res);
        var http = new XMLHttpRequest();
        http.open("POST", "<?php echo getUrlByRequest(RequestActionConstant::FORGOT_PASSWORD); ?>", true);
        http.onreadystatechange = function() {
            if(this.readyState === 4 && this.status === 200) {
                var json = JSON.parse(http.responseText);
                if(json.status === 1)
                    alert("Email telah Dikirim");
                else alert(json.message);
            }
        };
        http.send(fd);
    }
</script>
<div id="content">
    <div class="container background-white">
        <div class="container">
            <div class="row margin-vert-30">
                <!-- Login Box -->
                <div class="col-md-6 col-md-offset-3 col-sm-offset-3">
                    <form class="login-page" onsubmit="return check()">
                        <div class="login-header margin-bottom-30">
                            <h2>Masuk</h2>
                        </div>
                        <div class="input-group margin-bottom-20">
                                    <span class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </span>
                            <input placeholder="Username" class="form-control" type="text" id="username">
                        </div>
                        <div class="input-group margin-bottom-20">
                                    <span class="input-group-addon">
                                        <i class="fa fa-lock"></i>
                                    </span>
                            <input placeholder="Kata Sandi" class="form-control" type="password" id="password">
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button class="btn btn-primary pull-right" type="submit">Masuk</button>
                            </div>
                        </div>
                        <hr>
                        <a href="<?php
                        $url = getUrlByRequest(RequestPageConstant::REGISTRATION);
                        if(filter_input(INPUT_GET, GlobalConst::VAR_URL_REDIRECT) !== null)
                            $url = addGetToUrl($url, GlobalConst::VAR_URL_REDIRECT, filter_input(INPUT_GET, GlobalConst::VAR_URL_REDIRECT));
                        echo $url;
                        ?>">Daftar</a>
<br>
                        <a href="#" onclick="ForgetPassword();">Lupa Password?</a>
                    </form>
                </div>
                <!-- End Login Box -->
            </div>
        </div>
    </div>
</div>
<!-- === END CONTENT === -->