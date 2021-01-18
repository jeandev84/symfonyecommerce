<?php
namespace App\Service;



use Mailjet\Client;
use Mailjet\Resources;


/**
 * Class Mail <<mailjet>>
*/
class Mail
{

    /**
     * @var string
    */
    private $apiKey = '594188d14fc52dd3c7dec3c0a17c0022';


    /**
     * @var string
    */
    private $apiKeySecret = '5245572494149dd94ab53cfe34c3f700';


    /**
     * @param $toEmail
     * @param $toName
     * @param $subject
     * @param $content
    */
    public function send($toEmail, $toName, $subject, $content)
    {
        $mj = new Client($this->apiKey, $this->apiKeySecret,true,['version' => 'v3.1']);

        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "jeanyao@ymail.com",
                        'Name' => "La Boutique Francaise"
                    ],
                    'To' => [
                        [
                            'Email' => $toEmail,
                            'Name' => $toName
                        ]
                    ],
                    'TemplateID' => 2229677,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables' => [
                          "content" => $content
                     ]
                ]
            ]
        ];

        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success() && dd($response->getData());
    }
}