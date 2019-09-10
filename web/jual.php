<?php
require_once standart_path.'core/RequestActionConstant.php';
require_once standart_path.'action/PreRegistration.php';
$user = GetCurrentUser();
require_once standart_path.'dataobject/Mobil.php';
require_once standart_path.'action/SubmitBuy.php';
$runner = new Runner();
$runner->connect(GlobalConst\DBConst::HOST, GlobalConst\DBConst::PORT, GlobalConst\DBConst::DATABASE, GlobalConst\DBConst::DATABASE_USERNAME, GlobalConst\DBConst::DATABASE_PASSWORD);
?>
<script>
    function check(){
        if(document.getElementById("id_jual").value === null || document.getElementById("id_mobil").value === null ||
document.getElementById("id_jual").value === 0 || document.getElementById("id_mobil").value === 0)
            location.reload();

        if(document.getElementById("name").value === null || document.getElementById("name").value === ""
            || document.getElementById("kind").value === null || document.getElementById("kind").value === ""
            || document.getElementById("transmission").value === null || document.getElementById("transmission").value === ""
            || document.getElementById("year").value === null || document.getElementById("year").value === ""
            || document.getElementById("color").value === null || document.getElementById("color").value === ""
            || document.getElementById("odometer").value === null || document.getElementById("odometer").value === ""
            || document.getElementById("nengine").value === null || document.getElementById("nengine").value === ""
            || document.getElementById("nchassis").value === null || document.getElementById("nchassis").value === null
            || document.getElementById("price").value === null || document.getElementById("price").value === null
            || document.getElementById("pic").value === null || document.getElementById("pic").value === "") {
            alert("Semua Form harus terisi");
            return false;
        }
        if(document.getElementById("year").value.length !== 4){alert("tahun harus panjangnya harus 4"); return false;}

        var form = new FormData();
        form.append("<?php echo SubmitBuy::INPUT_ID; ?>", document.getElementById("id_jual").value);
        form.append("<?php echo SubmitBuy::INPUT_PRICE; ?>", getNumericFromNumericWithSeperator(document.getElementById("price").value));
        form.append("<?php echo SubmitBuy::INPUT_CAR_ID; ?>", document.getElementById("id_mobil").value);
        form.append("<?php echo SubmitBuy::INPUT_CAR_NAME; ?>", document.getElementById("name").value);
        form.append("<?php echo SubmitBuy::INPUT_CAR_KIND; ?>", document.getElementById("kind").value);
        form.append("<?php echo SubmitBuy::INPUT_CAR_TRANSMISSION; ?>", document.getElementById("transmission").value);
        form.append("<?php echo SubmitBuy::INPUT_CAR_YEAR; ?>", document.getElementById("year").value);
        form.append("<?php echo SubmitBuy::INPUT_CAR_COLOR; ?>", document.getElementById("color").value);
        form.append("<?php echo SubmitBuy::INPUT_CAR_ODOMETER; ?>", getNumericFromNumericWithSeperator(document.getElementById("odometer").value));
        form.append("<?php echo SubmitBuy::INPUT_CAR_NENGINE; ?>", document.getElementById("nengine").value);
        form.append("<?php echo SubmitBuy::INPUT_CAR_NCHASSIS; ?>", document.getElementById("nchassis").value);
        form.append("<?php echo SubmitBuy::INPUT_CAR_PIC; ?>", document.getElementById("pic").files[0]);

        var http = new XMLHttpRequest();
        http.upload.addEventListener("progress", sendProgress, false);
        http.open("POST", "<?php echo getUrlByRequest(RequestActionConstant::SUBMIT_BUY); ?>", true);
        http.onreadystatechange = function() {
alert(this.responseText);
            var json = JSON.parse(http.responseText);
            if(json.status === 1){
                alert("Email konfirmasi telah dikirim, silahkan Datang ke Showroom untuk Informasi Lebih Lanjut");
                window.location.href = "<?php echo getUrlByRequest(); ?>";
            }
            else alert(json.message);
        };
        http.send(form);
        return false;
    }
    function onFileSelect() {
        var file = document.getElementById("pic").files[0];
        if(file) {
            var file_size = 0;
            if (file.size > 1024 * 1024)
                file_size = (Math.round(file.size * 100 / (1024 * 1024)) / 100).toString() + 'MB';
            else
                file_size = (Math.round(file.size * 100 / 1024) / 100).toString() + 'KB';
            document.getElementById("pic_name").innerHTML = file.name;
            document.getElementById("pic_size").innerHTML = file_size;
            document.getElementById("pic_type").innerHTML = file.type;
var reader = new FileReader();
        reader.onload = function()
        {
            var output = document.getElementById('previewer');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
        }
    }
    function sendProgress(progress) {
        if(progress.lengthComputable) {
            var percent = Math.round(progress.loaded * 100 / progress.total);
            document.getElementById("pd").innerHTML = percent.toString() + "%";
            document.getElementById('pb').value = percent;
        }
        else document.getElementById("pd").innerHTML = "unknown progress";
    }
    window.onload = function () {
        var http = new XMLHttpRequest();
        http.open("POST", "<?php echo getUrlByRequest(RequestActionConstant::GET_NEW_CAR_ID); ?>", true);
        http.onreadystatechange = function () {
            if(this.readyState === 4 && this.status === 200) {
                var json = JSON.parse(http.responseText);
                if(json.status === 1) document.getElementById("id_mobil").value = json.result;
            }
        };
        http.send();

        var http1 = new XMLHttpRequest();
        http1.open("POST", "<?php echo getUrlByRequest(RequestActionConstant::GET_SELL_ID); ?>", true);
        http1.onreadystatechange = function () {
            if(this.readyState === 4 && this.status === 200) {
                var json = JSON.parse(http1.responseText);
                if(json.status === 1) document.getElementById("id_jual").value = json.result;
            }
        };
        http1.send();
    };
</script>
<!-- === BEGIN CONTENT === -->
<div id="content">
    <div class="container background-white">
        <div class="row margin-vert-30">
            <!-- Register Box -->
            <div class="col-md-6 col-md-offset-3 col-sm-offset-3">
                <form class="signup-page margin-top-20" onsubmit="return check();" enctype="multipart/form-data">
                    <div class="signup-header">
                        <h2 class="margin-bottom-20">Jual Mobil</h2>
                    </div>
                    <label>Id Jual</label>
                    <input class="form-control margin-bottom-20" type="text" id="id_jual" disabled>

                    <label>Id Mobil</label>
                    <input class="form-control margin-bottom-20" type="text" id="id_mobil" disabled>

                    <label>Nomor Polisi</label>
                    <input class="form-control margin-bottom-20" type="text" id="name">

                    <label>Jenis</label>
                    <select class="form-control margin-bottom-20" id="kind">
                        <?php
                        require_once standart_path.'dataobject/JenisMobil.php';
                        $runner->clearQueryArrayArray();
                        $kinds = new JenisMobil();
                        $kinds->setRunner($runner);
                        foreach ($kinds as $kind)
                            echo '<option value="'.$kind->getId().'">'.$kind->getJenis().'</option>';
                        ?>
                    </select>

                    <label>Transmisi</label>
                    <select class="form-control margin-bottom-20" id="transmission">
                        <?php
                        require_once standart_path.'dataobject/Tranmission.php';
                        $runner->clearQueryArrayArray();
                        $trans = new Transmission();
                        $trans->setRunner($runner);
                        foreach ($trans as $tran)
                            echo '<option value="'.$tran->getId().'">'.$tran->getTransmission().'</option>';
                        ?>
                    </select>

                    <label>Tahun Pembuatan</label>
                    <input class="form-control margin-bottom-20" type="text" id="year">

                    <label>Warna</label>
                    <input class="form-control margin-bottom-20" type="text" id="color">

                    <label>Odometer Sekarang</label>
                    <input class="form-control margin-bottom-20" id="odometer" oninput="numericPositiveOnly(this.id)">

                    <label>Nomor Mesin</label>
                    <input class="form-control margin-bottom-20" type="text" id="nengine">

                    <label>Nomor Rangka</label>
                    <input class="form-control margin-bottom-20" type="text" id="nchassis">

                    <label>Gambar</label>
<br>
<img id="previewer" alt="Belum ada Gambar" src="assets/img/no_image.png">
                    <input type="file" id="pic" onchange="onFileSelect();" accept="image/*">
                    <div id="pic_name"></div>
                    <div id="pic_size"></div>
                    <div id="pic_type"></div>

                    <label>Harga</label>
                    <input class="form-control margin-bottom-20" id="price" oninput="numericPositiveOnly(this.id)">

                    <label id="pd">0%</label><br>
                    <progress id="pb" value="0" max="100.0"></progress>

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