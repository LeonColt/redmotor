<?php
require_once standart_path.'core/RequestActionConstant.php';
require_once standart_path.'action/PreRegistration.php';
$user = GetCurrentUser();
require_once standart_path.'dataobject/Mobil.php';
require_once standart_path.'dataobject/SimulasiKredit.php';
require_once standart_path.'action/SubmitPesan.php';
$runner = new Runner();
$runner->connect(GlobalConst\DBConst::HOST, GlobalConst\DBConst::PORT, GlobalConst\DBConst::DATABASE, GlobalConst\DBConst::DATABASE_USERNAME, GlobalConst\DBConst::DATABASE_PASSWORD);

$sk = new SimulasiKredit();
$sk->setRunner($runner);
$sk->setTanggal(date("Y-m-d"));
$sk->load();

$car = new Mobil(filter_input(INPUT_GET, GlobalConst::VAR_CAR));
$car->setRunner($runner);
$car->appendLoadBy(Mobil::LOAD_BY_ID);
$car->load();
?>
<script>
    function check(){
        var http = new XMLHttpRequest();
        var fd = new FormData();
        fd.append("<?php echo SubmitPesan::INPUT_ID; ?>", document.getElementById("id_pemesanan").value);
        fd.append("<?php echo SubmitPesan::INPUT_CAR; ?>", document.getElementById("id_mobil").value);
        fd.append("<?php echo SubmitPesan::INPUT_METHOD; ?>", document.getElementById("payment_method").value);
        fd.append("<?php echo SubmitPesan::INPUT_COMMENT; ?>", document.getElementById("comment").value);
        http.open("POST", "<?php echo getUrlByRequest(RequestActionConstant::SUBMIT_PESAN); ?>", true);
        http.onreadystatechange = function() {
            alert(this.responseText);
            var json = JSON.parse(http.responseText);
            if(json.status === 1){
                alert("Email konfirmasi telah dikirim, silahkan konfirmasi pendaftaran terlebih dahulu sebelum masuk");
            }
            else alert(json.message);
        };
        http.send(fd);
        return false;
    }
    function SimulasiKredit() {
        var container = document.getElementById("sk");
        if(document.getElementById("payment_method").value === "0" ) {
            container.innerHTML = "";
            return;
        }
        var bunga = <?php echo $sk->getBungaPerTahun(); ?>/100;
        var administrasi = <?php echo $sk->getAdministrasi(); ?>;
        var harga = <?php echo $car->getPrice(); ?>;
        var dp = 0.3;

        //begin informasi
        var tbl_informasi = document.createElement("table");
        tbl_informasi.id = "informasi_table";

        var thead_info = document.createElement("thead");
        thead_info.innerHTML = "Informasi Pinjaman Anda";
        tbl_informasi.appendChild(thead_info);

        var tr_harga = document.createElement("tr");
        var th_harga_var = document.createElement("th");
        th_harga_var.innerHTML = "Harga";
        var th_harga_val = document.createElement("th");
        th_harga_val.innerHTML = "RP "+putDotEvery3Digits(harga.toFixed(2));
        tr_harga.appendChild(th_harga_var);
        tr_harga.appendChild(th_harga_val);
        tbl_informasi.append(tr_harga);

        var tr_dp = document.createElement("tr");
        var th_dp_var = document.createElement("th");
        th_dp_var.innerHTML = "Uang Muka (DP)";
        var th_dp_val = document.createElement("th");
        th_dp_val.innerHTML = "Rp "+putDotEvery3Digits((harga * dp).toFixed(2))+"( "+(dp*100)+"%)";
        tr_dp.appendChild(th_dp_var);
        tr_dp.appendChild(th_dp_val);
        tbl_informasi.appendChild(tr_dp);

        var tr_interest = document.createElement("tr");
        var th_interest_var = document.createElement("th");
        th_interest_var.innerHTML = "Bunga Pinjaman ";
        var th_interest_val = document.createElement("th");
        th_interest_val.innerHTML = (bunga*100)+"%("+((bunga*100)/12).toFixed(2)+"% per Bulan)";
        tr_interest.appendChild(th_interest_var);
        tr_interest.appendChild(th_interest_val);
        tbl_informasi.append(tr_interest);

        var tr_admin_cost = document.createElement("tr");
        var th_admin_cost_var = document.createElement("th");
        th_admin_cost_var.innerHTML = "Biaya Administrasi";
        var th_admin_cost_val = document.createElement("th");
        th_admin_cost_val.innerHTML = "Rp "+putDotEvery3Digits(administrasi.toFixed(2));
        tr_admin_cost.appendChild(th_admin_cost_var);
        tr_admin_cost.appendChild(th_admin_cost_val);
        tbl_informasi.append(tr_admin_cost);

        var tr_angsuran = document.createElement("tr");
        var th_angsuran_var = document.createElement("th");
        th_angsuran_var.innerHTML = "Lama Angsuran";
        var th_angsuran_val = document.createElement("th");
        var input_angsuran = document.createElement("input");
        input_angsuran.type = "number";
        input_angsuran.min = "1";
        input_angsuran.id = "lama_angsuran";
        input_angsuran.setAttribute("oninput", "extendSimulasiKredit("+harga+", "+dp+", "+bunga+", "+administrasi+");");
        var year_teller = document.createElement("label");
        year_teller.id = "year_teller";
        year_teller.innerHTML = " Tahun";
        th_angsuran_val.appendChild(input_angsuran);
        th_angsuran_val.appendChild(year_teller);
        tr_angsuran.appendChild(th_angsuran_var);
        tr_angsuran.appendChild(th_angsuran_val);
        tbl_informasi.append(tr_angsuran);
        //end informasi

        //begin plafon
        var tbl_plafon = document.createElement("table");

        var thead_plafon = document.createElement("thead");
        thead_plafon.innerHTML = "Plafon Pinjaman Anda";
        tbl_plafon.appendChild(thead_plafon);

        var tr_plafon_harga = document.createElement("tr");
        var th_plafon_harga_var = document.createElement("th");
        th_plafon_harga_var.innerHTML = "Harga Mobil";
        var th_plafon_harga_val = document.createElement("th");
        th_plafon_harga_val.style.textAlign = "right";
        th_plafon_harga_val.innerHTML = "Rp "+putDotEvery3Digits(harga.toFixed(2));
        tr_plafon_harga.appendChild(th_plafon_harga_var);
        tr_plafon_harga.appendChild(th_plafon_harga_val);
        tbl_plafon.appendChild(tr_plafon_harga);

        var tr_plafon_dp = document.createElement("tr");
        var th_plafon_dp_var = document.createElement("th");
        th_plafon_dp_var.innerHTML = "Uang Muka(DP)";
        var th_plafon_dp_val = document.createElement("th");
        th_plafon_dp_val.style.textAlign = "right";
        th_plafon_dp_val.innerHTML = "Rp "+putDotEvery3Digits((harga * dp).toFixed(2));
        tr_plafon_dp.appendChild(th_plafon_dp_var);
        tr_plafon_dp.appendChild(th_plafon_dp_val);
        tbl_plafon.appendChild(tr_plafon_dp);

        var tr_sub = document.createElement("tr");
        var tr_sub_var = document.createElement("th");
        tr_sub_var.innerHTML = "&nbsp;";
        var tr_sub_val = document.createElement("th");
        tr_sub_val.innerHTML = "_____________________-";
        tr_sub.appendChild(tr_sub_var);
        tr_sub.appendChild(tr_sub_val);
        tbl_plafon.appendChild(tr_sub);

        var tr_pinjaman = document.createElement("tr");
        var th_pinjaman_var = document.createElement("th");
        th_pinjaman_var.innerHTML = "Plafon Pinjaman";
        var th_pinjaman_val = document.createElement("th");
        th_pinjaman_val.style.textAlign = "right";
        th_pinjaman_val.innerHTML = "Rp "+putDotEvery3Digits((harga - (harga * dp)).toFixed(2));
        tr_pinjaman.appendChild(th_pinjaman_var);
        tr_pinjaman.appendChild(th_pinjaman_val);
        tbl_plafon.appendChild(tr_pinjaman);

        //begin other payment
        var tbl_other = document.createElement("table");

        var thead_other = document.createElement("thead");
        thead_other.innerHTML = "Biaya Lain";
        tbl_other.appendChild(thead_other);

        var tr_admin = document.createElement("tr");
        var th_admin_var = document.createElement("th");
        th_admin_var.innerHTML = "Administrasi";
        var th_admin_val = document.createElement("th");
        th_admin_val.style.textAlign = "right";
        th_admin_val.innerHTML = "Rp "+putDotEvery3Digits(administrasi.toFixed(2));
        tr_admin.appendChild(th_admin_var);
        tr_admin.appendChild(th_admin_val);
        tbl_other.appendChild(tr_admin);

        //begin biaya angsuran
        var tbl_angsuran = document.createElement("table");

        var thead_angsuran = document.createElement("thead");
        thead_angsuran.innerHTML = "Angsuran per Bulan";
        tbl_angsuran.appendChild(thead_angsuran);

        var tr_angsuran_pokok = document.createElement("tr");
        var th_angsuran_pokok_var = document.createElement("th");
        th_angsuran_pokok_var.innerHTML = "Angsuran Pokok Per Bulan";
        var th_angsuran_pokok_val = document.createElement("th");
        th_angsuran_pokok_val.id = "th_angsuran_pokok_val";
        th_angsuran_pokok_val.style.textAlign = "right";
        th_angsuran_pokok_val.innerHTML = "Rp -";
        tr_angsuran_pokok.appendChild(th_angsuran_pokok_var);
        tr_angsuran_pokok.appendChild(th_angsuran_pokok_val);
        tbl_angsuran.appendChild(tr_angsuran_pokok);

        var tr_angsuran_bunga = document.createElement("tr");
        var th_angsuran_bunga_var = document.createElement("th");
        th_angsuran_bunga_var.innerHTML = "Angsuran Bunga Per Bulan";
        var th_angsuran_bunga_val = document.createElement("th");
        th_angsuran_bunga_val.id = "th_angsuran_bunga_val";
        th_angsuran_bunga_val.style.textAlign = "right";
        th_angsuran_bunga_val.innerHTML = "Rp -";
        tr_angsuran_bunga.appendChild(th_angsuran_bunga_var);
        tr_angsuran_bunga.appendChild(th_angsuran_bunga_val);
        tbl_angsuran.appendChild(tr_angsuran_bunga);

        var tr_sub_angsuran = document.createElement("tr");
        var tr_sub_angsuran_var = document.createElement("th");
        tr_sub_angsuran_var.innerHTML = "&nbsp;";
        var tr_sub_angsuran_val = document.createElement("th");
        tr_sub_angsuran_val.style.textAlign = "right";
        tr_sub_angsuran_val.innerHTML = "_____________________+";
        tr_sub_angsuran.appendChild(tr_sub_angsuran_var);
        tr_sub_angsuran.appendChild(tr_sub_angsuran_val);
        tbl_angsuran.appendChild(tr_sub_angsuran);

        var tr_total_angsuran = document.createElement("tr");
        var th_total_angsuran_var = document.createElement("th");
        th_total_angsuran_var.innerHTML = "Total Angsuran per Bulan";
        var th_total_angsuran_val = document.createElement("th");
        th_total_angsuran_val.id = "th_total_angsuran_val";
        th_total_angsuran_val.style.textAlign = "right";
        th_total_angsuran_val.innerHTML = "Rp -";
        tr_total_angsuran.appendChild(th_total_angsuran_var);
        tr_total_angsuran.appendChild(th_total_angsuran_val);
        tbl_angsuran.appendChild(tr_total_angsuran);

        //begin first payment
        var tbl_first = document.createElement("tbl_first");

        var thead_first = document.createElement("thead");
        thead_first.innerHTML = "Pembayaran Pertama Kali"
        tbl_first.appendChild(thead_first);

        var tr_first_dp = document.createElement("tr");
        var th_first_dp_var = document.createElement("th");
        th_first_dp_var.innerHTML = "Uang Muka (DP)";
        var th_first_dp_val = document.createElement("th");
        th_first_dp_val.id = "first_dp";
        th_first_dp_val.style.textAlign = "right";
        th_first_dp_val.innerHTML = "Rp "+putDotEvery3Digits((harga * dp).toFixed(2));
        tr_first_dp.appendChild(th_first_dp_var);
        tr_first_dp.appendChild(th_first_dp_val);
        tbl_first.appendChild(tr_first_dp);

        var tr_first_angsuran = document.createElement("tr");
        var th_first_angsuran_var = document.createElement("th");
        th_first_angsuran_var.innerHTML = "Angsuran Pertama";
        var th_first_angsuran_val = document.createElement("th");
        th_first_angsuran_val.id = "first_angsuran";
        th_first_angsuran_val.style.textAlign = "right";
        th_first_angsuran_val.innerHTML = "Rp -";
        tr_first_angsuran.appendChild(th_first_angsuran_var);
        tr_first_angsuran.appendChild(th_first_angsuran_val);
        tbl_first.appendChild(tr_first_angsuran);

        var tr_first_admin = document.createElement("tr");
        var th_first_admin_var = document.createElement("th");
        th_first_admin_var.innerHTML = "Administrasi";
        var th_first_admin_val = document.createElement("th");
        th_first_admin_val.id = "first_admin";
        th_first_admin_val.style.textAlign = "right";
        th_first_admin_val.innerHTML = "Rp "+putDotEvery3Digits(administrasi.toFixed(2));
        tr_first_admin.appendChild(th_first_admin_var);
        tr_first_admin.appendChild(th_first_admin_val);
        tbl_first.appendChild(tr_first_admin);

        var tr_first_add = document.createElement("tr");
        var tr_first_add_var = document.createElement("th");
        tr_first_add_var.innerHTML = "&nbsp;";
        var tr_first_add_val = document.createElement("th");
        tr_first_add_val.style.textAlign = "right";
        tr_first_add_val.innerHTML = "_____________________+";
        tr_first_add.appendChild(tr_first_add_var);
        tr_first_add.appendChild(tr_first_add_val);
        tbl_first.appendChild(tr_first_add);

        var tr_first_total = document.createElement("tr");
        var th_first_total_var = document.createElement("th");
        th_first_total_var.innerHTML = "Total Pembayaran Pertama";
        var th_first_total_val = document.createElement("th");
        th_first_total_val.id = "first_total";
        th_first_total_val.style.textAlign = "right";
        th_first_total_val.innerHTML = "Rp -";
        tr_first_total.appendChild(th_first_total_var);
        tr_first_total.appendChild(th_first_total_val);
        tbl_first.appendChild(tr_first_total);



        //begin table angsuran
        var tbl_tbl_angsuran = document.createElement("table");
        tbl_tbl_angsuran.id = "tbl_angsuran";
        tbl_tbl_angsuran.border = "1";

        var thead_tbl_angsuran  = document.createElement("thead");
        thead_tbl_angsuran.innerHTML = "Tabel Angsuran";
        tbl_tbl_angsuran.appendChild(thead_tbl_angsuran);

        var header = document.createElement("tr");

        var header_bulan = document.createElement("th");
        header_bulan.innerHTML = "Bulan";
        header_bulan.style.textAlign = "center";
        header.appendChild(header_bulan);

        var header_angsuran_bunga = document.createElement("th");
        header_angsuran_bunga.innerHTML = "Angsuran Bunga";
        header_angsuran_bunga.style.textAlign = "center";
        header.appendChild(header_angsuran_bunga);

        var header_angsuran_pokok = document.createElement("th");
        header_angsuran_pokok.innerHTML = "Angsuran Pokok";
        header_angsuran_pokok.style.textAlign = "center";
        header.appendChild(header_angsuran_pokok);

        var header_total = document.createElement("th");
        header_total.innerHTML = "Total Angsuran";
        header_total.style.textAlign = "center";
        header.appendChild(header_total);

        var header_sisa = document.createElement("th");
        header_sisa.innerHTML = "Sisa Pinjaman";
        header_sisa.style.textAlign = "center";
        header.appendChild(header_sisa);

        tbl_tbl_angsuran.appendChild(header);

        //begin draw tables
        container.appendChild(tbl_informasi);

        container.appendChild(document.createElement("br"));
        container.appendChild(document.createElement("br"));

        container.appendChild(tbl_plafon);

        container.appendChild(document.createElement("br"));
        container.appendChild(document.createElement("br"));

        container.appendChild(tbl_other);

        container.appendChild(document.createElement("br"));
        container.appendChild(document.createElement("br"));

        container.appendChild(tbl_angsuran);

        container.appendChild(document.createElement("br"));
        container.appendChild(document.createElement("br"));

        container.appendChild(tbl_first);

        container.appendChild(document.createElement("br"));
        container.appendChild(document.createElement("br"));

        container.appendChild(tbl_tbl_angsuran);
    }
    function extendSimulasiKredit(harga, dp, bunga, administrasi) {
        var plafon = harga - (harga * dp);
        var lama_angsuran = document.getElementById("lama_angsuran").value;
        var lama_angsuran_bulan = lama_angsuran*12;
        document.getElementById("year_teller").innerHTML = " Tahun("+lama_angsuran_bulan+" Bulan)";
        var angsuran_pokok = plafon/lama_angsuran_bulan;
        var angsuran_bunga  = plafon * (bunga * lama_angsuran) / lama_angsuran_bulan;
        document.getElementById("th_angsuran_pokok_val").innerHTML = "Rp "+ putDotEvery3Digits(angsuran_pokok.toFixed(2));
        document.getElementById("th_angsuran_bunga_val").innerHTML = "Rp " + putDotEvery3Digits(angsuran_bunga.toFixed(2));
        document.getElementById("th_total_angsuran_val").innerHTML = "Rp "+ putDotEvery3Digits(((plafon/lama_angsuran_bulan)+(plafon * (bunga * lama_angsuran) / lama_angsuran_bulan)).toFixed(2));
        document.getElementById("first_angsuran").innerHTML = "Rp "+ putDotEvery3Digits(((plafon/lama_angsuran_bulan)+(plafon * (bunga * lama_angsuran) / lama_angsuran_bulan)).toFixed(2));
        document.getElementById("first_total").innerHTML = "Rp "+ putDotEvery3Digits(((harga * dp)+(plafon/lama_angsuran_bulan)+(plafon * (bunga * lama_angsuran) / lama_angsuran_bulan)+administrasi).toFixed(2));

        var tbl_angsuran = document.getElementById("tbl_angsuran");
        while (tbl_angsuran.rows.length > 1) tbl_angsuran.deleteRow(tbl_angsuran.rows.length - 1);

        var sisa = plafon;

        var tr = document.createElement("tr");
        var td_bulan = document.createElement("td");
        td_bulan.innerHTML = "0";
        tr.appendChild(td_bulan);
        var td_bunga = document.createElement("td");
        td_bunga.innerHTML = "Rp 0";
        tr.appendChild(td_bunga);
        var td_pokok = document.createElement("td");
        td_pokok.innerHTML = "Rp 0";
        tr.appendChild(td_pokok);
        var td_total = document.createElement("td");
        td_total.innerHTML = "Rp 0";
        tr.appendChild(td_total);
        var td_sisa = document.createElement("td");
        td_sisa.innerHTML = "Rp "+putDotEvery3Digits(sisa);
        tr.appendChild(td_sisa);
        tbl_angsuran.appendChild(tr);

        for( var angsuran_ke = 0; angsuran_ke < lama_angsuran_bulan; angsuran_ke++) {
            tr = document.createElement("tr");
            td_bulan = document.createElement("td");
            td_bulan.innerHTML = angsuran_ke + 1;
            tr.appendChild(td_bulan);
            td_bunga = document.createElement("td");
            td_bunga.innerHTML = "Rp "+putDotEvery3Digits(angsuran_bunga.toFixed(2));
            tr.appendChild(td_bunga);
            td_pokok = document.createElement("td");
            td_pokok.innerHTML = "Rp "+putDotEvery3Digits(angsuran_pokok.toFixed(2));
            tr.appendChild(td_pokok);
            td_total = document.createElement("td");
            td_total.innerHTML = "Rp "+putDotEvery3Digits((angsuran_bunga + angsuran_pokok).toFixed(2));
            tr.appendChild(td_total);
            td_sisa = document.createElement("td");
            sisa -= angsuran_pokok;
            td_sisa.innerHTML = "Rp "+putDotEvery3Digits(sisa.toFixed(2));
            tr.appendChild(td_sisa);
            tbl_angsuran.appendChild(tr);
        }
        tr = document.createElement("tr");
        td_bulan = document.createElement("td");
        td_bulan.innerHTML = "Total";
        tr.appendChild(td_bulan);
        td_bunga = document.createElement("td");
        td_bunga.innerHTML = "Rp "+putDotEvery3Digits((angsuran_bunga * lama_angsuran_bulan).toFixed(2));
        tr.appendChild(td_bunga);
        td_pokok = document.createElement("td");
        td_pokok.innerHTML = "Rp "+putDotEvery3Digits((angsuran_pokok * lama_angsuran_bulan).toFixed(2));
        tr.appendChild(td_pokok);
        td_total = document.createElement("td");
        td_total.innerHTML = "Rp "+putDotEvery3Digits(((angsuran_bunga + angsuran_pokok) * lama_angsuran_bulan).toFixed(2));
        tr.appendChild(td_total);
        td_sisa = document.createElement("td");
        td_sisa.innerHTML = "";
        tr.appendChild(td_sisa);
        tbl_angsuran.appendChild(tr);
    }
    function putDotEvery3Digits(input) {
        input = input + "";
        var res = "";
        var counter = 0;
        for (var i = input.length - 1; i > -1; i--) {
            res = input[i] + res;
            if(counter === 2 && i !== 0) {
                res = ","+res;
                counter = 0;
            }
            else if(i < input.length - 3) counter++;
        }
        return res;
    }
