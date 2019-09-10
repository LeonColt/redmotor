<?php

/**
 * Created by PhpStorm.
 * User: LC
 * Date: 07/07/2017
 * Time: 10:53
 */
require_once standart_path.'core/RequestActionConstant.php';
require_once standart_path.'action/PreRegistration.php';
require_once standart_path.'process/AHP.php';
require_once standart_path.'action/ProcessAHP.php';
require_once standart_path.'dataobject/Mobil.php';
$user = GetCurrentUser();
$runner = new Runner();
$runner->connect(GlobalConst\DBConst::HOST, GlobalConst\DBConst::PORT, GlobalConst\DBConst::DATABASE, GlobalConst\DBConst::DATABASE_USERNAME, GlobalConst\DBConst::DATABASE_PASSWORD);

$cars = new Mobil();
$cars->setRunner($runner);
$cars->appendLinkJoin(DataObjectConstant::TABLE_CAR_KIND);
$cars->appendLinkJoin(DataObjectConstant::TABLE_CAR_TYPE);
$cars->appendLinkJoin(DataObjectConstant::TABLE_CAR_BRAND);
$cars->appendLinkJoin(DataObjectConstant::TABLE_CAR_TRANSMISSION);
$cars->appendLinkJoin(DataObjectConstant::TABLE_CAR_VALUE);
$cars->appendLoopBy(Mobil::LOOP_BY_NOT_SOLD);

if($cars->count() < 3) {
    echo '<script>window.location.href = "'.getUrlByRequest().'";</script>';
    exit();
}


$ahp_keys = getAHPKey();
?>
<script>
    function setCookie(cname, cvalue, exdays, path ='/') {
        var d = new Date();
        d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
        var expires = "expires="+d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path="+path;
    }
    function getCookie(cname) {
        var name = cname + "=";
        var decodedCookie = decodeURIComponent(document.cookie);
        var ca = decodedCookie.split(';');
        for(var i = 0; i <ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) === ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) === 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }
    window.onload = function () {
        if(!navigator.cookieEnabled) return;
        var pre_json = getCookie("ahp_input");
        if(pre_json === "") return;
        var json = JSON.parse(pre_json);
        document.getElementById("car_type").selectedIndex = json.car_type;
        document.getElementById("price_min").value = json.price_min;
        document.getElementById("price_max").value = json.price_max;

        for (var i = 0; i < json.criteria.length; i++) {
            for(var j = i + 1; j < json.criteria.length; j++) {
                var locator = json.criteria[i]+"_"+json.criteria[j];
                var radio = document.getElementsByName(locator);
                for ( var k = 0; k < radio.length; k++) {
                    if(radio[k].value === json.result[locator]) {
                        radio[k].checked = true;
                        break;
                    }
                }
            }
        }
    };
    function sendAHPInput(cjson) {
        var fd = new FormData();
        fd.append("<?php echo ProcessAHP::INPUT_CAR_TYPE;?>", cjson.car_type);
        fd.append("<?php echo ProcessAHP::INPUT_MIN_PRICE; ?>", getNumericFromNumericWithSeperator(cjson.price_min));
        fd.append("<?php echo ProcessAHP::INPUT_MAX_PRICE; ?>", getNumericFromNumericWithSeperator(cjson.price_max));

        for(var i = 0; i < cjson.criteria.length; i++)
            fd.append("<?php echo ProcessAHP::INPUT_CRITERIA; ?>["+i+"]", cjson.criteria[i]);

        for (var i = 0; i < cjson.criteria.length; i++) {
            for(var j = i + 1; j < cjson.criteria.length; j++) {
                var locator = cjson.criteria[i]+"_"+cjson.criteria[j];
                fd.append("<?php echo ProcessAHP::INPUT_DATA; ?>["+locator+"]" , cjson.result[locator]);
            }
        }

        var http = new XMLHttpRequest();
        http.open("POST", "<?php echo getUrlByRequest(RequestActionConstant::PROCESS_AHP); ?>", true);
        http.onreadystatechange = function() {
            if(this.readyState === 4 && this.status === 200) {
                console.log(this.responseText);
                var json = JSON.parse(this.responseText);
                if(json.status === 1) redirect(json);
                else alert(json.message);
            }
        };
        http.send(fd);
    }
    function redirect(json) {
        var form = document.createElement('form');
        form.method = "POST";
        form.action = "<?php echo getUrlByRequest(); ?>";
        if(json.result.length === 0) {
            alert("Tidak ada mobil yang sesuai dengan kriteria yang anda masukkan");
            return;
        }
        for (var i = 0; i < json.result.length; i++) {
            var input_id = document.createElement("input");
            input_id.type = "hidden";
            input_id.name = "data["+i+"][id]";
            input_id.value = json.result[i].id;
            form.appendChild(input_id);

            var input_score = document.createElement("input");
            input_score.type = "hidden";
            input_score.name = "data["+i+"][score]";
            input_score.value = json.result[i].score;
            form.appendChild(input_score);
        }
        var btt = document.createElement("input");
        btt.type = "submit";
        btt.name = "ahp_submitter";
        btt.value = "submitter";
        form.appendChild(btt);
        document.body.appendChild(form);
        form.submit();
    }
    function hook() {
        <?php
        end($ahp_keys);
        $last_key = key($ahp_keys);
        reset($ahp_keys);
        foreach ($ahp_keys as $index => $key) {
            echo 'var checked = false;var temp = document.getElementsByName("'.$key[0].'_'.$key[1].'");';
            echo 'for( var i = 0; i < temp.length; i++) {';
            echo 'if(temp[i].checked) {checked = true; break;}}';
            echo 'if(!checked) {alert("soal no : '.($index + 1).'"); alert("Semua Kriteria Harus Diisi");return false;}';
        }
        ?>

        var cjson = {};
        cjson.car_type = document.getElementById("car_type").value;
        cjson.price_min = document.getElementById("price_min").value;
        cjson.price_max = document.getElementById("price_max").value;
        cjson.criteria = [];
        cjson.result = {};

        <?php
        foreach (GlobalConst\AHPConst::CRITERIAS as $CRITERIA) echo 'cjson.criteria.push("'.$CRITERIA.'");';

        foreach ($ahp_keys as $index => $key) {
            echo 'temp = document.getElementsByName("'.$key[0].'_'.$key[1].'");';
            echo 'for( var i = 0; i < temp.length; i++) {';
            echo 'if(temp[i].checked){ cjson.result.'.$key[0].'_'.$key[1].' = temp[i].value; break; }';
            echo '}';
        }
        ?>
        if(navigator.cookieEnabled) setCookie("ahp_input", JSON.stringify(cjson), 365);
        sendAHPInput(cjson);
        return false;
    }
