/*
It is used to one of which is user input validation.
ltdReport function to validate the user.

Version : 1.0
Author : Madhubala (YJ0009)
Date : 05-Jul-2023
*/
// Import the required packages and libraries
const Joi = require("@hapi/joi");
// To declare ltdReport object 
const footwallvalidate = Joi.object().keys({
  // Object Properties are define
//   request_id:    Joi.string().required().label("Request Id"),/
//   user_id:       Joi.string().required().label("User Id"),
//   table_name:    Joi.string().required().label("Table name"),
//   backup_dbname: Joi.string().required().label("Backup dbname"),

}).options({ abortEarly: false });
// To exports the ltdReport module
module.exports = footwallvalidate