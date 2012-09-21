<?php
/**
 * Qdmailコンポーネントをラッピングした日本語メール配信ライブラリ
 * 
 * PHP versions >= 5.2
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 * @since   JpnSendmail 1.0
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

if (!isset($config)) {
    $config = array();
}
if (!isset($config['JpnSendmail'])) {
    $config['JpnSendmail'] = array();
}

/** SMTP */
$config['JpnSendmail']['smtpProtocol'] = 'SMTP';
$config['JpnSendmail']['smtpHost']     = 'localhost';

// If protocol are SMTP set to 25.
// If protocol are SMTPAuth set to 587.
$config['JpnSendmail']['smtpPort'] = '25';
