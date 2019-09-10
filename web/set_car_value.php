<?php
require_once standart_path.'core/RequestActionConstant.php';
require_once standart_path.'action/PreRegistration.php';
require_once standart_path.'action/GetSetCarValue.php';
$user = GetCurrentUser();
if(!$user->isAdmin() && !$user->isSuperAdmin()) {
    header(getUrlByRequest());
    exit();
}
$runner = new Runner();
$runner->connect(GlobalConst\DBConst::HOST, GlobalConst\DBConst::PORT, GlobalConst\DBConst::DATABASE, GlobalConst\DBConst::DATABASE_USERNAME, GlobalConst\DBConst::DATABASE_PASSWORD);
?>
<!-- === BEGIN CONTENT === -->
<div id="content">
    <div class="container background-white">
        <div class="row margin-vert-30">
            <!-- Register Box -->
            <div class="col-md-6 col-md-offset-3 col-sm-offset-3">
                <form class="signup-page margin-top-20" onsubmit="return SetValue();" enctype="multipart/form-data">
                    <div class="signup-header">
                        <h2 class="margin-bottom-20">Tetapan Nilai Mobil</h2>
                    </div>
                    <label>ID Mobil</label>
                    <input class="form-control margin-bottom-20" type="text" id="id_value" oninput="GetValue();" <?php if(filter_input(INPUT_GET, GlobalConst::VAR_CAR) !== null) echo ' value="'.filter_input(INPUT_GET, GlobalConst::VAR_CAR).'"'; ?>>

                    <label>Nilai Dokumen</label>
                    <input class="form-control margin-bottom-20" type="number" min="1" id="document">

                    <label>Nilai Mesin</label>
                    <input class="form-control margin-bottom-20" type="number" min="1" id="engine">

                    <label>Nilai Odometer</label>
                    <input class="form-control margin-bottom-20" type="number" min="1" id="odometer">

                    <label>Nilai Interior</label>
                    <input class="form-control margin-bottom-20" type="number" min="1" id="interior">

                    <label>Nilai Exterior</label>
                    <input class="form-control margin-bottom-20" type="number" min="1" id="exterior">

                    <label>Nilai Tahun</label>
                    <input class="form-control margin-bottom-20" type="number" min="1" id="year">

                    <label>Nilai Harga</label>
                    <input class="form-control margin-bottom-20" type="number" min="1" id="price">

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
<script>
    function SetValue() {
        var fd = new FormData();
        fd.append("<?php echo GetSetCarValue::INPUT_OPERATION; ?>", "<?php  echo GetSetCarValue::OPERATION_SET; ?>");
        fd.append("<?php echo GetSetCarValue::SG_ID; ?>", document.getElementById("id_value").value);
        fd.append("<?php echo GetSetCarValue::SG_DOCUMENT; ?>", document.getElementById("document").value);
        fd.append("<?php echo GetSetCarValue::SG_ENGINE; ?>", document.getElementById("engine").value);
        fd.append("<?php echo GetSetCarValue::SG_ODOMETER; ?>", document.getElementById("odometer").value);
        fd.append("<?php echo GetSetCarValue::SG_INTERIOR; ?>", document.getElementById("interior").value);
        fd.append("<?php echo GetSetCarValue::SG_EXTERIOR; ?>", document.getElementById("exterior").value);
        fd.append("<?php echo GetSetCarValue::SG_YEAR; ?>", document.getElementById("year").value);
        fd.append("<?php echo GetSetCarValue::SG_PRICE; ?>", document.getElementById("price").value);

        var http = new XMLHttpRequest();
        http.open("POST", "<?php echo getUrlByRequest(RequestActionConstant::GET_SET_CAR_VALUE);?>", true);
        http.onreadystatechange = function () {
            if(this.readyState === 4 && this.status === 200) {
                var json = JSON.parse(this.responseText);
                if(json.status === 1) alert("Nilai Mobil telah Diubah");
                else alert(json.message);
            }
        };
        http.send(fd);
        return false;
    }
    function GetValue() {
        if(document.getElementById("id_value").value === null) return;
        var fd = new FormData();
        fd.append("<?php echo GetSetCarValue::INPUT_OPERATION; ?>", "<?php echo GetSetCarValue::OPERATION_GET; ?>");
        fd.append("<?php echo GetSetCarValue::SG_ID; ?>", document.getElementById("id_value").value);

        var http = new XMLHttpRequest();
        http.open("POST", "<?php echo getUrlByRequest(RequestActionConstant::GET_SET_CAR_VALUE);?>", true);
        http.onreadystatechange = function () {
            if(this.readyState === 4 && this.status === 200) {
                var json = JSON.parse(this.responseText);
                if(json.status === 1) {
                    document.getElementById("document").value = json.result["<?php echo GetSetCarValue::SG_DOCUMENT; ?>"];
                    document.getElementById("engine").value = json.result["<?php echo GetSetCarValue::SG_ENGINE; ?>"];
                    document.getElementById("odometer").value = json.result["<?php echo GetSetCarValue::SG_ODOMETER; ?>"];
                    document.getElementById("interior").value = json.result["<?php echo GetSetCarValue::SG_INTERIOR; ?>"];
                    document.getElementById("exterior").value = json.result["<?php echo GetSetCarValue::SG_EXTERIOR; ?>"];
                    document.getElementById("year").value = json.result["<?php echo GetSetCarValue::SG_YEAR; ?>"];
                    document.getElementById("price").value = json.result["<?php echo GetSetCarValue::SG_PRICE; ?>"];
                }
                else alert(json.message);
            }
        };
        http.send(fd);
    }
    window.onload = GetValue();
</script>