<?php
namespace App\Controller\Api;
use App\Service\FormatService;


// require_once __DIR__ . '/../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/*
 * API: Media Suggestion endpoint
 */

class SuggestApiController
{
    private FormatService $formatService;

    public function __construct(FormatService $formatService)
    {
        $this->formatService = $formatService;
    }

    /*
     * POST /api-suggest
     */
    public function store(): void
    {
        header('Content-Type: application/json');

        try {

            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                http_response_code(405);
                echo json_encode([
                    'success' => false,
                    'message' => 'Method not allowed'
                ]);
                return;
            }

            $input = $this->getInput();
            $result = $this->process($input);

            if (!empty($result['error'])) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => $result['error']
                ]);
                return;
            }

            echo json_encode([
                'success' => true,
                'message' => 'Suggestion sent successfully'
            ]);
        } catch (Exception $e) {

            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /*
     * Validation + Email logic
     */
    private function process(array $data): array
    {
        $data = [
            'name'     => trim($data['name'] ?? ''),
            'email'    => trim($data['email'] ?? ''),
            'category' => trim($data['category'] ?? ''),
            'title'    => trim($data['title'] ?? ''),
            'format'   => trim($data['format'] ?? ''),
            'genre'    => trim($data['genre'] ?? ''),
            'year'     => trim($data['year'] ?? ''),
            'details'  => trim($data['details'] ?? ''),
        ];

        if (
            empty($data['name']) ||
            empty($data['email']) ||
            empty($data['category']) ||
            empty($data['title'])
        ) {
            return ['error' => 'Name, Email, Category and Title are required'];
        }

        if (!PHPMailer::validateAddress($data['email'])) {
            return ['error' => 'Invalid email address'];
        }

        $this->sendEmail($data);

        return ['success' => true];
    }

    /*
     * FIXED SMTP EMAIL CONFIG (IMPORTANT)
     */
    private function sendEmail(array $data): void
    {
        $mail = new PHPMailer(true);

        /*
     * SMTP CONFIG
     */

        $mail->isSMTP();

        $mail->Host = $_ENV['MAIL_HOST'];

        $mail->SMTPAuth = true;

        $mail->Username = $_ENV['MAIL_USERNAME'];

        $mail->Password = $_ENV['MAIL_PASSWORD'];

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

        $mail->Port = (int) $_ENV['MAIL_PORT'];

        $mail->CharSet = 'UTF-8';

        /*
     * SSL FIX FOR XAMPP
     */

        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ];

        /*
     * DEBUG
     * REMOVE LATER
     */

        $mail->SMTPDebug = 2;

        /*
     * EMAIL
     */

        $mail->setFrom(
            $_ENV['MAIL_FROM_EMAIL'],
            $_ENV['MAIL_FROM_NAME']
        );

        $mail->addReplyTo(
            $data['email'],
            $data['name']
        );

        $mail->addAddress(
            $_ENV['MAIL_FROM_EMAIL']
        );

        $mail->Subject =
            'Library Suggestion from: ' . $data['name'];

        $mail->Body =
            "Name: {$data['name']}\n" .
            "Email: {$data['email']}\n\n" .
            "Category: {$data['category']}\n" .
            "Title: {$data['title']}\n" .
            "Format: {$data['format']}\n" .
            "Genre: {$data['genre']}\n" .
            "Year: {$data['year']}\n\n" .
            "Details:\n{$data['details']}\n";
        //     var_dump($_ENV['MAIL_USERNAME']);
        // var_dump($_ENV['MAIL_PASSWORD']);
        // exit;


        $mail->send();
    }
    /**
     * Accept JSON or form-data
     */
    private function getInput(): array
    {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';

        if (strpos($contentType, 'application/json') !== false) {
            $json = file_get_contents("php://input");
            return json_decode($json, true) ?? [];
        }

        return $_POST;
    }
}
