<!doctype html>

<?php use Illuminate\Support\Facades\Session; use App\Models\SocialMedia; $companyPhone = getWebConfig(name:
'company_phone'); $companyEmail = getWebConfig(name: 'company_email'); $companyName = getWebConfig(name:
'company_name'); $companyLogo = getWebConfig(name: 'company_web_logo'); $lang = \App\Utils\Helpers::default_lang();
$direction = Session::get('direction'); /* SAFE VALUES */ $name = $contact->name ?? ''; $email = $contact->email ?? '';
$mobile = $contact->mobile_number ?? ''; $subject = $contact->subject ?? ''; $message = $contact->message ?? ''; /*
SOCIAL MEDIA */ $social_media = SocialMedia::where('active_status', 1)->get(); ?>

<html lang="{{ $lang }}" class="{{ $direction === 'rtl' ? 'active' : '' }}">
    <head>
        <meta charset="UTF-8" />
        <title>Contact Us</title>

        <style>
            body {
                margin: 0;
                font-family: Arial, Helvetica, sans-serif;
                font-size: 13px;
                background: #f7fbff;
            }

            .main-table {
                width: 500px;
                background: #ffffff;
                margin: 0 auto;
                padding: 40px;
            }

            .mail-img-1 {
                width: 100%;
                height: 136px;
                object-fit: contain;
            }

            .mail-img-2 {
                width: 100%;
                height: 45px;
                object-fit: contain;
            }

            .text-center {
                text-align: center;
            }

            hr {
                border-color: rgba(0, 170, 109, 0.3);
                margin: 16px 0;
            }
        </style>
    </head>

    <body style="background-color: #e9ecef; padding: 15px">
        <table dir="{{ $direction }}" class="main-table">
            <tbody>
                <tr>
                    <td>
                        <!-- HEADER IMAGE -->

                        <!-- <img
                            class="mail-img-1"
                            src="{{ dynamicAsset(path: 'public/assets/back-end/img/customer-registration.png') }}"
                            alt=""
                        /> -->

                        <!-- TITLE -->

                        <h2 class="text-center">
                            {{ $type == 'admin' ? 'New Contact Request Received' : 'Thank You for Contacting Us' }}
                        </h2>

                        <!-- GREETING -->

                        <h3>{{ translate('Hi') }} {{ $type == 'admin' ? 'Admin' : $name }},</h3>

                        @if($type == 'customer')

                        <p>
                            Thank you for contacting us. We have received your message and our support team will get
                            back to you shortly.
                        </p>

                        @endif

                        <!-- CONTACT DETAILS -->

                        <div style="border: 1px solid #e5e7eb; padding: 15px; background: #f9fafb; border-radius: 6px">
                            <strong>Name:</strong>
                            {{ $name }}

                            <br /><br />

                            <strong>Email:</strong>
                            {{ $email }}

                            <br /><br />

                            <strong>Mobile:</strong>
                            {{ $mobile }}

                            <br /><br />

                            <strong>Subject:</strong>
                            {{ $subject }}

                            <br /><br />

                            <strong>Message:</strong> <br />

                            {{ $message }}
                        </div>

                        <br />

                        @if($type == 'admin')

                        <p>A new contact request has been submitted through the website.</p>

                        @endif

                        <hr />

                        <p>
                            {{ translate('please_') }}

                            <a href="{{ route('contacts') }}" target="_blank"> {{ translate('_contact_us') }} </a>

                            {{ translate('_for_any_queries') }}, {{ translate('_we_are_always_happy_to_help') }}.
                        </p>

                        <p>{{ translate('Thanks_&_Regards') }},</p>

                        <p>{{ $companyName }}</p>
                    </td>
                </tr>

                <!-- FOOTER -->

                <tr>
                    <td>
                        <?php /*<img
                            class="mail-img-2"
                            src="{{ getStorageImages(path: $companyLogo, type: 'backend-logo') }}"
                            alt=""
                        />

                        <div style="text-align: center; margin-top: 10px">
                            <a href="{{ route('privacy-policy') }}"> {{ translate('Privacy_Policy') }} </a>

                            |

                            <a href="{{ route('contacts') }}"> {{ translate('Contact_Us') }} </a>
                        </div>

                        @if($social_media->count() > 0)

                        <div style="text-align: center; margin-top: 15px">
                            @foreach ($social_media as $social)

                            <a href="{{ $social->link }}" style="margin: 0 5px">
                                <img
                                    src="{{ dynamicAsset(path: 'public/assets/back-end/img/'.$social->name.'.png') }}"
                                    width="24"
                                />
                            </a>

                            @endforeach
                        </div>

                        @endif */?>

                        <div style="text-align: center; margin-top: 10px">
                            {{ translate('All_copy_right_reserved') }}, {{ date('Y') }} {{ $companyName }}
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </body>
</html>
