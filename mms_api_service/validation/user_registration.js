/*
It is used to one of which is user input validation.
ChangePassword function to validate the user.

Version : 1.0
Author : Madhubala (YJ0009)
Date : 05-Jul-2023
*/
// Import the required packages and libraries
const Joi = require("@hapi/joi");
// To declare ChangePassword object 
const ChangePassword = Joi.object().keys({
  // Object Properties are define
  request_id: Joi.string().required().label("request_id"),
  customer_mobile: Joi.string().required().label("customer_mobile"),
  customer_name: Joi.string().required().label("customer_name"),
  customer_interest: Joi.string().required().label("Customer_Interest"),
  camera_id: Joi.string().required().label("camera_id"),
  image: Joi.string().required().label("image"),
  otp_verify: Joi.string().required().label("otp_verify"),
  customer_email: Joi.string().required().label("customer_email"),


}).options({ abortEarly: false });
// To exports the ChangePassword module
module.exports = ChangePassword
