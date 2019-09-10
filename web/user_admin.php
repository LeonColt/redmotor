<?php
require_once standart_path.'core/universal_methode.php';
require_once standart_path.'core/MySQLAccess.php';
require_once standart_path.'dataobject/User.php';
require_once standart_path.'action/UserOperation.php';
require_once standart_path.'action/ForgotPassword.php';

$runner = new Runner();
$runner->connect(GlobalConst\DBConst::HOST, GlobalConst\DBConst::PORT, GlobalConst\DBConst::DATABASE, GlobalConst\DBConst::DATABASE_USERNAME, GlobalConst\DBConst::DATABASE_PASSWORD);
$user = GetCurrentUser();

?>
<script>
    function updateForm() {
        var checked = false;
        var id = null;
        var radio = document.getElementsByName("selector");
        for(var i = 0; i < radio.length; i++) {
            if(radio[i].checked) {
                checked = true;
                id = radio[i].value;
                break;
            }
        }

        if(id === "<?php echo $user->getId(); ?>"){
            document.getElementById("name").disabled = false;
            document.getElementById("ktp").disabled = false;
            document.getElementById("birth_date").disabled = false;
            document.getElementById("sex").disabled = false;
            document.getElementById("ktp").disabled = false;
            document.getElementById("alamat").disabled = false;
            document.getElementById("telephone").disabled = false;
            document.getElementById("email").disabled = false;
            //document.getElementById("level").disabled = true;
            document.getElementById("submitter").disabled = false;
            document.getElementById("btt_change_password").disabled = false;

        }
        else {
            document.getElementById("name").disabled = true;
            document.getElementById("ktp").disabled = true;
            document.getElementById("birth_date").disabled = true;
            document.getElementById("sex").disabled = true;
            document.getElementById("ktp").disabled = true;
            document.getElementById("alamat").disabled = true;
            document.getElementById("telephone").disabled = true;
            document.getElementById("email").disabled = true;
            //document.getElementById("level").disabled = true;
            document.getElementById("submitter").disabled = true;
            document.getElementById("btt_change_password").disabled = true;
        }

        if(!checked) return;
        document.getElementById("benutzer_id").value = id;

        var fd = new FormData();
        fd.append("<?php echo UserOperation::INPUT_OPERATION; ?>", "<?php echo UserOperation::OPERATION_GET; ?>");
        fd.append("<?php echo UserOperation::INPUT_ID; ?>", id);

        var http = new XMLHttpRequest();
        http.open("POST", "<?php echo getUrlByRequest(RequestActionConstant::USER_OPERATION); ?>", true);
        http.onreadystatechange = function () {
            if(this.readyState === 4 && this.status === 200) {
                var json = JSON.parse(this.responseText);
                if(json.status === 1) {
                    document.getElementById("benutzername").value = json.result.<?php echo UserOperation::OUTPUT_USERNAME; ?>;
                    document.getElementById("name").value = json.result.<?php echo UserOperation::OUTPUT_NAME; ?>;
                    document.getElementById("ktp").value = json.result.<?php echo UserOperation::OUTPUT_KTP; ?>;
                    document.getElementById("birth_date").value = json.result.<?php echo UserOperation::OUTPUT_BIRTH_DATE; ?>;
                    document.getElementById("sex").value = json.result.<?php echo UserOperation::OUTPUT_SEX; ?>;
                    document.getElementById("ktp").value = json.result.<?php echo UserOperation::OUTPUT_KTP; ?>;
                    document.getElementById("alamat").innerHTML = json.result.<?php echo UserOperation::OUTPUT_ADDRESS; ?>;
                    document.getElementById("telephone").value = json.result.<?php echo UserOperation::OUTPUT_TELEPHONE; ?>;
                    document.getElementById("email").value = json.result.<?php echo UserOperation::OUTPUT_EMAIL; ?>;
                    document.getElementById("level").value = json.result.<?php echo UserOperation::OUTPUT_LEVEL; ?>;
                }
            }
        };
        http.send(fd);
    }
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
        return false;
    }
</script>
<div id="porfolio" class="parallax-bg1" style="background-position: 50% 0%;" data-stellar-background-ratio="0.5">
    <div class="container">
        <table class="table-responsive" style="text-align: center; color: black; width: 100%" border="3" id="mt">
            <thead>
            <th>Pilih</th>
            <th>ID</th>
            <th>Username</th>
            <th>Nama</th>
            <th>KTP</th>
            <th>Tanggal Lahir</th>
            <th>Kelamin</th>
            <th>Alamat</th>
            <th>Telephone</th>
            <th>Email</th>
            <th>Type</th>
            </thead>
            <?php
            $users = new User();
            $users->setRunner($runner);
            foreach ($users as $luser) echo '<tr>
<td><input type="radio" name="selector" value="'.$luser->getId().'" onchange="updateForm();"></td>
<td>'.$luser->getId().'</td>
<td>'.$luser->getUsername().'</td>
<td>'.$luser->getName().'</td>
<td>'.$luser->getKtp().'</td>
<td>'.$luser->getBirthDate().'</td>
<td>'.(($luser->isFemale() ) ? "F" : "M").'</td>
<td>'.$luser->getAddress().'</td>
<td>'.$luser->getTelp().'</td>
<td>'.$luser->getEmail().'</td>
<td>'.( ($luser->isSuperAdmin()) ? "Super Admin" : (($luser->isAdmin()) ? "Admin" : "Customer" ) ).'</td>
</tr>';
            ?>
        </table>
        <br><br>
        <br><br>
        <div>
            <form>
                <table>
                    <tr>
                        <td><label>ID</label></td>
                        <td><input type="text" id="benutzer_id" disabled></td>
                    </tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr>
                        <td><label>Username</label></td>
                        <td><input type="text" id="benutzername" disabled></td>
                    </tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr>
                        <td><label>Nama</label></td>
                        <td><input type="text" id="name" disabled></td>
                    </tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr>
                        <td><label>KTP</label></td>
                        <td><input type="text" id="ktp" disabled></td>
                    </tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr>
                        <td><label>Tanggal Lahir</label></td>
                        <td><input type="date" id="birth_date" disabled></td>
                    </tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr>
                        <td><label>Jenis Kelamin</label></td>
                        <td>
                            <select id="sex" disabled>
                                <option value="0">Wanita</option>
                                <option value="1">Pria</option>
                                <option value="2">Lainnya</option>
                            </select>
                        </td>
                    </tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr>
                        <td><label>Alamat</label></td>
                        <td><textarea id="alamat" disabled></textarea></td>
                    </tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr>
                        <td><label>Telepon</label></td>
                        <td><input type="text" id="telephone" disabled></td>
                    </tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr>
                        <td><label>Email</label></td>
                        <td><input type="email" id="email" disabled></td>
                    </tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr>
                        <td><label>Level</label></td>
                        <td>
                            <select id="level" disabled>
                                <option value="1">Super Admin</option>
                                <option value="2">Admin</option>
                                <option value="3">Customer</option>
                            </select>
                        </td>
                    </tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr>
                        <td><input type="button" value="Update" id="submitter" onclick="Update();" disabled></td>
                    </tr>
                </table>
            </form>
            <br><br>
            <button id="btt_change_password" onclick="ChangePassword();" disabled>Ganti Password</button>
        </div>
    </div>
</div>
<script>
    window.onload = updateForm();
</script>