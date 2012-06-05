<?php
/**
 * Update Prospect Test
 * This is a basic action to populate a prospect object
 * Then we can use magical getters and setters
 * to update the data array.
 * This should work on default and custom fields.
 * LAST TESTED 6/5/2012
 */
include('./PardotConnector.class.php');
include('./Prospect.class.php');
$p = new Prospect();
$p = $p->fetchProspectByEmail('api@pardot.com');
$p->email = 'api+10@pardot.com';
$p->save();
$p->email = 'api@pardot.com';
$p-save();

