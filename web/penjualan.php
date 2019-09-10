<?php
require_once standart_path.'core/universal_methode.php';
require_once standart_path.'core/MySQLAccess.php';
require_once standart_path.'dataobject/Pembelian.php';
require_once standart_path.'action/GetBuying.php';

$runner = new Runner();
$runner->connect(GlobalConst\DBConst::HOST, GlobalConst\DBConst::PORT, GlobalConst\DBConst::DATABASE, GlobalConst\DBConst::DATABASE_USERNAME, GlobalConst\DBConst::DATABASE_PASSWORD);
$user = GetCurrentUser();

?>
<div id="porfolio" class="parallax-bg1" style="background-position: 50% 0%;" data-stellar-background-ratio="0.5">
    <div class="container">
        <label>Tipe Penjualan</label>
        <table class="table-responsive" style="text-align: center; color: #a58f1e;" border="3" id="mt">
            <tr>
                <th>ID</th>
                <th>Pemesanan</th>
                <th>Tanggal</th>
                <th>Total Harga</th>
                <th>Leasing</th>
            </tr>
            <?php
            $penjualan = new Penjualan();
            $penjualan->setRunner($runner);
            foreach ($penjualan as $item) {
                echo "<tr>";
                echo '<td>'.$item->getId().'</td>';
                echo '<td><a href="'.getUrlByRequest(RequestPageConstant::BOOKING).'" target="_blank">'.$item->getBooking()->getId().'</a></td>';
                echo '<td>'.$item->getDate().'</td>';
                echo '<td>'.$item->getTotalPrice().'</td>';
                echo '<td>';
                if(($item->getLeasing() !== null))
                    echo '<a href="#">'.$item->getLeasing()->getId().'</a>';
                else echo "-";
                echo '</td>';
                echo "</tr>";
            }
            ?>
        </table>
    </div>
</div>