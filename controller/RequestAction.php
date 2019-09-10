<?php

/**
 * Created by PhpStorm.
 * User: LC
 * Date: 04/04/2017
 * Time: 17:21
 */
require_once standart_path.'core/RequestActionConstant.php';
require_once standart_path.'core/JSONEncoder.php';
class RequestAction
{
    private $request;
    public function __construct($request)
    {
        $this->request = $request;
    }
    public function execute(){
        switch($this->request) {
            case RequestActionConstant::LOGIN:  $this->requestLogin(); break;
            case RequestActionConstant::LOGOUT: $this->requestLogout(); break;
            case RequestActionConstant::PRE_REGISTRATION: $this->requestPreRegistration(); break;
            case RequestActionConstant::REGISTRATION: $this->requestRegistration(); break;
            case RequestActionConstant::GET_CARS: $this->requestGetCars(); break;
            case RequestActionConstant::GET_CARS_BY_IDS : $this->requestGetCarsByIds(); break;
            case RequestActionConstant::GET_KIND_OF_CARS: $this->requestGetKindOfCars(); break;
            case RequestActionConstant::GET_BOOK_ID: $this->requestGetBookId(); break;
            case RequestActionConstant::PROCESS_AHP: $this->requestProcessAHP(); break;
            case RequestActionConstant::SUBMIT_BUY: $this->requestSubmitBuy(); break;
            case RequestActionConstant::GET_NEW_CAR_ID: $this->requestGetNewCarId(); break;
            case RequestActionConstant::GET_SELL_ID: $this->getSellId(); break;
            case RequestActionConstant::GET_BUYING: $this->requestGetBuying(); break;
            case RequestActionConstant::GET_SET_CAR_VALUE: $this->requestGetSetCarValue(); break;
            case RequestActionConstant::SUBMIT_BUYING: $this->requestSubmitBuying(); break;
            case RequestActionConstant::SUBMIT_PESAN: $this->requestSubmitPesan(); break;
            case RequestActionConstant::LEASING_OPERATION: $this->reuestLeasingOperation(); break;
            case RequestActionConstant::GET_BOOKINGS: $this->requestGetBookings(); break;
            case RequestActionConstant::SUBMIT_SELLING: $this->requestSubmitSelling(); break;
            case RequestActionConstant::CLOSE_BOOKING: $this->requestCloseBooking(); break;
            case RequestActionConstant::USER_OPERATION: $this->requestUserOperation(); break;
            case RequestActionConstant::CHANGE_PASSWORD : $this->requestChangePassword(); break;
            case RequestActionConstant::FORGOT_PASSWORD : $this->requestForgotPassword(); break;
            case RequestActionConstant::SUBMIT_EDIT_CAR: $this->requestSubmitEditCar(); break;
            default: {
                header("HTTP/1.0 404 Not Found - command not found", true, 404);
                exit();
            } break;
        }
    }
    private function requestLogin() {
        require_once standart_path.'action/Login.php';
        $encoder = new JSONEncoder();
        try {
            $login = new Login(filter_input(INPUT_POST, Login::USERNAME), filter_input(INPUT_POST, Login::PASSWORD));
            $login->execute();
            $encoder->status_berhasil();
        } catch(Exception $ex) {
            $encoder->status_gagal();
            $encoder->set_message($ex->getMessage());
        }
        $encoder->show();
    }
    private function requestLogout() {
        require_once standart_path.'action/Logout.php';
        $logout = new Logout();
        $logout->execute();
    }
    private function requestPreRegistration() {
        require_once standart_path.'action/PreRegistration.php';
        $encoder = new JSONEncoder();
        try {
            $pre_reg = new PreRegistration(filter_input(INPUT_POST, PreRegistration::USERNAME),
                filter_input(INPUT_POST, PreRegistration::PASSWORD),
                filter_input(INPUT_POST, PreRegistration::NAME),
                filter_input(INPUT_POST, PreRegistration::NO_KTP),
                filter_input(INPUT_POST, PreRegistration::BIRTH_DATE),
                filter_input(INPUT_POST, PreRegistration::SEX),
                filter_input(INPUT_POST, PreRegistration::ADDRESS),
                filter_input(INPUT_POST, PreRegistration::TELEPHONE),
                filter_input(INPUT_POST, PreRegistration::EMAIL),
                MEMBER);
            $pre_reg->execute();
            $encoder->status_berhasil();
        } catch(Exception $ex) {
            $encoder->status_gagal();
            $encoder->set_message($ex->getMessage());
        }
        $encoder->show();
    }
    private function requestRegistration() {
        require_once standart_path.'action/Registration.php';
        $encoder = new JSONEncoder();
        try {
            $exec = new Registration(filter_input(INPUT_POST, Registration::REGISTRATION_TOKEN));
            $exec->execute();
            $encoder->status_berhasil();
        } catch(Exception $ex) {
            $encoder->status_gagal();
            $encoder->set_message($ex->getMessage());
        }
        $encoder->show();
    }
    private function requestGetCars() {
        require_once standart_path.'action/GetCars.php';
        $encoder = new JSONEncoder();
        try {
            $exec = new GetCars(filter_input(INPUT_POST, GetCars::BRAND),
                filter_input(INPUT_POST, GetCars::TYPE),
                filter_input(INPUT_POST, GetCars::KIND),
                filter_input(INPUT_POST, GetCars::TRANSMISSION),
                filter_input(INPUT_POST, GetCars::PRICE_MIN),
                filter_input(INPUT_POST, GetCars::PRICE_MAX),
                filter_input(INPUT_POST, GetCars::ODOMETER_MIN),
                filter_input(INPUT_POST, GetCars::ODOMETER_MAX));
            $res = $exec->execute();
            $encoder->status_berhasil();
            $encoder->set_result($res);
        } catch(Exception $ex) {
            $encoder->status_gagal();
            $encoder->set_message($ex->getMessage());
        }
        $encoder->show();
    }
    private function requestGetCarsByIds() {
        require_once standart_path.'action/GetCarsByIds.php';
        $encoder = new JSONEncoder();
        try {
            $exec = new GetCarsByIds($_POST[GetCarsByIds::INPUT_IDS]);
            $res = $exec->execute();
            $encoder->status_berhasil();
            $encoder->set_result($res);
        } catch(Exception $ex) {
            $encoder->status_gagal();
            $encoder->set_message($ex->getMessage());
        }
        $encoder->show();
    }
    private function requestGetKindOfCars() {
        require_once standart_path.'action/GetKindOfCars.php';
        $encoder = new JSONEncoder();
        try {
            $exec = new GetKindOfCars(filter_input(INPUT_POST, GetKindOfCars::BRAND), filter_input(INPUT_POST, GetKindOfCars::TYPE));
            $res = $exec->execute();
            $encoder->status_berhasil();
            $encoder->set_result($res);
        } catch(Exception $ex) {
            $encoder->status_gagal();
            $encoder->set_message($ex->getMessage());
        }
        $encoder->show();
    }
    private function requestGetBookId() {
        require_once standart_path.'action/GetBookId.php';
        $encoder = new JSONEncoder();
        try {
            $exec = new GetBookId();
            $res = $exec->execute();
            $encoder->status_berhasil();
            $encoder->set_result($res);
        } catch (Exception $exception) {
            $encoder->status_gagal();
            $encoder->set_message($exception->getMessage());
        }
        $encoder->show();
    }
    private function requestProcessAHP() {
        require_once standart_path.'action/ProcessAHP.php';
        $encoder = new JSONEncoder();
        try {
            $exec = new ProcessAHP(
                filter_input(INPUT_POST, ProcessAHP::INPUT_CAR_TYPE, FILTER_VALIDATE_INT),
                filter_input(INPUT_POST, ProcessAHP::INPUT_MIN_PRICE),
                filter_input(INPUT_POST, ProcessAHP::INPUT_MAX_PRICE),
                $_POST[ProcessAHP::INPUT_CRITERIA],
                $_POST[ProcessAHP::INPUT_DATA]);
            $res = $exec->execute();
            $encoder->status_berhasil();
            $encoder->set_result($res);
        } catch (Exception $exception) {
            $encoder->status_gagal();
            $encoder->set_message($exception->getMessage());
        }
        $encoder->show();
    }
    private function requestSubmitBuy() {
        require_once standart_path.'action/SubmitBuy.php';
        $encoder = new JSONEncoder();
        try {
            $exec = new SubmitBuy(
                filter_input(INPUT_POST, SubmitBuy::INPUT_ID, FILTER_VALIDATE_INT),
                filter_input(INPUT_POST, SubmitBuy::INPUT_PRICE, FILTER_VALIDATE_INT),
                filter_input(INPUT_POST, SubmitBuy::INPUT_CAR_ID, FILTER_VALIDATE_INT),
                filter_input(INPUT_POST, SubmitBuy::INPUT_CAR_NAME),
                filter_input(INPUT_POST, SubmitBuy::INPUT_CAR_KIND, FILTER_VALIDATE_INT),
                filter_input(INPUT_POST, SubmitBuy::INPUT_CAR_TRANSMISSION, FILTER_VALIDATE_INT),
                filter_input(INPUT_POST, SubmitBuy::INPUT_CAR_YEAR, FILTER_VALIDATE_INT),
                filter_input(INPUT_POST, SubmitBuy::INPUT_CAR_COLOR),
                filter_input(INPUT_POST, SubmitBuy::INPUT_CAR_ODOMETER, FILTER_VALIDATE_INT),
                filter_input(INPUT_POST, SubmitBuy::INPUT_CAR_NENGINE),
                filter_input(INPUT_POST, SubmitBuy::INPUT_CAR_NCHASSIS),
                $_FILES[SubmitBuy::INPUT_CAR_PIC]
            );
            $exec->execute();
            $encoder->status_berhasil();
        } catch (Exception $exception) {
            $encoder->status_gagal();
            $encoder->set_message($exception->getMessage());
        }
        $encoder->show();
    }
    private function requestGetNewCarId() {
        require_once standart_path.'action/GetNewCarId.php';
        $encoder = new JSONEncoder();
        try {
            $exec = new GetNewCarId();
            $res = $exec->execute();
            $encoder->status_berhasil();
            $encoder->set_result($res);
        } catch (Exception $exception) {
            $encoder->status_gagal();
            $encoder->set_message($exception->getMessage());
        }
        $encoder->show();
    }
    private function getSellId() {
        require_once standart_path.'action/GetSellId.php';
        $encoder = new JSONEncoder();
        try {
            $exec = new GetSellId();
            $res = $exec->execute();
            $encoder->status_berhasil();
            $encoder->set_result($res);
        } catch (Exception $exception) {
            $encoder->status_gagal();
            $encoder->set_message($exception->getMessage());
        }
        $encoder->show();
    }
    private function requestGetBuying() {
        require_once standart_path.'action/GetBuying.php';
        $encoder = new JSONEncoder();
        try {
            $exec = new GetBuying(filter_input(INPUT_POST, GetBuying::INPUT_DATE_FINISH_UNFINISHED, FILTER_VALIDATE_INT));
            $res = $exec->execute();
            $encoder->status_berhasil();
            $encoder->set_result($res);
        } catch (Exception $exception) {
            $encoder->status_gagal();
            $encoder->set_message($exception->getMessage());
        }
        $encoder->show();
    }
    private function requestGetSetCarValue() {
        require_once standart_path.'action/GetSetCarValue.php';
        $encoder = new JSONEncoder();
        try {
            $exec = new GetSetCarValue(
                filter_input(INPUT_POST, GetSetCarValue::INPUT_OPERATION, FILTER_VALIDATE_INT),
                filter_input(INPUT_POST, GetSetCarValue::SG_ID, FILTER_VALIDATE_INT),
                filter_input(INPUT_POST, GetSetCarValue::SG_DOCUMENT),
                filter_input(INPUT_POST, GetSetCarValue::SG_ENGINE),
                filter_input(INPUT_POST, GetSetCarValue::SG_ODOMETER),
                filter_input(INPUT_POST, GetSetCarValue::SG_INTERIOR),
                filter_input(INPUT_POST, GetSetCarValue::SG_EXTERIOR),
                filter_input(INPUT_POST, GetSetCarValue::SG_YEAR),
                filter_input(INPUT_POST, GetSetCarValue::SG_PRICE));
            $res = $exec->execute();
            $encoder->status_berhasil();
            $encoder->set_result($res);
        } catch (Exception $exception) {
            $encoder->status_gagal();
            $encoder->set_message($exception->getMessage());
        }
        $encoder->show();
    }
    private function requestSubmitBuying() {
        require_once standart_path.'action/SubmitBuying.php';
        $encoder = new JSONEncoder();
        try {
            $exec = new SubmitBuying(
                filter_input(INPUT_POST, SubmitBuying::INPUT_ID, FILTER_VALIDATE_INT),
                filter_input(INPUT_POST, SubmitBuying::INPUT_CAR_ID, FILTER_VALIDATE_INT),
                filter_input(INPUT_POST, SubmitBuying::INPUT_DEAL_PRICE),
                filter_input(INPUT_POST, SubmitBuying::INPUT_SELL_PRICE));
            $exec->execute();
            $encoder->status_berhasil();
        } catch (Exception $exception) {
            $encoder->status_gagal();
            $encoder->set_message($exception->getMessage());
        }
        $encoder->show();
    }
    private function requestSubmitPesan() {
        require_once standart_path.'action/SubmitPesan.php';
        $encoder = new JSONEncoder();
        try {
            $exec = new SubmitPesan(
                filter_input(INPUT_POST, SubmitPesan::INPUT_ID),
                filter_input(INPUT_POST, SubmitPesan::INPUT_CAR),
                filter_input(INPUT_POST, SubmitPesan::INPUT_METHOD),
                filter_input(INPUT_POST, SubmitPesan::INPUT_COMMENT));
            $exec->execute();
            $encoder->status_berhasil();
        } catch (Exception $exception) {
            $encoder->status_gagal();
            $encoder->set_message($exception->getMessage());
        }
        $encoder->show();
    }
    private function reuestLeasingOperation() {
        require_once standart_path.'action/LeasingOperation.php';
        $encoder = new JSONEncoder();
        try {
            $exec = new LeasingOperation(
                filter_input(INPUT_POST, LeasingOperation::INPUT_OPERATION, FILTER_VALIDATE_INT),
                filter_input(INPUT_POST, LeasingOperation::INPUT_ID),
                filter_input(INPUT_POST, LeasingOperation::INPUT_NAME),
                filter_input(INPUT_POST, LeasingOperation::INPUT_ADDRESS),
                filter_input(INPUT_POST, LeasingOperation::INPUT_TELEPHONE));
            $res = $exec->execute();
            $encoder->status_berhasil();
            if($res !== null) $encoder->set_result($res);
        } catch (Exception $exception) {
            $encoder->status_gagal();
            $encoder->set_message($exception->getMessage());
        }
        $encoder->show();
    }
    private function requestGetBookings() {
        require_once standart_path.'action/GetBookings.php';
        $encoder = new JSONEncoder();
        try {
            $exec = new GetBookings(filter_input( INPUT_POST,GetBookings::INPUT_LOOP, FILTER_VALIDATE_INT));
            $res = $exec->execute();
            $encoder->status_berhasil();
            $encoder->set_result($res);
        } catch (Exception $exception) {
            $encoder->status_gagal();
            $encoder->set_message($exception->getMessage());
        }
        $encoder->show();
    }
    private function requestSubmitSelling() {
        require_once standart_path.'action/SubmitSelling.php';
        require_once standart_path.'action/CloseBooking.php';
        $encoder = new JSONEncoder();
        try {
            $exec = new SubmitSelling(filter_input(INPUT_POST, SubmitSelling::INPUT_BOOK_ID, FILTER_VALIDATE_INT), filter_input(INPUT_POST, SubmitSelling::INPUT_TOTAL_PRICE), filter_input(INPUT_POST, SubmitSelling::INPUT_LEASING_ID));
            $exec->execute();
            $encoder->status_berhasil();
        } catch (Exception $exception) {
            $encoder->status_gagal();
            $encoder->set_message($exception->getMessage());
        }
        $encoder->show();
    }
    private function requestCloseBooking() {
        require_once standart_path.'action/CloseBooking.php';
        $encoder = new JSONEncoder();
        try {
            $exec = new CloseBooking(filter_input( INPUT_POST,CloseBooking::INPUT_ID, FILTER_VALIDATE_INT));
            $exec->execute();
            $encoder->status_berhasil();
        } catch (Exception $exception) {
            $encoder->status_gagal();
            $encoder->set_message($exception->getMessage());
        }
        $encoder->show();
    }
    private function requestUserOperation() {
        require_once standart_path.'action/UserOperation.php';
        $encoder = new JSONEncoder();
        try {
            $exec = new UserOperation(
                filter_input( INPUT_POST,UserOperation::INPUT_OPERATION, FILTER_VALIDATE_INT),
                filter_input(INPUT_POST, UserOperation::INPUT_ID),
                filter_input(INPUT_POST, UserOperation::INPUT_USERNAME),
                filter_input(INPUT_POST, UserOperation::INPUT_PASSWORD),
                filter_input(INPUT_POST, UserOperation::INPUT_NAME),
                filter_input(INPUT_POST, UserOperation::INPUT_KTP),
                filter_input(INPUT_POST, UserOperation::INPUT_BIRTH_DATE),
                filter_input(INPUT_POST, UserOperation::INPUT_SEX),
                filter_input(INPUT_POST, UserOperation::INPUT_ADDRESS),
                filter_input(INPUT_POST, UserOperation::INPUT_TELEPHONE),
                filter_input(INPUT_POST, UserOperation::INPUT_EMAIL),
                filter_input(INPUT_POST, UserOperation::INPUT_LEVEL));
            $res = $exec->execute();
            $encoder->status_berhasil();
            $encoder->set_result($res);
        } catch (Exception $exception) {
            $encoder->status_gagal();
            $encoder->set_message($exception->getMessage());
        }
        $encoder->show();
    }
    private function requestChangePassword()
    {
        require_once standart_path.'action/ChangePassword.php';
        $encoder = new JSONEncoder();
        try {
            $exec = new ChangePassword(
                filter_input( INPUT_POST,ChangePassword::TOKEN),
                filter_input(INPUT_POST, ChangePassword::PASSWORD));
            $exec->execute();
            $encoder->status_berhasil();
        } catch (Exception $exception) {
            $encoder->status_gagal();
            $encoder->set_message($exception->getMessage());
        }
        $encoder->show();
    }

