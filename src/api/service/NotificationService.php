<?php
use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;
use PHPMailer\PHPMailer\PHPMailer;

class NotificationService
{
    public function email($params = array(
        'smtp' => array('host' => '', 'username' => '', 'password' => '', 'port' => 465),
        'from' => array('mail' => '', 'name' => ''),
        'send' => array('mail' => '', 'name' => ''),
        'subject' => '',
        'body' => '',
    )) {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = $params['smtp']['host'];
        $mail->SMTPAuth = true;
        $mail->Username = $params['smtp']['username'];
        $mail->Password = $params['smtp']['password'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = $params['smtp']['port'];

        $mail->setFrom($params['from']['mail'], $params['from']['name']);
        $mail->addAddress($params['send']['mail'], $params['send']['name']);

        $mail->isHTML(true);
        $mail->Subject = $params['subject'];
        $mail->Body = $params['body'];

        $mail->send();
    }

    public function sms($params = array(
        'service_provider' => 'aliyun',
        'options' => array(),
    )) {
        switch ($params['service_provider']) {
            case 'aliyun':
                $this->smsAliyun($params['options']);
                break;
        }
    }

    public function smsAliyun($options = array())
    {
        AlibabaCloud::accessKeyClient($options['accessKeyId'], $options['accessSecret'])
            ->regionId($options['RegionId'])
            ->asGlobalClient();
        try {
            $result = AlibabaCloud::rpcRequest()
                ->product('Dysmsapi')
                ->scheme('https')
                ->version(date('Y-m-d', time()))
                ->action('SendSms')
                ->method('POST')
                ->options([
                    'query' => [
                        'RegionId' => $options['RegionId'],
                        'PhoneNumbers' => $options['PhoneNumbers'],
                        'SignName' => $options['SignName'],
                        'TemplateCode' => $options['TemplateCode'],
                        'TemplateParam' => json_encode($options['TemplateParam']),
                    ],
                ])
                ->request();
            return $result;
        } catch (ClientException $e) {
            return array(
                'statusCode' => '503',
                'message' => $e->getErrorMessage(),
            );
        } catch (ServerException $e) {
            return array(
                'statusCode' => '503',
                'message' => $e->getErrorMessage(),
            );
        }
    }
}
