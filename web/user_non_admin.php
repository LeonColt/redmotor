<?php
require_once standart_path.'core/universal_methode.php';
require_once standart_path.'dataobject/User.php';
require_once standart_path.'action/UserOperation.php';
require_once standart_path.'action/ForgotPassword.php';

$user = GetCurrentUser();

$runner = new Runner();
$runner->connect(GlobalConst\DBConst::HOST, GlobalConst\DBConst::PORT, GlobalConst\DBConst::DATABASE, GlobalConst\DBConst::DATABASE_USERNAME, GlobalConst\DBConst::DATABASE_PASSWORD);

$loaded_user = new User($user->getId());
$loaded_user->setRunner($runner);
$loaded_user->load();

?>
<script>
    function Update() {
        var fd = new FormData();
        fd.append("<?php echo UserOperation::INPUT_OPERATION; ?>", "<?php echo UserOperation::OPERATION_UPDATE; ?>");
        fd.append("<?php echo UserOperation::INPUT_ID; ?>", document.getElementById("benutzer_id").value);
        fd.append("<?php echo UserOperation::INPUT_USERNAME?>", document.getElementById("benutzername").value);
        fd.append("<?php echo UserOperation::INPUT_NAME; ?>", document.getElementById("name").value);
        fd.append("<?php echo UserOperation::INPUT_KTP; ?>", document.getElementById("ktp").value);
        fd.append("<?php echo UserOperation::INPUT_BIRTH_DATE; ?>", document.getElementById("birth_date").value);
        fd.append("<?php echo UserOperation::INPUT_SEX; ?>", document.getElementById("sex").value);
        fd.append("<?php echo UserOperation::INPUT_ADDRESS; ?>", document.getElementById("alamat").value);
        fd.append("<?php echo UserOperation::INPUT_TELEPHONE; ?>", document.getElementById("telephone").value);
        fd.append("<?php echo UserOperation::INPUT_EMAIL; ?>", document.getElementById("email").value);
        fd.append("<?php echo UserOperation::INPUT_LEVEL; ?>", "<?php echo User::LEVEL_CUSTOMER?>");

        var http = new XMLHttpRequest();
        http.open("POST", "<?php echo getUrlByRequest(RequestActionConstant::USER_OPERATION); ?>", true);
        http.onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 200) {
                var json = JSON.parse(this.responseText);
                if (json.status === 1) window.location.reload(true);
            }
        };
        http.send(fd);
    }
    function ChangePassword() {
        var fd = new FormData();
        fd.append("<?php echo ForgotPassword::ID_USERNAME; ?>", document.getElementById("benutzer_id").value);
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
<div id="porfolio" class="parallax-bg1" style="background-position: 50% 0%;" data-stellar-background-ratio="0.5">
    <div class="container">
        <br><br>
        <div>
            <form>
                <table>
                    <tr>
                        <td><label>ID</label></td>
                        <td><input type="text" id="benutzer_id" value="<?php echo $loaded_user->getId(); ?>" disabled></td>
                    </tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr>
                        <td><label>Username</label></td>
                        <td><input type="text" id="benutzername" value="<?php echo $loaded_user->getUsername(); ?>" disabled></td>
                    </tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr>
                        <td><label>Nama</label></td>
                        <td><input type="text" id="name" value="<?php echo $loaded_user->getName(); ?>"></td>
                    </tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr>
                        <td><label>KTP</label></td>
                        <td><input type="text" id="ktp" value="<?php echo $loaded_user->getKtp(); ?>"></td>
                    </tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr>
                        <td><label>Tanggal Lahir</label></td>
                        <td><input type="date" id="birth_date" value="<?php echo $loaded_user->getBirthDate(); ?>"></td>
                    </tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr>
                        <td><label>Jenis Kelamin</label></td>
                        <td>
                            <select id="sex">
                                <option value="0"<?php if ($loaded_user->isFemale()) echo " selected";?>>Wanita</option>
                                <option value="1"<?php if ($loaded_user->isMale()) echo " selected";?>>Pria</option>
                                <option value="2"<?php if (!$loaded_user->isFemale() && !$loaded_user->isMale()) echo " selected";?>>Lainnya</option>
                            </select>
                        </td>
                    </tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr>
                        <td><label>Alamat</label></td>
                        <td><textarea id="alamat"><?php echo $loaded_user->getAddress(); ?></textarea></td>
                    </tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr>
                        <td><label>Telepon</label></td>
                        <td><input type="text" id="telephone" value="<?php echo $loaded_user->getTelp(); ?>"></td>
                    </tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr>
                        <td><label>Email</label></td>
                        <td><input type="email" id="email" value="<?php echo $loaded_user->getEmail(); ?>"></td>
                    </tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr>
                        <td><input type="button" value="Update" onclick="Update();"></td>
                    </tr>
                </table>
            </form>
            <br><br>
            <button id="btt_change_password" onclick="ChangePassword();">Ganti Password</button>
        </div>
    </div>
</div>
