<?php
$menue = '<md-tabs>
    <md-primary-tab id="home" active>Home</md-primary-tab>
    <md-primary-tab id="files">Files</md-primary-tab>
    <md-primary-tab id="reminders">ToDo</md-primary-tab>
    <md-primary-tab id="settings">Settings</md-primary-tab>';

$admin = $session->getAdmin();

if ($admin) {
    $menue .= '<md-primary-tab id="store">App Store</md-primray-tab>';
}

$menue .= '</md-tabs>';
echo $menue;
?>