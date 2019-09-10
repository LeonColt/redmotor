<?php
require_once standart_path.'core/universal_methode.php';
require_once standart_path.'core/MySQLAccess.php';
require_once standart_path.'dataobject/Pembelian.php';
require_once standart_path.'action/SubmitSelling.php';
require_once standart_path.'action/GetBookings.php';
require_once standart_path.'action/LeasingOperation.php';
require_once standart_path.'action/CloseBooking.php';

$runner = new Runner();
$runner->connect(GlobalConst\DBConst::HOST, GlobalConst\DBConst::PORT, GlobalConst\DBConst::DATABASE, GlobalConst\DBConst::DATABASE_USERNAME, GlobalConst\DBConst::DATABASE_PASSWORD);
$user = GetCurrentUser();

?>
<script>
    function postGetBooking(json_leasing) {
        var type = document.getElementById("type");
        var fd = new FormData();
        fd.append("<?php echo GetBookings::INPUT_LOOP; ?>", (type === null) ? 0 : type.value);

        var http = new XMLHttpRequest();
        http.open("POST", "<?php echo getUrlByRequest(RequestActionConstant::GET_BOOKINGS); ?>", true);
        http.onreadystatechange = function () {
            if(this.readyState === 4 && this.status === 200) {
                var json = JSON.parse(this.responseText);
                if(json.status === 1) {
                    $('#mt tr').remove();
                    var table = document.getElementById("mt");

                    var headers = [
                        "ID",
                        "ID Customer",
                        "ID Mobil",
                        "Metode Pembayaran",
                        "Tanggal Pemesanan",
                        "Komentar",
                        "Harga Deal",
                        "Leasing",
                        "Submit",
                        "Close"
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
                        lbl_id.innerHTML = json.result[i].<?php echo GetBookings::OUTPUT_ID; ?>;
                        td_id.appendChild(lbl_id);
                        tr.appendChild(td_id);

                        var td_date = document.createElement("td");
                        td_date.innerHTML = json.result[i].<?php echo GetBookings::OUTPUT_CUSTOMER; ?>;
                        tr.appendChild(td_date);

                        var td_car_id = document.createElement("td");
                        var car_link = document.createElement("a");
                        car_link.href = "<?php
                                echo getUrlByRequest(RequestPageConstant::PRODUCT);
                                ?>&<?php echo GlobalConst::VAR_CAR; ?>="+json.result[i].<?php echo GetBookings::OUTPUT_CAR; ?>;
                        car_link.target = "_blank";
                        car_link.innerHTML = json.result[i].<?php echo GetBookings::OUTPUT_CAR; ?>;
                        td_car_id.appendChild(car_link);
                        tr.appendChild(td_car_id);

                        var td_car_name = document.createElement("td");
                        td_car_name.innerHTML = ( json.result[i].<?php echo GetBookings::OUTPUT_METHOD; ?> ===  0 ) ? "Tunai" : "Kredit";
                        tr.appendChild(td_car_name);

                        var td_user_id = document.createElement("td");
                        td_user_id.innerHTML = json.result[i].<?php echo GetBookings::OUTPUT_DATE; ?>;
                        tr.appendChild(td_user_id);

                        var td_user_username = document.createElement("td");
                        td_user_username.innerHTML = json.result[i].<?php echo GetBookings::OUTPUT_COMMENT; ?>;
                        tr.appendChild(td_user_username);

                        var td_total_price = document.createElement("td");
                        if(json.result[i].<?php echo GetBookings::OUTPUT_DEAL_PRICE; ?> === null) {
                            var input = document.createElement("input");
                            input.id = "deal_price_"+json.result[i].<?php echo GetBookings::OUTPUT_ID; ?>;
                            input.setAttribute("oninput", "numericPositiveOnly(this.id);");
                            td_total_price.appendChild(input);
                        }
                        else td_total_price.innerHTML = json.result[i].<?php echo GetBookings::OUTPUT_DEAL_PRICE; ?>;
                        tr.appendChild(td_total_price);

                        var td_leasing = document.createElement("td");
                        if( json.result[i].<?php echo GetBookings::OUTPUT_METHOD; ?> !==  0 ) {
                            if(json.result[i].<?php echo GetBookings::OUTPUT_LEASING; ?> === null) {
                                var select = document.createElement("select");
                                select.id = "leasing_"+json.result[i].<?php echo GetBookings::OUTPUT_ID; ?>;
                                for (var j = 0; j < json_leasing.result.length; j++) {
                                    var option = document.createElement("option");
                                    option.value = json_leasing.result[j].<?php echo LeasingOperation::RETURN_ID; ?>;
                                    option.innerHTML = json_leasing.result[j].<?php echo LeasingOperation::RETURN_NAME; ?>;
                                    select.appendChild(option);
                                }
                                td_leasing.appendChild(select);
                            }
                            else td_leasing.innerHTML = json.result[i].<?php echo GetBookings::OUTPUT_DEAL_PRICE; ?>;
                        }
                        else td_leasing.innerHTML = "-";
                        tr.appendChild(td_leasing);

                        var td_submit = document.createElement("td");
                        if(json.result[i].<?php echo GetBookings::OUTPUT_DEAL_PRICE; ?> === null) {
                            var btt = document.createElement("input");
                            btt.type = "button";
                            btt.value = "Submit";
                            var with_leasing = json.result[i].<?php echo GetBookings::OUTPUT_METHOD; ?> !==  0;
                            btt.setAttribute("onclick", "submitBooking("+json.result[i].<?php echo GetBookings::OUTPUT_ID; ?>+", "+with_leasing+")");
                            td_submit.appendChild(btt);
                        }
                        else td_submit.innerHTML = "Submitted";
                        tr.appendChild(td_submit);

                        var td_close = document.createElement("td");
                        if(json.result[i].<?php echo GetBookings::OUTPUT_DEAL_PRICE; ?> === null) {
                            var btt_close = document.createElement("input");
                            btt_close.type = "button";
                            btt_close.value = "Close";
                            btt_close.setAttribute("onclick", "closeBooking("+json.result[i].<?php echo GetBookings::OUTPUT_ID; ?>+")");
                            td_close.appendChild(btt_close);
                        }
                        else td_close.innerHTML = "Submitted";
                        tr.appendChild(td_close);

                        table.appendChild(tr);
                    }
                }
                else alert(json.message);
            }
        };
        http.send(fd);
    }
    function GetBooking() {
        var fd = new FormData();
        fd.append("<?php echo LeasingOperation::INPUT_OPERATION; ?>", "<?php echo LeasingOperation::OPERATION_ARRAY?>" );

        var http = new XMLHttpRequest();
        http.open("POST", "<?php echo getUrlByRequest(RequestActionConstant::LEASING_OPERATION ); ?>", true);
        http.onreadystatechange = function () {
            if(this.readyState === 4 && this.status === 200) {
                var json = JSON.parse(this.responseText);
                if(json.status === 1) postGetBooking(json);
                else alert(json.message);
            }
        };
        http.send(fd);
    }
    window.onload = GetBooking();
    function submitBooking(id, with_leasing) {
        if(document.getElementById("deal_price_"+id).value === 0 || document.getElementById("deal_price_"+id).value === "") {
            alert("Harga Deal tidak boleh kosong");
            return;
        }
        var ok = confirm("Data : \n  ID : "+id+"\n Total Harga : "+document.getElementById("deal_price_"+id).value+"\n Dengan Leasing : "+((with_leasing) ? ("Ya\n Leasing ID : "+document.getElementById("leasing_"+id).value+"\n Nama Leasing : "+document.getElementById("leasing_"+id).options[document.getElementById("leasing_"+id).selectedIndex].text) : "Tidak" )+"\n Apakah Data Sudah Benar?");
        if(!ok) return;
        var fd = new FormData();
        fd.append("<?php echo SubmitSelling::INPUT_BOOK_ID; ?>", id );
        fd.append("<?php echo SubmitSelling::INPUT_TOTAL_PRICE; ?>", document.getElementById("deal_price_"+id).value);
        if(with_leasing)
            fd.append("<?php echo SubmitSelling::INPUT_LEASING_ID; ?>", document.getElementById("leasing_"+id).value);

        var http = new XMLHttpRequest();
        http.open("POST", "<?php echo getUrlByRequest(RequestActionConstant::SUBMIT_SELLING ); ?>", true);
        http.onreadystatechange = function () {
            if(this.readyState === 4 && this.status === 200) {
                var json = JSON.parse(this.responseText);
                if(json.status === 1) {
                    alert("Penjualan Telah Diinput");
                    GetBooking();
                }
                else alert(json.message);
            }
        };
        http.send(fd);
    }
    function closeBooking(id) {
        var ok = confirm("Tutup Pemesanan dengan ID "+id+" ?");
        if(!ok ) return;
        var fd = new FormData();
        fd.append("<?php echo CloseBooking::INPUT_ID; ?>", id);
        var http = new XMLHttpRequest();
        http.open("POST", "<?php echo getUrlByRequest(RequestActionConstant::CLOSE_BOOKING ); ?>", true);
        http.onreadystatechange = function () {
            if(this.readyState === 4 && this.status === 200) {
                var json = JSON.parse(this.responseText);
                if(json.status === 1) {
                    alert("Pemesanan Telah Ditutup");
                    GetBooking();
                }
                else alert(json.message);
            }
        };
        http.send(fd);
    }
</script>
<div id="porfolio" class="parallax-bg1" style="background-position: 50% 0%;" data-stellar-background-ratio="0.0">
    <div class="container">
        <label>Tipe Pembelian</label>
        <select class="form-control margin-bottom-20" id="type" onchange="GetSelling();">
            <option value="0">Semua</option>
            <option value="<?php echo GetBookings::LOOP_FINISH; ?>">Selesai</option>
            <option value="<?php echo GetBookings::LOOP_UNFINISHED; ?>">Belum Selesai</option>
        </select>
        <table class="table-responsive" style="text-align: center; color: #a58f1e;" border="3" id="mt"></table>
    </div>
</div>