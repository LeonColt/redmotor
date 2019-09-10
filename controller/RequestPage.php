<?php

/**
 * Created by PhpStorm.
 * User: LC
 * Date: 04/04/2017
 * Time: 17:21
 */
require_once standart_path.'core/RequestPageConstant.php';
require_once standart_path.'core/web/Page.php';
class RequestPage
{
    private $request;
    public function __construct($request){$this->request = $request;}
    public function execute() {
        switch ($this->request) {
            case RequestPageConstant::HOME: $this->requestHome(); break;
            case RequestPageConstant::LOGIN: $this->requestLogin(); break;
            case RequestPageConstant::REGISTRATION: $this->requestRegistration(); break;
            case RequestPageConstant::CONFIRMATION_REGISTRATION: $this->requestConfirmationRegistration(); break;
            case RequestPageConstant::PRODUCT: $this->requestProduct(); break;
            case RequestPageConstant::ABOUT: $this->requestAbout(); break;
            case RequestPageConstant::BOOK: $this->requestBook(); break;
            case RequestPageConstant::BUY_AHP: $this->requestBuyAHP(); break;
            case RequestPageConstant::SELL: $this->requestSell(); break;
            case RequestPageConstant::BUYING: $this->requestBuying(); break;
            case RequestPageConstant::SET_CAR_VALUE: $this->requestSetCarValue(); break;
            case RequestPageConstant::INFORMATION : $this->requestInformation(); break;
            case RequestPageConstant::BOOKING: $this->requestBooking(); break;
            case RequestPageConstant::SELLING: $this->requestSelling(); break;
            case RequestPageConstant::LEASING: $this->requestLeasing(); break;
            case RequestPageConstant::USER: $this->requestUser(); break;
            case RequestPageConstant::CHANGE_PASSWORD: $this->requestChangePassword(); break;
            case RequestPageConstant::EDIT_CAR: $this->requestEditCar(); break;
            default: $this->requestHome();
        }
    }
    private function requestHome() {
        ob_start(); // do not remove or session start error
        $page = new Page();
        $page->load(standart_path."web/fragment/html_header.html");
        $page->push("<head>");
        $page->push("<title>");
        $page->push("Red Motor");
        $page->push("</title>");
        $page->load(standart_path."web/fragment/Head.php");
        $page->push("</head>");
        $page->push("<body>");
        $page->load(standart_path."web/fragment/header.php");
        //$page->load(standart_path."web/fragment/location.html");
        $page->load(standart_path."web/home.php");
        $page->load(standart_path."web/fragment/footer.php");
        $page->push("</body>");
        $page->push("</html>");
        ob_end_flush(); // do not remove or page will not be shown
    }
    private function requestLogin() {
        require_once standart_path.'core/universal_methode.php';
        if( isSessionLoginValid()) {
            echo '<script>window.location.href = "'.getUrlByRequest().'";</script>';
            return;
        }
        $page = new Page();
        $page->load(standart_path."web/fragment/html_header.html");
        $page->push("<head>");
        $page->push("<title>");
        $page->push("Masuk");
        $page->push("</title>");
        $page->load(standart_path."web/fragment/Head.php");
        $page->push("</head>");
        $page->push("<body>");

/*
        $page->push('<div id="social" class="visible-lg">');

        $page->push('<ul class="social-icons pull-right hidden-xs">');
        $page->push('<li class="social-rss">');
        $page->push('<a href="#" target="_blank" title="RSS"></a>');
        $page->push('</li>');

        $page->push('<li class="social-twitter">');
        $page->push('<a href="#" target="_blank" title="Twitter"></a>');
        $page->push('</li>');

        $page->push('<li class="social-facebook">');
        $page->push('<a href="#" target="_blank" title="Facebook"></a>');
        $page->push('</li>');

        $page->push('<li class="social-googleplus">');
        $page->push('<a href="#" target="_blank" title="GooglePlus"></a>');
        $page->push('</li>');

        $page->push('</ul>');

        $page->push('</div>');
*/

        $page->load(standart_path."web/fragment/header.php");
        $page->load(standart_path."web/login.php");
        $page->load(standart_path."web/fragment/footer.php");
        $page->push("</body>");
        $page->push("</html>");
    }
    private function requestRegistration() {
        require_once standart_path.'core/universal_methode.php';
        if( isSessionLoginValid()) {
            echo '<script>window.location.href = "'.getUrlByRequest().'";</script>';
            return;
        }
        $page = new Page();
        $page->load(standart_path."web/fragment/html_header.html");
        $page->push("<head>");
        $page->push("<title>");
        $page->push("Pendaftaran");
        $page->push("</title>");
        $page->load(standart_path."web/fragment/Head.php");
        $page->push("</head>");
        $page->push("<body>");
        $page->load(standart_path."web/registration.php");
        $page->push("</body>");
        $page->push("</html>");
    }
    private function requestConfirmationRegistration() {
        $page = new Page();
        $page->load(standart_path."web/fragment/html_header.html");
        $page->push("<head>");
        $page->push("<title>");
        $page->push("Konfirmasi Pendaftaran");
        $page->push("</title>");
        $page->load(standart_path."web/fragment/Head.php");
        $page->push("</head>");
        $page->push("<body>");
        $page->load(standart_path."web/confirmation_registration.php");
        $page->push("</body>");
        $page->push("</html>");
    }
    private function requestProduct() {
        ob_start(); // do not remove or session start error
        $page = new Page();
        $page->load(standart_path."web/fragment/html_header.html");
        $page->push("<head>");
        $page->push("<title>");
        require_once standart_path.'core/MySQLAccess.php';
        require_once standart_path.'dataobject/Mobil.php';
        $runner = new Runner();
        $runner->connect(GlobalConst\DBConst::HOST, GlobalConst\DBConst::PORT, GlobalConst\DBConst::DATABASE, GlobalConst\DBConst::DATABASE_USERNAME, GlobalConst\DBConst::DATABASE_PASSWORD);
        try {
            $car = new Mobil(filter_input(INPUT_GET, GlobalConst::VAR_CAR, FILTER_VALIDATE_INT));
            $car->setRunner($runner);
            $car->appendLoadBy(Mobil::LOAD_BY_ID);
            $car->load();
            $page->push($car->getName());
        } catch(Exception $ex) {
            $page->push("Not Found");
        }
        $page->push("</title>");
        $page->load(standart_path."web/fragment/Head.php");
        $page->push("</head>");
        $page->push("<body>");
        $page->load(standart_path."web/fragment/header.php");
        //$page->load(standart_path."web/fragment/location.html");
        //$page->push('<center><image alt="Brand Car" src="data:image/png;base64,'.$merek->getPic().'"></image></center>');
        $page->load(standart_path."web/product.php");
        $page->load(standart_path."web/fragment/footer.php");
        $page->push("</body>");
        $page->push("</html>");
        ob_end_flush(); // do not remove or page will not be shown
    }
    private function requestAbout() {
        $page = new Page();
        $page->load(standart_path."web/fragment/html_header.html");
        $page->push("<head>");
        $page->push("<title>");
        $page->push("About Red Motor");
        $page->push("</title>");
        $page->load(standart_path."web/fragment/Head.php");
        $page->push("</head>");
        $page->push("<body>");
        $page->load(standart_path."web/fragment/header.php");
        //$page->load(standart_path."web/fragment/location.html");
        $page->load(standart_path."web/about.php");
        $page->load(standart_path."web/fragment/footer.php");
        $page->push("</body>");
        $page->push("</html>");
    }
    private function requestBook() {
        ob_start();
        require_once standart_path.'core/universal_methode.php';
        if( !isSessionLoginValid()) {
            echo '<script>window.location.href = "'.getUrlByRequest().'";</script>';
            return;
        }
        $page = new Page();
        $page->load(standart_path."web/fragment/html_header.html");
        $page->push("<head>");
        $page->push("<title>");
        $page->push("Pesan Mobil");
        $page->push("</title>");
        $page->load(standart_path."web/fragment/Head.php");
        $page->push("</head>");
        $page->push("<body>");
        $page->load(standart_path."web/pesan.php");
        $page->push("</body>");
        $page->push("</html>");
        ob_end_flush();
    }
    private function requestBuyAHP() {
        $page = new Page();
        $page->load(standart_path."web/fragment/html_header.html");
        $page->push("<head>");
        $page->push("<title>");
        $page->push("Beli AHP");
        $page->push("</title>");
        $page->load(standart_path."web/fragment/Head.php");
        $page->push("</head>");
        $page->push("<body>");
        $page->load(standart_path."web/buy_ahp.php");
        $page->push("</body>");
        $page->push("</html>");
    }
    private function requestSell() {
        $page = new Page();
        $page->load(standart_path."web/fragment/html_header.html");
        $page->push("<head>");
        $page->push("<title>");
        $page->push("Jual");
        $page->push("</title>");
        $page->load(standart_path."web/fragment/Head.php");
        $page->push("</head>");
        $page->push("<body>");
        $page->load(standart_path."web/jual.php");
        $page->push("</body>");
        $page->push("</html>");
    }
    private function requestBuying() {
        ob_start();
        $page = new Page();
        $page->load(standart_path."web/fragment/html_header.html");
        $page->push("<head>");
        $page->push("<title>");
        $page->push("Pembelian");
        $page->push("</title>");
        $page->load(standart_path."web/fragment/Head.php");
        $page->push("</head>");
        $page->push("<body>");
        $page->load(standart_path."web/fragment/header.php");
        //$page->load(standart_path."web/fragment/location.html");
        $page->load(standart_path."web/pembelian.php");
        $page->load(standart_path."web/fragment/footer.php");
        $page->push("</body>");
        $page->push("</html>");
        ob_end_flush();
    }
    private function requestSetCarValue() {
        require_once standart_path.'core/universal_methode.php';
        if( !isSessionLoginValid()) {
            echo '<script>window.location.href = "'.getUrlByRequest().'";</script>';
            return;
        }
        $page = new Page();
        $page->load(standart_path."web/fragment/html_header.html");
        $page->push("<head>");
        $page->push("<title>");
        $page->push("Update Nilai Mobil");
        $page->push("</title>");
        $page->load(standart_path."web/fragment/Head.php");
        $page->push("</head>");
        $page->push("<body>");
        $page->load(standart_path."web/set_car_value.php");
        $page->push("</body>");
        $page->push("</html>");
    }
    private function requestInformation() {
        ob_start();
        $page = new Page();
        $page->load(standart_path."web/fragment/html_header.html");
        $page->push("<head>");
        $page->push("<title>");
        $page->push("Informasi");
        $page->push("</title>");
        $page->load(standart_path."web/fragment/Head.php");
        $page->push("</head>");
        $page->push("<body>");
        $page->load(standart_path."web/fragment/header.php");
        //$page->load(standart_path."web/fragment/location.html");
        $page->load(standart_path."web/informasi.php");
        $page->load(standart_path."web/fragment/footer.php");
        $page->push("</body>");
        $page->push("</html>");
        ob_end_flush();
    }
    private function requestBooking() {
        ob_start();
        $page = new Page();
        $page->load(standart_path."web/fragment/html_header.html");
        $page->push("<head>");
        $page->push("<title>");
        $page->push("Pemesanan");
        $page->push("</title>");
        $page->load(standart_path."web/fragment/Head.php");
        $page->push("</head>");
        $page->push("<body>");
        $page->load(standart_path."web/fragment/header.php");
        //$page->load(standart_path."web/fragment/location.html");
        $page->load(standart_path."web/pemesanan.php");
        $page->load(standart_path."web/fragment/footer.php");
        $page->push("</body>");
        $page->push("</html>");
        ob_end_flush();
    }
    private function requestSelling() {
        ob_start();
        $page = new Page();
        $page->load(standart_path."web/fragment/html_header.html");
        $page->push("<head>");
        $page->push("<title>");
        $page->push("Penjualan");
        $page->push("</title>");
        $page->load(standart_path."web/fragment/Head.php");
        $page->push("</head>");
        $page->push("<body>");
        $page->load(standart_path."web/fragment/header.php");
        //$page->load(standart_path."web/fragment/location.html");
        $page->load(standart_path."web/penjualan.php");
        $page->load(standart_path."web/fragment/footer.php");
        $page->push("</body>");
        $page->push("</html>");
        ob_end_flush();
    }
    private function requestLeasing() {
        ob_start();
        $page = new Page();
        $page->load(standart_path."web/fragment/html_header.html");
        $page->push("<head>");
        $page->push("<title>");
        $page->push("Leasing");
        $page->push("</title>");
        $page->load(standart_path."web/fragment/Head.php");
        $page->push("</head>");
        $page->push("<body>");
        $page->load(standart_path."web/fragment/header.php");
        //$page->load(standart_path."web/fragment/location.html");
        $page->load(standart_path."web/leasing.php");
        $page->load(standart_path."web/fragment/footer.php");
        $page->push("</body>");
        $page->push("</html>");
        ob_end_flush();
    }
    private function requestUser() {
        ob_start();
        $page = new Page();
        $page->load(standart_path."web/fragment/html_header.html");
        $page->push("<head>");
        $page->push("<title>");
        $page->push("Pengguna");
        $page->push("</title>");
        $page->load(standart_path."web/fragment/Head.php");
        $page->push("</head>");
        $page->push("<body>");
        $page->load(standart_path."web/fragment/header.php");
        //$page->load(standart_path."web/fragment/location.html");
        $page->load(standart_path."web/user.php");
        $page->load(standart_path."web/fragment/footer.php");
        $page->push("</body>");
        $page->push("</html>");
        ob_end_flush();
    }
    private function requestChangePassword() {
        $page = new Page();
        $page->load(standart_path."web/fragment/html_header.html");
        $page->push("<head>");
        $page->push("<title>");
        $page->push("Ubah Password");
        $page->push("</title>");
        $page->load(standart_path."web/fragment/Head.php");
        $page->push("</head>");
        $page->push("<body>");
        $page->load(standart_path."web/change_password.php");
        $page->push("</body>");
        $page->push("</html>");
    }
    private function requestEditCar() {
        $page = new Page();
        $page->load(standart_path."web/fragment/html_header.html");
        $page->push("<head>");
        $page->push("<title>");
        $page->push("Edit Mobil");
        $page->push("</title>");
        $page->load(standart_path."web/fragment/Head.php");
        $page->push("</head>");
        $page->push("<body>");
        $page->load(standart_path."web/edit_mobil.php");
        $page->push("</body>");
        $page->push("</html>");
    }
}