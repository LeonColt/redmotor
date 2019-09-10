<?php
/**
 * Created by PhpStorm.
 * User: LC
 * Date: 12/04/2017
 * Time: 14:06
 */
require_once standart_path.'core/Session/Session.php';
require_once standart_path.'core/RequestActionConstant.php';
$user = GetCurrentUser();
?>
<div id="header" style="background-position: 50% 0%; height:70%;" data-stellar-background-ratio="0.5">
    <div class="container">
        <div id="carousel-example-1" class="carousel slide" data-ride="carousel">
            <!-- Indicators -->
            <ol class="carousel-indicators">
                <li data-target="#carousel-example-1" data-slide-to="0" class="active"></li>
                <li data-target="#carousel-example-1" data-slide-to="1"></li>
                <li data-target="#carousel-example-1" data-slide-to="2"></li>
            </ol>
            <!-- Wrapper for slides -->
            <div class="carousel-inner center-block">
                <div class="item active"><img src="<?php echo load_image_url("a.jpg"); ?>"></div>
                <div class="item"><img src="<?php echo load_image_url("b.jpg"); ?>"></div>
                <div class="item"><img src="<?php echo load_image_url("c.jpg"); ?>"></div>
            </div>
            <!-- Controls -->
            <a class="left carousel-control" href="#carousel-example-1" data-slide="prev">
                <span class="glyphicon glyphicon-chevron-left"></span>
            </a>
            <a class="right carousel-control" href="#carousel-example-1" data-slide="next">
                <span class="glyphicon glyphicon-chevron-right"></span>
            </a>
        </div>
        <!-- Top Menu -->
        <div id="hornav" class="row text-light">
            <div class="col-md-12">
                <div class="text-center visible-lg">
                    <ul id="hornavmenu" class="nav navbar-nav">
                        <li>
                            <a <?php echo (getCurrentRequest() == "" || isCurrentRequestEquals(RequestPageConstant::HOME)) ? "" : ('href="'.getUrlByRequest().'"'); ?> class="fa-home <?php
                            echo (getCurrentRequest() == "" || isCurrentRequestEquals(RequestPageConstant::HOME)) ? "active" : "";
                            ?>">Beranda</a>
                        </li>
                        <li>
                            <a href="<?php if($user !== null)
                                echo getUrlByRequest(RequestPageConstant::BUY_AHP);
                            else {
                                $url = getUrlByRequest(RequestPageConstant::LOGIN);
                                $url = addGetToUrl($url, GlobalConst::VAR_URL_REDIRECT, getUrlByRequest(RequestPageConstant::BUY_AHP));
                                echo $url;
                            }
                                ?>" class="fa-cloud-download">Beli</a>
                        </li>
                        <?php
                        if($user !== null) {
                            if($user->isSuperAdmin() || $user->isAdmin()) {
                                echo  "<li>";
                                echo  '<a href="'.getUrlByRequest(RequestPageConstant::BUYING).'" class="fa-cloud-download">Pembelian</a>';
                                echo  "</li>";
                            }
                        }

                        if($user !== null) {
                            if($user->isSuperAdmin() || $user->isAdmin()) {
                                echo  "<li>";
                                echo  '<a href="'.getUrlByRequest(RequestPageConstant::BOOKING).'" class="fa-cloud-download">Pemesanan</a>';
                                echo  "</li>";
                            }
                        }
                        echo '<li>';
                        if($user === null){
                            $url = getUrlByRequest(RequestPageConstant::LOGIN);
                            $url = addGetToUrl($url, GlobalConst::VAR_URL_REDIRECT, urlencode(getUrlByRequest(RequestPageConstant::SELL)));
                        }
                        else $url = getUrlByRequest(RequestPageConstant::SELL);
                        echo '<a href="'.$url.'" class="fa-cloud-upload">Jual</a>';
                        echo '</li>';

                        if($user !== null) {
                            if($user->isSuperAdmin() || $user->isAdmin()) {
                                echo  "<li>";
                                echo  '<a href="'.getUrlByRequest(RequestPageConstant::SELLING).'" class="fa-cloud-upload">Penjualan</a>';
                                echo  "</li>";

                                echo  "<li>";
                                echo  '<a href="'.getUrlByRequest(RequestPageConstant::LEASING).'" class="fa-adn">Leasing</a>';
                                echo  "</li>";

                                echo  "<li>";
                                echo  '<a href="'.getUrlByRequest(RequestPageConstant::USER).'" class="fa-users">Pengguna</a>';
                                echo  "</li>";
                            }
                            else {
                                echo  "<li>";
                                echo  '<a href="'.getUrlByRequest(RequestPageConstant::USER).'" class="fa-users">'.$user->getUsername().'</a>';
                                echo  "</li>";
                            }
                        }
                        ?>
                        <li>
                            <a <?php echo (isCurrentRequestEquals(RequestPageConstant::INFORMATION)) ? "" : ('href="'.getUrlByRequest(RequestPageConstant::INFORMATION).'"'); ?> class="fa-user <?php
                            echo (isCurrentRequestEquals(RequestPageConstant::HOME)) ? "active" : "";
                            ?>">Informasi</a>
                        </li>
                        <li>
                            <a <?php echo (isCurrentRequestEquals(RequestPageConstant::ABOUT)) ? "" : ('href="'.getUrlByRequest(RequestPageConstant::ABOUT).'"'); ?> class="fa-user <?php
                            echo (isCurrentRequestEquals(RequestPageConstant::HOME)) ? "active" : "";
                            ?>">Tentang</a>
                        </li>
                        <li>
                            <?php
                            if($user !== null) {
                                echo '<a href="'.getUrlByRequest(RequestActionConstant::LOGOUT).'" class="fa-sign-out">';
                                echo "Keluar</a>";
                            }
                            else {
                                echo '<a '.(((isCurrentRequestEquals(RequestPageConstant::LOGIN)) ? '' : ('href="'.getUrlByRequest(RequestPageConstant::LOGIN).'"')) ).' class="fa-sign-in '.((isCurrentRequestEquals(RequestPageConstant::LOGIN)) ? "active" : "").'">';
                                echo "Masuk</a>";
                            }
                            ?>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- End Top Menu -->
    </div>
</div>
