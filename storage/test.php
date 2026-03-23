<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

define('DOMAIN_POINTED_DIRECTORY', 'public');

$companyPhone = getWebConfig('company_phone');
$companyEmail = getWebConfig('company_email');
$companyName = getWebConfig('company_name');
$companyLogo = getWebConfig('company_web_logo');
$lang = \App\Utils\Helpers::default_lang();

echo "companyPhone type: " . gettype($companyPhone) . "\n";
echo "companyEmail type: " . gettype($companyEmail) . "\n";
echo "companyName type: " . gettype($companyName) . "\n";
echo "companyLogo type: " . gettype($companyLogo) . "\n";

echo "translate('please_') type: " . gettype(translate('please_')) . "\n";
echo "translate('Contact_Us') type: " . gettype(translate('Contact_Us')) . "\n";

try {
    $contact = new \App\Models\Contact();
    $contact->name = 'Test';
    $contact->email = 'test@example.com';
    $contact->mobile_number = '1234567890';
    $contact->subject = 'Test';
    $contact->message = 'Testing';
    $html = view('email-templates.contact_us', [
        'contact' => $contact,
        'type' => 'customer',
        // 'message' => new \Illuminate\Mail\Message(new \Symfony\Component\Mime\Email()) // Mailer sets this implicitly
    ])->render();
    echo "View Rendered Successfully!\n";
} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "File: " . $e->getFile() . "\n";
    // echo "Trace: " . $e->getTraceAsString() . "\n";
}