</script>
<!-- === BEGIN CONTENT === -->
<div id="content">
    <div class="container background-white">
        <div class="row margin-vert-30">
            <!-- Register Box -->
            <div class="col-md-6 col-md-offset-3 col-sm-offset-3">
                <form class="signup-page margin-top-20" onsubmit="return check()">
                    <div class="signup-header">
                        <h2 class="margin-bottom-20">Pemesanan Mobil</h2>
                    </div>
                    <label>Nomor Pemesanan</label>
                    <input class="form-control margin-bottom-20" type="text" id="id_pemesanan" disabled>

                    <label>Id Customer</label>
                    <input class="form-control margin-bottom-20" type="text" id="id_customer" value="<?php echo $user->getId(); ?>" disabled>

                    <label>Username Customer</label>
                    <input class="form-control margin-bottom-20" type="text" id="username_customer" value="<?php echo $user->getUsername(); ?>" disabled>

                    <label>ID Mobil</label>
                    <input class="form-control margin-bottom-20" type="text" id="id_mobil" value="<?php echo $car->getId(); ?>" disabled>

                    <label>Nama Mobil/Nomor Polisi</label>
                    <input class="form-control margin-bottom-20" type="text" value="<?php echo $car->getName(); ?>" disabled>

                    <img style="width: 100%; height: 100%" class="img-responsive" src="data:image/<?php echo $car->getPic()->getType(); ?>;base64,<?php echo $car->getPic()->getImage(); ?>">

                    <label>Komentar</label>
                    <textarea class="form-control margin-bottom-60" id="comment"></textarea>

                    <label>Harga</label>
                    <input class="form-control margin-bottom-20" value="Rp <?php echo $car->getPrice(); ?>" disabled>

                    <label>Metode Pembayaran</label>
                    <select class="form-control margin-bottom-20" id="payment_method" onchange="SimulasiKredit();" >
                        <option value="0">Tunai</option>
                        <option value="1">Kredit</option>
                    </select>

                    <div id="sk"></div>

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
    window.onload = SimulasiKredit();
    window.onload = function () {
        var http = new XMLHttpRequest();
        http.open("POST", "<?php echo getUrlByRequest(RequestActionConstant::GET_BOOK_ID); ?>", true);
        http.onreadystatechange = function () {
            if(this.readyState === 4 && this.status === 200) {
                var json = JSON.parse(http.responseText);
                if(json.status === 1) document.getElementById("id_pemesanan").value = json.result;
            }
        };
        http.send();
    };
</script>