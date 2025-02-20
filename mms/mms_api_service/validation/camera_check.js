/*
It is used to one of which is user input validation.
CameraCheck function to validate the user.

Version : 1.0
Author : Madhubala (YJ0009)
Date : 05-Jul-2023
*/
// Import the required packages and libraries
const Joi = require("@hapi/joi");
// To declare CameraCheck object 
const cameraCheck = Joi.object().keys({
  // Object Properties are define
  request_id: Joi.string().required().label("Request ID"),
  camera_name: Joi.string().required().label("Camera name"),
  list: Joi.string().required().label("List"),
  ip_address: Joi.string().required().label("Ip adress"),
  camera_details: Joi.string().required().label("Camera details"),
  cameraradio1: Joi.string().required().label("Camera radio1"),

}).options({ abortEarly: false });
// To exports the CameraCheck module
module.exports = cameraCheck

