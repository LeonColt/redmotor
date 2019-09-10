<?php
require_once standart_path.'core/universal_methode.php';
require_once standart_path.'core/MySQLAccess.php';
require_once standart_path.'dataobject/Pembelian.php';
require_once standart_path.'action/GetBuying.php';
require_once standart_path.'action/SubmitBuying.php';

$runner = new Runner();
$runner->connect(GlobalConst\DBConst::HOST, GlobalConst\DBConst::PORT, GlobalConst\DBConst::DATABASE, GlobalConst\DBConst::DATABASE_USERNAME, GlobalConst\DBConst::DATABASE_PASSWORD);
$user = GetCurrentUser();

?>
<script>
    function GetBuying() {
        var type = document.getElementById("selling_type");
        var fd = new FormData();
        fd.append("<?php echo GetBuying::INPUT_DATE_FINISH_UNFINISHED; ?>", (type === null) ? 0 : type.value);

        var http = new XMLHttpRequest();
        http.open("POST", "<?php echo getUrlByRequest(RequestActionConstant::GET_BUYING); ?>", true);
        http.onreadystatechange = function () {
            if(this.readyState === 4 && this.status === 200) {
                var json = JSON.parse(this.responseText);
                if(json.status === 1) {
                    var elmtTable = document.getElementById('mt');
                    var tableRows = elmtTable.getElementsByTagName('tr');
                    var rowCount = tableRows.length;
                    for (var x=rowCount-1; x>-1; x--) elmtTable.removeChild(tableRows[x]);
                    var table = document.getElementById("mt");

                    var headers = [
                        "ID",
                        "Tanggal",
                        "ID Mobil",
                        "Nama Mobil",
                        "ID Penjual",
                        "Username Penjual",
                        "Penawaran Harga",
                        "Harga Deal",
                        "Harga Jual",
                        "Submit"
                    ];

                    var header = document.createElement("tr");

                    for(var i = 0; i < headers.length; i++) {
                        var header_column = document.createElement("th");
                        var header_label = document.createElement("label");
                        header_label.innerHTML = headers[i];
                        header_column.appendChild(header_label);
                        header.appendChild(header_column);
                    }
                    table.appendChild(header);

                    for(i = 0; i < json.result.length; i++) {
                        var tr = document.createElement("tr");

                        var td_id = document.createElement("td");
                        var lbl_id = document.createElement("label");
                        lbl_id.innerHTML = json.result[i].<?php echo GetBuying::RETURN_ID; ?>;
                        td_id.appendChild(lbl_id);
                        tr.appendChild(td_id);

                        var td_date = document.createElement("td");
                        td_date.innerHTML = json.result[i].<?php echo GetBuying::RETURN_DATE; ?>;
                        tr.appendChild(td_date);

                        var td_car_id = document.createElement("td");
                        var car_link = document.createElement("a");
                        car_link.href = "<?php
                                echo getUrlByRequest(RequestPageConstant::PRODUCT);
                            ?>&<?php echo GlobalConst::VAR_CAR; ?>="+json.result[i].<?php echo GetBuying::RETURN_CAR_ID; ?>;
                        car_link.target = "_blank";
                        car_link.innerHTML = json.result[i].<?php echo GetBuying::RETURN_CAR_ID; ?>;
                        td_car_id.appendChild(car_link);
                        tr.appendChild(td_car_id);

                        var td_car_name = document.createElement("td");
                        td_car_name.innerHTML = json.result[i].<?php echo GetBuying::RETURN_CAR_NAME; ?>;
                        tr.appendChild(td_car_name);

                        var td_user_id = document.createElement("td");
                        td_user_id.innerHTML = json.result[i].<?php echo GetBuying::RETURN_USER_ID; ?>;
                        tr.appendChild(td_user_id);

                        var td_user_username = document.createElement("td");
                        td_user_username.innerHTML = json.result[i].<?php echo GetBuying::RETURN_USER_USERNAME; ?>;
                        tr.appendChild(td_user_username);

                        var td_price = document.createElement("td");
                        td_price.innerHTML = json.result[i].<?php echo GetBuying::RETURN_PRICE; ?>;
                        tr.appendChild(td_price);

                        var td_deal_price = document.createElement("td");
                        if(json.result[i].<?php echo GetBuying::RETURN_DATE; ?> === null) {
                            var input_deal_price = document.createElement("input");
                            input_deal_price.setAttribute("id", "deal_price_"+json.result[i].<?php echo GetBuying::RETURN_ID; ?>);
                            input_deal_price.setAttribute("oninput", "numericPositiveOnly(this.id);");
                            td_deal_price.appendChild(input_deal_price);
                        }
                        else td_deal_price.innerHTML = json.result[i].<?php echo GetBuying::RETURN_DEAL_PRICE; ?>;
                        tr.appendChild(td_deal_price);

                        var td_sell_price = document.createElement("td");
                        if(json.result[i].<?php echo GetBuying::RETURN_DATE; ?> === null) {
                            var input_sell_price = document.createElement("input");
                            input_sell_price.setAttribute("id", "sell_price_"+json.result[i].<?php echo GetBuying::RETURN_ID; ?>);
                            input_sell_price.setAttribute("oninput", "numericPositiveOnly(this.id);");
                            td_sell_price.appendChild(input_sell_price);
                        }
                        else td_sell_price.innerHTML = json.result[i].<?php echo GetBuying::RETURN_SELL_PRICE; ?>;
                        tr.appendChild(td_sell_price);

                        var td_submit = document.createElement("td");
                        if(json.result[i].<?php echo GetBuying::RETURN_DATE; ?> === null) {
                            var btt_submit = document.createElement("button");
                            btt_submit.setAttribute("onclick", "SubmitBuying("+json.result[i].<?php echo GetBuying::RETURN_ID; ?>+", "+json.result[i].<?php echo GetBuying::RETURN_CAR_ID; ?>+");");
                            btt_submit.innerHTML = "Submit";
                            td_submit.appendChild(btt_submit);
                        }
                        else td_submit.innerHTML = "Submitted";
                        tr.appendChild(td_submit);

                        table.appendChild(tr);
                    }
                }
                else alert(json.message);
            }
        };
        http.send(fd);
    }
    window.onload = GetBuying();
    function SubmitBuying(id, car_id) {

        if(document.getElementById("deal_price_"+id).value === null
            || document.getElementById("deal_price_"+id).value === ""
            || document.getElementById("sell_price_"+id).value === null
            || document.getElementById("sell_price_"+id).value === "") {
            alert("Harga Deal dan Harga Jual harus diinput");
            return;
        }

        var fd = new FormData();
        fd.append("<?php echo SubmitBuying::INPUT_ID; ?>", id );
        fd.append("<?php echo SubmitBuying::INPUT_CAR_ID; ?>", car_id);
        fd.append("<?php echo SubmitBuying::INPUT_DEAL_PRICE; ?>", getNumericFromNumericWithSeperator(document.getElementById("deal_price_"+id).value));
        fd.append("<?php echo SubmitBuying::INPUT_SELL_PRICE; ?>", getNumericFromNumericWithSeperator(document.getElementById("sell_price_"+id).value));

        var http = new XMLHttpRequest();
        http.open("POST", "<?php echo getUrlByRequest(RequestActionConstant::SUBMIT_BUYING ); ?>", true);
        http.onreadystatechange = function () {
            if(this.readyState === 4 && this.status === 200) {
                var json = JSON.parse(this.responseText);
                if(json.status === 1) {
                    alert("Harga Deal Mobil telah Diinput");
                    window.location.href = "<?php
                            $url = getUrlByRequest(RequestPageConstant::SET_CAR_VALUE);
                            $url = addGetToUrl($url, GlobalConst::VAR_CAR, "");
                            echo $url;
                        ?>"+car_id;
                }
                else alert(json.message);
            }
        };
        http.send(fd);
    }
</script>
<div id="porfolio" style="background-position: 50% 0%;" data-stellar-background-ratio="0.5">
    <div class="container">
        <label>Tipe Pembelian</label>
        <select class="form-control margin-bottom-20" id="selling_type" onchange="GetBuying();">
            <option value="0">Semua</option>
            <option value="<?php echo Pembelian::LOOP_BY_FINISH; ?>">Selesai</option>
            <option value="<?php echo Pembelian::LOOP_BY_UNFINISHED; ?>">Belum Selesai</option>
        </select>
        <table class="table-responsive" style="text-align: center;" border="3" id="mt"></table>
    </div>
</div>