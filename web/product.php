<?php
/**
 * Created by PhpStorm.
 * User: LC
 * Date: 20/04/2017
 * Time: 08:40
 */
$user = GetCurrentUser();
?>
<div id="porfolio" class="parallax-bg2" style="background-position: 50% 0%;" data-stellar-background-ratio="0.5">
    <div class="container">
        <div class="row margin-top-40">
        <?php
        require_once standart_path.'dataobject/Mobil.php';
        $runner = new Runner();
        $runner->connect(GlobalConst\DBConst::HOST, GlobalConst\DBConst::PORT, GlobalConst\DBConst::DATABASE, GlobalConst\DBConst::DATABASE_USERNAME, GlobalConst\DBConst::DATABASE_PASSWORD);

        $id = filter_input(INPUT_GET, GlobalConst::VAR_CAR, FILTER_VALIDATE_INT);
        if( $id === false) {
            ?>
            <table class="table-responsive">
                <tr>
                    <td style="text-align: center">
                        <label style=" background-color: #2b542c; vertical-align: middle; color: snow; display: inline-block; font-size: 200%">
                            Mobil Tidak Valid
                        </label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <img class="img-circle" style="height: 100%; width: 100%;" src="<?php echo load_image_url("car_silhoutte.png"); ?>" >
                    </td>
                </tr>
            </table>
            <?php
        }
        else {
            try {
                $car = new Mobil(filter_input(INPUT_GET, GlobalConst::VAR_CAR));
                $car->appendLinkJoin(DataObjectConstant::TABLE_CAR_KIND);
                $car->appendLinkJoin(DataObjectConstant::TABLE_CAR_TYPE);
                $car->appendLinkJoin(DataObjectConstant::TABLE_CAR_BRAND);
                $car->appendLinkJoin(DataObjectConstant::TABLE_CAR_TRANSMISSION);
                $car->setRunner($runner);
                $car->appendLoadBy(Mobil::LOAD_BY_ID);
                $car->load();

                //show car
                ?>
                <table class="table-responsive parallax-bg1" style="width: 100%">
                    <tr>
                        <td>
                            <img style="width: 100%; height: 100%" class="img-responsive" src="data:image/<?php echo $car->getPic()->getType()?>;base64,<?php echo $car->getPic()->getImage(); ?>">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table style="color: black;">
                                <tr>
                                    <th>Nama</th>
                                    <th>&nbsp;:&nbsp;</th>
                                    <th><?php echo $car->getName(); ?></th>
                                </tr>
                                <tr>
                                    <th>Jenis</th>
                                    <th>&nbsp;:&nbsp;</th>
                                    <th><?php echo $car->getJenis()->getJenis(); ?></th>
                                </tr>
                                <tr>
                                    <th>Tipe</th>
                                    <th>&nbsp;:&nbsp;</th>
                                    <th><?php echo $car->getJenis()->getTipe()->getType(); ?></th>
                                </tr>
                                <tr>
                                    <th>Merek</th>
                                    <th>&nbsp;:&nbsp;</th>
                                    <th><?php echo $car->getJenis()->getMerek()->getMerek(); ?></th>
                                </tr>
                                <tr>
                                    <th>Tahun</th>
                                    <th>&nbsp;:&nbsp;</th>
                                    <th><?php echo $car->getYear(); ?></th>
                                </tr>
                                <tr>
                                    <th>Odometer</th>
                                    <th>&nbsp;:&nbsp;</th>
                                    <th><?php echo number_format($car->getOdometer(), 0, ',', '.'); ?></th>
                                </tr>
                                <tr>
                                    <th>Harga</th>
                                    <th>&nbsp;:&nbsp;</th>
                                    <th>Rp <?php echo number_format($car->getPrice(), 0, ',', '.'); ?></th>
                                </tr>
                                <tr>
                                    <th>Warna</th>
                                    <th>&nbsp;:&nbsp;</th>
                                    <th><?php echo $car->getColor(); ?></th>
                                </tr>
                                <tr>
                                    <th>
                                        <a href="
                                        <?php
                                        if($user === null){
                                            $url = getUrlByRequest(RequestPageConstant::LOGIN);
                                            $url = addGetToUrl($url, GlobalConst::VAR_URL_REDIRECT, urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']));
                                        }
                                        else {
                                            $url = getUrlByRequest(RequestPageConstant::BOOK);
                                            $url = addGetToUrl($url, GlobalConst::VAR_CAR, $id);
                                        }
                                        echo $url;
                                        ?>">
                                            Pesan
                                        </a>
                                    </th>
                                </tr>
                                <?php
                                if($user !== null) {
                                    if($user->isAdmin() || $user->isSuperAdmin()) {
                                        echo "<tr><th>";
                                        $url = getUrlByRequest(RequestPageConstant::EDIT_CAR);
                                        $url = addGetToUrl($url, GlobalConst::VAR_CAR, $id);
                                        echo '<a href="'.$url.'">Edit';
                                        echo '</a></th></tr>';

                                        echo "<tr><th>";
                                        $url = getUrlByRequest(RequestPageConstant::SET_CAR_VALUE);
                                        $url = addGetToUrl($url, GlobalConst::VAR_CAR, $id);
                                        echo '<a href="'.$url.'">Update Nilai Mobil';
                                        echo '</a></th></tr>';
                                    }
                                }
                                ?>
                            </table>
                        </td>
                    </tr>
                </table>
                <?php
            } catch (Exception $ex) {
                ?>
                <table class="table-responsive">
                    <tr>
                        <td style="text-align: center">
                            <label style=" background-color: #2b542c; vertical-align: middle; color: snow; display: inline-block; font-size: 200%">
                                <?php
                                echo $ex->getMessage();
                                ?>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <img class="img-circle" style="height: 100%; width: 100%;" src="<?php echo load_image_url("car_silhoutte.png"); ?>" >
                        </td>
                    </tr>
                </table>
                <?php
            }
        }
        ?>
        </div>
    </div>
</div>