</script>
<!-- === BEGIN CONTENT === -->
<div id="content">
    <div class="container background-white">
        <div class="row margin-vert-30">
            <!-- Register Box -->
            <div class="col-md-6 col-md-offset-3 col-sm-offset-3">
                <form class="signup-page margin-top-20" onsubmit="return hook();">
                    <div class="signup-header">
                        <h2 class="margin-bottom-20">Pemesanan Mobil</h2>
                    </div>
                    <label>Tipe Mobil</label>
                    <select class="form-control margin-bottom-0" id="car_type">
                        <option value="0">Semua</option>
                        <?php
                        $types = new TipeMobil();
                        $types->setRunner($runner);
                        foreach ($types as $type) echo '<option value="'.$type->getId().'">'.$type->getType().'</option>';
                        ?>
                    </select>

                    <label>Harga Minimum</label>
                    <input class="form-control margin-bottom-0" type="text" name="price_min" id="price_min" oninput="numericPositiveOnly(this.id);">

                    <label>Harga Maksimum</label>
                    <input class="form-control margin-bottom-0" type="text" name="price_max" id="price_max" oninput="numericPositiveOnly(this.id);">

                    <br>


                    <?php
                    foreach ($ahp_keys as $index => $item) {
                        echo '<label>'.($index + 1).'. Menurut Anda Lebih Penting Kriteria '.$item[0].' atau '.$item[1].'?</label><br>';
                        echo '<input type="radio" name="'.$item[0].'_'.$item[1].'" id="'.$item[0].'_'.$item[1].'" value="4-1"> <label for="dokumen_mesin_1_0">Memilih '.$item[0].'</label><br>';
                        echo '<input type="radio" name="'.$item[0].'_'.$item[1].'" id="'.$item[0].'_'.$item[1].'" value="1-1"> <label for="dokumen_mesin_1_1">Sama Sama Penting</label><br>';
                        echo '<input type="radio" name="'.$item[0].'_'.$item[1].'" id="'.$item[0].'_'.$item[1].'" value="1-4"> <label for="dokumen_mesin_0_1">Memilih '.$item[1].'</label><br>';
                        echo '<br>';
                    }
                    ?>
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