    private function requestForgotPassword()
    {
        require_once standart_path.'action/ForgotPassword.php';
        $encoder = new JSONEncoder();
        try {
            $exec = new ForgotPassword(filter_input( INPUT_POST,ForgotPassword::ID_USERNAME));
            $exec->execute();
            $encoder->status_berhasil();
        } catch (Exception $exception) {
            $encoder->status_gagal();
            $encoder->set_message($exception->getMessage());
        }
        $encoder->show();
    }
    private function requestSubmitEditCar() {
        require_once standart_path.'action/EditCar.php';
        $encoder = new JSONEncoder();
        try {
            $exec = new EditCar(
                filter_input(INPUT_POST, EditCar::INPUT_CAR_ID, FILTER_VALIDATE_INT),
                filter_input(INPUT_POST, EditCar::INPUT_CAR_NAME),
                filter_input(INPUT_POST, EditCar::INPUT_CAR_KIND, FILTER_VALIDATE_INT),
                filter_input(INPUT_POST, EditCar::INPUT_CAR_TRANSMISSION, FILTER_VALIDATE_INT),
                filter_input(INPUT_POST, EditCar::INPUT_CAR_YEAR, FILTER_VALIDATE_INT),
                filter_input(INPUT_POST, EditCar::INPUT_CAR_COLOR),
                filter_input(INPUT_POST, EditCar::INPUT_CAR_ODOMETER, FILTER_VALIDATE_INT),
                filter_input(INPUT_POST, EditCar::INPUT_CAR_NENGINE),
                filter_input(INPUT_POST, EditCar::INPUT_CAR_NCHASSIS),
                filter_input(INPUT_POST, EditCar::INPUT_CAR_PRICE),
                $_FILES[EditCar::INPUT_CAR_PIC]
            );
            $exec->execute();
            $encoder->status_berhasil();
        } catch (Exception $exception) {
            $encoder->status_gagal();
            $encoder->set_message($exception->getMessage());
        }
        $encoder->show();
    }
}