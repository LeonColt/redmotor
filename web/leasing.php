<?php
require_once standart_path.'core/universal_methode.php';
require_once standart_path.'core/MySQLAccess.php';
require_once standart_path.'dataobject/Leasing.php';
require_once standart_path.'action/LeasingOperation.php';

$runner = new Runner();
$runner->connect(GlobalConst\DBConst::HOST, GlobalConst\DBConst::PORT, GlobalConst\DBConst::DATABASE, GlobalConst\DBConst::DATABASE_USERNAME, GlobalConst\DBConst::DATABASE_PASSWORD);
$user = GetCurrentUser();

?>
<script>
    function updateForm() {
        if(document.getElementById("mode").value === "1") {
            document.getElementById("name_leasing").value = "";
            document.getElementById("address").value = "";
            document.getElementById("telephone").value = "";
            getNewId();
            return;
        }
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
        if(!checked) return;
        document.getElementById("id_leasing").value = id;

        var fd = new FormData();
        fd.append("<?php echo LeasingOperation::INPUT_OPERATION; ?>", "<?php echo LeasingOperation::OPERATION_GET; ?>");
        fd.append("<?php echo LeasingOperation::INPUT_ID; ?>", id);

        var http = new XMLHttpRequest();
        http.open("POST", "<?php echo getUrlByRequest(RequestActionConstant::LEASING_OPERATION); ?>", true);
        http.onreadystatechange = function () {
            if(this.readyState === 4 && this.status === 200) {
                var json = JSON.parse(this.responseText);
                if(json.status === 1) {
                    document.getElementById("name_leasing").value = json.result.<?php echo LeasingOperation::RETURN_NAME; ?>;
                    document.getElementById("address").value = json.result.<?php echo LeasingOperation::RETURN_ADDRESS; ?>;
                    document.getElementById("telephone").value = json.result.<?php echo LeasingOperation::RETURN_TELEPHONE; ?>;
                }
            }
        };
        http.send(fd);
    }
    function getNewId() {
        var fd = new FormData();
        fd.append("<?php echo LeasingOperation::INPUT_OPERATION; ?>", "<?php echo LeasingOperation::OPERATION_GET_NEW_ID; ?>");

        var http = new XMLHttpRequest();
        http.open("POST", "<?php echo getUrlByRequest(RequestActionConstant::LEASING_OPERATION); ?>", true);
        http.onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 200) {
                var json = JSON.parse(this.responseText);
                if (json.status === 1) {
                    document.getElementById("id_leasing").value = json.result;
                }
            }
        };
        http.send(fd);
    }
    function addOrUpdateLeasing() {
        var fd = new FormData();
        fd.append("<?php echo LeasingOperation::INPUT_OPERATION; ?>", "<?php echo LeasingOperation::OPERATION_ADD; ?>");
        fd.append("<?php echo LeasingOperation::INPUT_ID; ?>", document.getElementById("id_leasing").value);
        fd.append("<?php echo LeasingOperation::INPUT_NAME; ?>", document.getElementById("name_leasing").value);
        fd.append("<?php echo LeasingOperation::INPUT_ADDRESS; ?>", document.getElementById("address").value);
        fd.append("<?php echo LeasingOperation::INPUT_TELEPHONE; ?>", document.getElementById("telephone").value);

        var http = new XMLHttpRequest();
        http.open("POST", "<?php echo getUrlByRequest(RequestActionConstant::LEASING_OPERATION); ?>", true);
        http.onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 200) {
                var json = JSON.parse(this.responseText);
                if (json.status === 1) window.location.reload(true);
            }
        };
        http.send(fd);
    }
</script>
<div id="porfolio" class="parallax-bg1" style="background-position: 50% 0%;" data-stellar-background-ratio="0.5">
    <div class="container">
        <table class="table-responsive" style="text-align: center; color: black; width: 100%" border="3" id="mt">
            <thead>
            <th>Pilih</th>
            <th>ID</th>
            <th>Nama</th>
            <th>Alamat</th>
            <th>Telephone</th>
            </thead>
            <?php
            $leasings = new Leasing();
            $leasings->setRunner($runner);
            foreach ($leasings as $leasing) echo '<tr><td><input type="radio" name="selector" value="'.$leasing->getId().'" onchange="updateForm();"></td><td>'.$leasing->getId().'</td><td>'.$leasing->getName().'</td><td>'.$leasing->getAddress().'</td><td>'.$leasing->getTelephone().'</td></tr>';
            ?>
        </table>
        <br><br>
        <div>
            <form>
                <table>
                    <tr>
                        <td><label>ID</label></td>
                        <td><input type="text" id="id_leasing" disabled></td>
                    </tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr>
                        <td><label>Name</label></td>
                        <td><input type="text" id="name_leasing"></td>
                    </tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr>
                        <td><label>Alamat</label></td>
                        <td><textarea id="address"></textarea></td>
                    </tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr>
                        <td><label>Telepone</label></td>
                        <td><input type="text" id="telephone"></td>
                    </tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr>
                        <td><label>Mode</label></td>
                        <td>
                            <select id="mode" onchange="updateForm();">
                                <option value="1">Tambah</option>
                                <option value="2">Update</option>
                            </select>
                        </td>
                    </tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr>
                        <td><input type="button" value="Submit" onclick="addOrUpdateLeasing();"></td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>
<script>
    window.onload = updateForm();
</script>