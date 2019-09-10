<?php

/**
 * Created by PhpStorm.
 * User: LC
 * Date: 15/07/2017
 * Time: 15:48
 */
require_once standart_path.'core/universal_methode.php';
require_once standart_path.'core/MySQLAccess.php';
require_once standart_path.'dataobject/MerekMobil.php';
require_once standart_path.'dataobject/TipeMobil.php';
require_once standart_path.'dataobject/Mobil.php';
require_once standart_path.'action/GetCars.php';
require_once standart_path.'action/GetKindOfCars.php';

$runner = new Runner();
$runner->connect(GlobalConst\DBConst::HOST, GlobalConst\DBConst::PORT, GlobalConst\DBConst::DATABASE, GlobalConst\DBConst::DATABASE_USERNAME, GlobalConst\DBConst::DATABASE_PASSWORD);
$user = GetCurrentUser();
?>
<script src="assets/js/numeric.js"></script>
<script>
    function GetCars() {
        document.getElementById("container").innerHTML = "";
        var http = new XMLHttpRequest();
        var fd = new FormData();
        fd.append("<?php echo GetCars::BRAND; ?>", document.getElementById("brand").value);
        fd.append("<?php echo GetCars::TYPE; ?>", document.getElementById("type").value);
        fd.append("<?php echo GetCars::KIND; ?>", document.getElementById("kind").value);
        fd.append("<?php echo GetCars::TRANSMISSION; ?>", document.getElementById("transmission").value);
        fd.append("<?php echo GetCars::PRICE_MIN; ?>", getNumericFromNumericWithSeperator(document.getElementById("price_min").value));
        fd.append("<?php echo GetCars::PRICE_MAX; ?>", getNumericFromNumericWithSeperator(document.getElementById("price_max").value));
        fd.append("<?php echo GetCars::ODOMETER_MIN; ?>", getNumericFromNumericWithSeperator(document.getElementById("odo_min").value));
        fd.append("<?php echo GetCars::ODOMETER_MAX; ?>", getNumericFromNumericWithSeperator(document.getElementById("odo_max").value));
        http.open("POST", "<?php echo getUrlByRequest(RequestActionConstant::GET_CARS); ?>", true);
        http.onreadystatechange = function() {
            if( this.readyState === 4 && this.status === 200) {
                var json = JSON.parse(this.responseText);
                if(json.status === 1) {
                    var container = document.getElementById("container");
                    var result = json.result;
                    for ( var i = 0; i < result.length; i++) {
                        var car = document.createElement("div");
                        car.className = "portfolio-item col-sm-4 col-xs-6 margin-bottom-40";
                        var a = document.createElement("a");
                        a.setAttribute("href", "<?php
                                echo getUrlByRequest(RequestPageConstant::PRODUCT);
                                ?>&<?php echo GlobalConst::VAR_CAR ?>="+result[i].<?php echo GetCars::RETURN_ID; ?>);
                        var figure = document.createElement("figure");
figure.style = "background:white;";
                        figure.className = "animate fadeInLeft";
                        figure.style.color = "black";
                        var grid_image = document.createElement("div");
                        grid_image.className = "grid-image";
                        var featured_info = document.createElement("div");
                        featured_info.className = "featured-info";
                        var info = document.createElement("div");
                        info.className = "info-wrapper";
                        info.innerHTML = result[i].<?php echo GetCars::RETURN_NAME; ?>;
                        var img = document.createElement("img");
                        img.setAttribute("alt", "Car Image");
                        img.setAttribute("src", "data:image/"+result[i].<?php echo GetCars::RETURN_IMAGE_TYPE; ?>+";base64,"+result[i].<?php echo GetCars::RETURN_IMAGE; ?>);
                        img.style.height = "300px";
                        img.style.width = "300px";
                        var name = document.createElement("div");
                        name.innerHTML = "Nama : "+result[i].<?php echo GetCars::RETURN_NAME; ?>;
                        var brand = document.createElement("div");
                        brand.innerHTML = "Merek : "+result[i].<?php echo GetCars::RETURN_BRAND; ?>;
                        var type = document.createElement("div");
                        type.innerHTML = "Tipe : "+result[i].<?php echo GetCars::RETURN_TYPE; ?>;
                        var kind = document.createElement("div");
                        kind.innerHTML = "Jenis : "+result[i].<?php echo GetCars::RETURN_KIND; ?>;
                        var transmission = document.createElement("div");
                        transmission.innerHTML = "Transmisi : "+result[i].<?php echo GetCars::RETURN_TRANSMISSION; ?>;
                        var year = document.createElement("div");
                        year.innerHTML = "Tahun : "+result[i].<?php echo GetCars::RETURN_YEAR; ?>;
                        var odometer = document.createElement("div");
                        odometer.innerHTML = "Odometer : "+addNumericSeperator(result[i].<?php echo GetCars::RETURN_ODOMETER; ?>);
                        var price = document.createElement("div");
                        price.innerHTML = "Harga : Rp "+addNumericSeperator(result[i].<?php echo GetCars::RETURN_PRICE; ?>);
                        featured_info.appendChild(info);
                        grid_image.appendChild(featured_info);
                        grid_image.appendChild(img);
                        figure.appendChild(grid_image);
                        figure.appendChild(name);
                        figure.appendChild(brand);
                        figure.appendChild(type);
                        figure.appendChild(kind);
                        figure.appendChild(transmission);
                        figure.appendChild(year);
                        figure.appendChild(odometer);
                        figure.appendChild(price);
                        a.appendChild(figure);
                        car.appendChild(a);
                        container.appendChild(car);
                    }
                }
                else alert(json.message);
            }
        };
        http.send(fd);
    }
    function GetKind() {
        $('#kind').empty();
        var kind = document.getElementById("kind");
        var all = document.createElement("option");
        all.value = 0;
        all.innerHTML = "Semua";
        kind.appendChild(all);
        var brand = document.getElementById("brand");
        var type = document.getElementById("type");
        if( brand.value === 0 && type.value === 0) return;
        var http = new XMLHttpRequest();
        var params = "<?php echo GetKindOfCars::BRAND; ?>="+brand.value;
        params += "&<?php echo GetKindOfCars::TYPE; ?>="+type.value;
        http.open("POST", "<?php echo getUrlByRequest(RequestActionConstant::GET_KIND_OF_CARS); ?>", true);
        http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        http.onreadystatechange = function() {
            if( this.readyState === 4 && this.status === 200) {
                var json = JSON.parse(http.responseText);
                if(json.status === 1) {
                    var result = json.result;
                    for ( var i = 0; i < result.length; i++) {
                        var option = document.createElement("option");
                        option.value = result[i].<?php echo GetKindOfCars::RETURN_ID; ?>;
                        option.innerHTML = result[i].<?php echo GetKindOfCars::RETURN_KIND; ?>;
                        kind.appendChild(option);
                    }
                }
                else alert(json.message);
            }
        };
        http.send(params);
    }
    window.onload = GetKind;
    window.onload = GetCars;
</script>
<div id="porfolio" class="parallax-bg1" style="background-position: 100% 0%;" data-stellar-background-ratio="0.0">
    <div class="container" style="background:white;">
        <table class="table-responsive" style="text-align: center; color: black;">
            <tr>
                <td><label>Merek</label></td>
                <td><label>Tipe</label></td>
                <td><label>Jenis</label></td>
                <td><label>Transmisi</label></td>
            </tr>
            <tr>
                <td><select id="brand" onchange="GetKind(); GetCars();">
                        <option value="0">Semua</option>
                        <?php
                        $mereks = new MerekMobil();
                        $mereks->setRunner($runner);
                        foreach($mereks as $merek)
                            echo '<option value="'.$merek->getId().'">'.$merek->getMerek().'</option>';
                        ?>
                    </select>
                </td>
                <td>
                    <select id="type" onchange="GetKind(); GetCars();">
                        <option value="0">Semua</option>
                        <?php
                        $types = new TipeMobil();
                        $types->setRunner($runner);
                        foreach($types as $type)
                            echo '<option value="'.$type->getId().'">'.$type->getType().'</option>';
                        ?>
                    </select>
                </td>
                <td>
                    <select id="kind" onchange="GetCars()">
                        <option value="0">Semua</option>
                    </select>
                </td>
                <td>
                    <select id="transmission" onchange="GetCars();">
                        <option value="0">Semua</option>
                        <?php
                        $runner->clearQueryArrayArray();
                        $trans = new Transmission();
                        $trans->setRunner($runner);
                        foreach ($trans as $tran)
                            echo '<option value="'.$tran->getId().'">'.$tran->getTransmission().'</option>';
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td rowspan="2"><label>Rentang Harga</label></td>
                <td colspan="2"><label>Minimum</label><br><input id="price_min" onkeyup="GetCars()" oninput="numericPositiveOnly(this.id)"></td>
            </tr>
            <tr>
                <td colspan="2"><label>Maksimum</label><br><input id="price_max" onkeyup="GetCars()" oninput="numericPositiveOnly(this.id)"></td>
            </tr>
            <tr>
                <td rowspan="2"><label>Odometer</label></td>
                <td colspan="2"><label>Minimum</label><br><input id="odo_min" onkeyup="GetCars()" oninput="numericPositiveOnly(this.id)"></td>
            </tr>
            <tr>
                <td colspan="2"><label>Maksimum</label><br><input id="odo_max" onkeyup="GetCars()" oninput="numericPositiveOnly(this.id)"></td>
            </tr>
        </table>
        <div class="row margin-top-40" id="container" style="color: black;"></div>
    </div>
</div>