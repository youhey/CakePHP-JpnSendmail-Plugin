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

/**
 * デフォルトの設定なので、プログラムから上書きしてください。
 * <cpde>
 * class ExampleController extends AppController {
 *     public function beforeFilter() {
 *         Configure::write('JpnSendmail.mailFrom', 'hoge@example.com');
 *         Configure::write('JpnSendmail.mailName', 'User Name');
 *         Configure::write('JpnSendmail.mailReplyto', 'foo@example.com');
 *     }
 * </code>
 */
$config['JpnSendmail']['mailFrom']    = 'unknown@example.com';
$config['JpnSendmail']['mailReplyto'] = 'unknown@example.com';
