<?php
require_once standart_path.'core/RequestActionConstant.php';
$user = GetCurrentUser();
require_once standart_path.'dataobject/Mobil.php';
require_once standart_path.'action/EditCar.php';

$runner = new Runner();
$runner->connect(GlobalConst\DBConst::HOST, GlobalConst\DBConst::PORT, GlobalConst\DBConst::DATABASE, GlobalConst\DBConst::DATABASE_USERNAME, GlobalConst\DBConst::DATABASE_PASSWORD);
$id = filter_input(INPUT_GET, GlobalConst::VAR_CAR, FILTER_VALIDATE_INT);
if(empty($id)) {
    echo '<script>
alert("Mobil Tidak Ada");
window.location = "'.getUrlByRequest().'";
</script>';
    return;
}
$car = new Mobil($id);
$car->appendLinkJoin(DataObjectConstant::TABLE_CAR_KIND);
$car->appendLinkJoin(DataObjectConstant::TABLE_CAR_TRANSMISSION);
$car->appendLoadBy(Mobil::LOAD_BY_ID);
$car->setRunner($runner);
if(!$car->load()) {
    echo '<script>
alert("Mobil Tidak Ada");
window.location = "'.getUrlByRequest().'";
</script>';
    return;
}
?>
<script>
    function check(){
        if( document.getElementById("id_mobil").value === null || document.getElementById("id_mobil").value === ""
            || document.getElementById("name").value === null || document.getElementById("name").value === ""
            || document.getElementById("kind").value === null || document.getElementById("kind").value === ""
            || document.getElementById("transmission").value === null || document.getElementById("transmission").value === ""
            || document.getElementById("year").value === null || document.getElementById("year").value === ""
            || document.getElementById("color").value === null || document.getElementById("color").value === ""
            || document.getElementById("odometer").value === null || document.getElementById("odometer").value === ""
            || document.getElementById("nengine").value === null || document.getElementById("nengine").value === ""
            || document.getElementById("nchassis").value === null || document.getElementById("nchassis").value === null
            || document.getElementById("price").value === null || document.getElementById("price").value === null
            || document.getElementById("pic").value === null || document.getElementById("pic").value === "") {
            alert("Semua Form harus terisi dan Gambar Mobil harus ada");
            return false;
        }
        if(document.getElementById("year").value.length !== 4){alert("tahun panjangnya harus 4"); return false;}

        var form = new FormData();
        form.append("<?php echo EditCar::INPUT_CAR_ID; ?>", document.getElementById("id_mobil").value);
        form.append("<?php echo EditCar::INPUT_CAR_NAME; ?>", document.getElementById("name").value);
        form.append("<?php echo EditCar::INPUT_CAR_KIND; ?>", document.getElementById("kind").value);
        form.append("<?php echo EditCar::INPUT_CAR_TRANSMISSION; ?>", document.getElementById("transmission").value);
        form.append("<?php echo EditCar::INPUT_CAR_YEAR; ?>", document.getElementById("year").value);
        form.append("<?php echo EditCar::INPUT_CAR_COLOR; ?>", document.getElementById("color").value);
        form.append("<?php echo EditCar::INPUT_CAR_ODOMETER; ?>", getNumericFromNumericWithSeperator(document.getElementById("odometer").value));
        form.append("<?php echo EditCar::INPUT_CAR_NENGINE; ?>", document.getElementById("nengine").value);
        form.append("<?php echo EditCar::INPUT_CAR_NCHASSIS; ?>", document.getElementById("nchassis").value);
        form.append("<?php echo EditCar::INPUT_CAR_PRICE; ?>", getNumericFromNumericWithSeperator(document.getElementById("price").value));
        form.append("<?php echo EditCar::INPUT_CAR_PIC; ?>", document.getElementById("pic").files[0]);

        var http = new XMLHttpRequest();
        http.upload.addEventListener("progress", sendProgress, false);
        http.open("POST", "<?php echo getUrlByRequest(RequestActionConstant::SUBMIT_EDIT_CAR); ?>", true);
        http.onreadystatechange = function() {
alert(this.responseText);
            var json = JSON.parse(http.responseText);
            if(json.status === 1){
                alert("Mobil telah diedit");
                window.location.href = "<?php echo getUrlByRequest(); ?>";
            }
            else alert(json.message);
        };
        http.send(form);
        return false;
    }
    function onFileSelect(event) {
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
        }
        var reader = new FileReader();
        reader.onload = function()
        {
            var output = document.getElementById('previewer');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
    function sendProgress(progress) {
        if(progress.lengthComputable) {
            var percent = Math.round(progress.loaded * 100 / progress.total);
            document.getElementById("pd").innerHTML = percent.toString() + "%";
            document.getElementById('pb').value = percent;
        }
        else document.getElementById("pd").innerHTML = "unknown progress";
    }
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
                    <label>Id Mobil</label>
                    <input class="form-control margin-bottom-20" type="text" value="<?php echo $car->getId(); ?>" id="id_mobil" disabled>

                    <label>Nama/Nomor Polisi</label>
                    <input class="form-control margin-bottom-20" type="text" value="<?php echo $car->getName(); ?>" id="name">

                    <label>Jenis</label>
                    <select class="form-control margin-bottom-20" id="kind">
                        <?php
                        require_once standart_path.'dataobject/JenisMobil.php';
                        $runner->clearQueryArrayArray();
                        $kinds = new JenisMobil();
                        $kinds->setRunner($runner);
                        foreach ($kinds as $kind)
                            echo '<option value="'.$kind->getId().'" '.( ($kind->getId() === $car->getJenis()->getId()) ? ' selected="selected"' : '' ).'>'.$kind->getJenis().'</option>';
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
                            echo '<option value="'.$tran->getId().'" '.( ($tran->getId() === $car->getTransmission()->getId()) ? ' selected="selected"' : '' ).'>'.$tran->getTransmission().'</option>';
                        ?>
                    </select>

                    <label>Tahun Pembuatan</label>
                    <input class="form-control margin-bottom-20" type="text" value="<?php echo $car->getYear(); ?>" id="year">

                    <label>Warna</label>
                    <input class="form-control margin-bottom-20" type="text"value="<?php echo $car->getColor();?>" id="color">

                    <label>Odometer Sekarang</label>
                    <input class="form-control margin-bottom-20" id="odometer" value="<?php echo number_format($car->getOdometer()); ?>" oninput="numericPositiveOnly(this.id);">

                    <label>Nomor Mesin</label>
                    <input class="form-control margin-bottom-20" type="text" id="nengine" value="<?php echo $car->getNengine(); ?>">

                    <label>Nomor Rangka</label>
                    <input class="form-control margin-bottom-20" type="text" id="nchassis" value="<?php echo $car->getNchasis(); ?>">

                    <label>Gambar</label>
                    <br>
                    <img id="previewer" src="data:image/<?php echo $car->getPic()->getType()?>;base64,<?php echo $car->getPic()->getImage(); ?>">
                    <input type="file" id="pic" onchange="onFileSelect(event);" accept="image/*">
                    <div id="pic_name"></div>
                    <div id="pic_size"></div>
                    <div id="pic_type"></div>

                    <label>Harga</label>
                    <input class="form-control margin-bottom-20" id="price" value="<?php echo number_format($car->getPrice()); ?>" oninput="numericPositiveOnly(this.id);">

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