# cpsms

![Screenshot](/images/screenshot.png)

This is a SMS provider to use with the danish SMS gateway cpsms.dk

The extension is licensed under [AGPL-3.0](LICENSE.txt).

## Requirements

* PHP v7.4+
* CiviCRM 5.59+

## Installation (Web UI)

Learn more about installing CiviCRM extensions in the [CiviCRM Sysadmin Guide](https://docs.civicrm.org/sysadmin/en/latest/customize/extensions/).

## Installation (CLI, Zip)

Sysadmins and developers may download the `.zip` file for this extension and
install it with the command-line tool [cv](https://github.com/civicrm/cv).


## Getting Started

In Administer/System Setting/SMS Provider press 'Add SMS Provider'
Fill in the required fields.
In the password field insert the base64 encoded authorization string from the 
API-nøgle section of the CPSMS Indstillinger.
The from=xxx in api-params will control the from address shown for the SMS

