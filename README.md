# CakePHP-JpnSendmail-Plugin

PHP�̃}���`�o�C�g���i���ɓ��{��j�ɓ������Ă��鑽�@�\�ȃ��[�����M���W���[���uQdmail�v���ACakePHP����ȒP�ɗ��p���邽�߂̃v���O�C���ł��B

## �g�p��

���[���𑗐M�������N���X�ŁuJpnSendmail.SmtpSender�v�R���|�[�l���g��L���ɂ��܂��B
���L�̓��[���𑗐M����P���ȃ��W�b�N�ł��B

    class ExampleController extends AppController {
        public $components = array(
                'JpnSendmail.SmtpSender',
            );
    
        public function beforeFilter() {
            parent::beforeFilter();
    
            $from    = 'info@example.com';
            $name    = 'Web Master';
            $replyto = 'webmaster@example.com';
            Configure::write('JpnSendmail.mailFrom', $from);
            Configure::write('JpnSendmail.mailName', $name);
            Configure::write('JpnSendmail.mailReplyto', $replyto);
        }
    
        public function sendmail($username, $email) {
            // username...
            // email...
    
            $template = 'secret'
            $secretId = 'hogehoge';
            $content  = array(
                    'SecretId' => $secretId,
                    'UserName' => $username,
                );
    
            $this->SmtpSender->sendmail($email, $subject, $content, $template);
        }
    }

�܂��A��L�Őݒ肵���usecret�v�̃r���[���쐬���܂��B
app/views/elements/email/text/secret.ctp

    <?php echo Set::extract('UserName', $content) ?>�l
    
    ���\�����F<?php echo date('Y�Nn��j�� H:i:s').PHP_EOL ?>
    
    ���[���𑗂邩���낵���ˁB
    
    �閧��ID�F<?php echo Set::extract('SecretId', $content).PHP_EOL ?>
    �厖�ɂ��ĂˁB
