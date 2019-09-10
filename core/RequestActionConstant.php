<?php

/**
 * Created by PhpStorm.
 * User: LC
 * Date: 04/04/2017
 * Time: 17:22
 */
class RequestActionConstant
{
    const FLAG = 2;
    const LOGIN = "login-".RequestActionConstant::FLAG;
    const LOGOUT = "logout-".RequestActionConstant::FLAG;
    const REGISTRATION = "Registration-".RequestActionConstant::FLAG;
    const PRE_REGISTRATION = "PreRegistration-".RequestActionConstant::FLAG;
    const GET_CARS = "get_cars-".RequestActionConstant::FLAG;
    const GET_CARS_BY_IDS = "get_cars_by_ids-".RequestActionConstant::FLAG;
    const GET_KIND_OF_CARS = "get_kind_of_cars-".RequestActionConstant::FLAG;
    const GET_BOOK_ID = "get_book_id-".RequestActionConstant::FLAG;
    const PROCESS_AHP = "process_ahp-".RequestActionConstant::FLAG;
    const SUBMIT_BUY = "submit_buy-".RequestActionConstant::FLAG;
    const GET_NEW_CAR_ID = "get_new_car_id-".RequestActionConstant::FLAG;
    const GET_SELL_ID = "get_sell_id-".RequestActionConstant::FLAG;
    const GET_BUYING = "get_buying-".RequestActionConstant::FLAG;
    const GET_SET_CAR_VALUE = "set_car_value-".RequestActionConstant::FLAG;
    const SUBMIT_BUYING = "submit_buying-".RequestActionConstant::FLAG;
    const SUBMIT_PESAN = "submit_pesan-".RequestActionConstant::FLAG;
    const LEASING_OPERATION = "leasing_operation-".RequestActionConstant::FLAG;
    const GET_BOOKINGS = "get_bookings-".RequestActionConstant::FLAG;
    const SUBMIT_SELLING = "submit_selling-".RequestActionConstant::FLAG;
    const CLOSE_BOOKING = "close_booking-".RequestActionConstant::FLAG;
    const USER_OPERATION = "user_operation-".RequestActionConstant::FLAG;
    const CHANGE_PASSWORD = "change_password-".RequestActionConstant::FLAG;
    const FORGOT_PASSWORD = "forgot_passwort-".RequestActionConstant::FLAG;
    const SUBMIT_EDIT_CAR = "submit_edit_car-".RequestActionConstant::FLAG;
}