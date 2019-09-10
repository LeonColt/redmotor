<?php

/**
 * Created by PhpStorm.
 * User: LC
 * Date: 15/07/2017
 * Time: 15:50
 */
require_once standart_path.'core/universal_methode.php';
require_once standart_path.'core/MySQLAccess.php';
require_once standart_path.'dataobject/Mobil.php';
require_once standart_path.'action/GetCarsByIds.php';

$runner = new Runner();
$runner->connect(GlobalConst\DBConst::HOST, GlobalConst\DBConst::PORT, GlobalConst\DBConst::DATABASE, GlobalConst\DBConst::DATABASE_USERNAME, GlobalConst\DBConst::DATABASE_PASSWORD);
$user = GetCurrentUser();
?>
<script src="assets/js/numeric.js"></script>
<script>
    function GetCars() {
        document.getElementById("container").innerHTML = "";
        var fd = new FormData();
        <?php
        foreach ($_POST["data"] as $index => $datum) echo 'fd.append("'.GetCarsByIds::INPUT_IDS.'['.$index.']", "'.$datum['id'].'");';
        ?>
        var datum = [
            <?php
                end($_POST["data"]);
                $end = key($_POST["data"]);
                foreach ($_POST["data"] as $index => $datum) {
                    echo '{"id" : '.$datum['id'].', "score" : '.$datum['score'].'}';
                    if($index !== $end) echo  ",";
                }
            ?>
        ];
        var http = new XMLHttpRequest();
        http.open("POST", "<?php echo getUrlByRequest(RequestActionConstant::GET_CARS_BY_IDS); ?>", true);
        http.onreadystatechange = function() {
            if( this.readyState === 4 && this.status === 200) {
alert(this.responseText);
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
                                ?>&<?php echo GlobalConst::VAR_CAR ?>="+result[i].<?php echo GetCarsByIds::RETURN_ID; ?>);
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
                        info.innerHTML = result[i].<?php echo GetCarsByIds::RETURN_NAME; ?>;
                        var img = document.createElement("img");
                        img.setAttribute("alt", "Car Image");
                        img.setAttribute("src", "data:image/"+result[i].<?php echo GetCarsByIds::RETURN_IMAGE_TYPE; ?>+";base64,"+result[i].<?php echo GetCarsByIds::RETURN_IMAGE; ?>);
                        img.style.height = "300px";
                        img.style.width = "300px";
                        var score = document.createElement("div");
                        for(var j = 0; j < datum.length; j++) {
                            if(result[i].<?php echo GetCarsByIds::RETURN_ID; ?> === datum[j].id) {
                                score.innerHTML = "Skor : "+(datum[j].score * 100).toFixed(2);
                                break;
                            }
                        }
                        var name = document.createElement("div");
                        name.innerHTML = "Nama : "+result[i].<?php echo GetCarsByIds::RETURN_NAME; ?>;
                        var brand = document.createElement("div");
                        brand.innerHTML = "Merek : "+result[i].<?php echo GetCarsByIds::RETURN_BRAND; ?>;
                        var type = document.createElement("div");
                        type.innerHTML = "Tipe : "+result[i].<?php echo GetCarsByIds::RETURN_TYPE; ?>;
                        var kind = document.createElement("div");
                        kind.innerHTML = "Jenis : "+result[i].<?php echo GetCarsByIds::RETURN_KIND; ?>;
                        var transmission = document.createElement("div");
                        transmission.innerHTML = "Transmisi : "+result[i].<?php echo GetCarsByIds::RETURN_TRANSMISSION; ?>;
                        var year = document.createElement("div");
                        year.innerHTML = "Tahun : "+result[i].<?php echo GetCarsByIds::RETURN_YEAR; ?>;
                        var odometer = document.createElement("div");
                        odometer.innerHTML = "Odometer : "+addNumericSeperator(result[i].<?php echo GetCarsByIds::RETURN_ODOMETER; ?>);
                        var price = document.createElement("div");
                        price.innerHTML = "Harga : Rp "+addNumericSeperator(result[i].<?php echo GetCarsByIds::RETURN_PRICE; ?>);
                        featured_info.appendChild(info);
                        grid_image.appendChild(featured_info);
                        grid_image.appendChild(img);
                        figure.appendChild(grid_image);
                        figure.appendChild(score);
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
    window.onload = GetCars;
</script>
<div id="porfolio" class="parallax-bg1" style="background-position: 50% 0%;" data-stellar-background-ratio="0.0">
    <div class="container">
        <div class="row margin-top-40" id="container"></div>
    </div>
</div>