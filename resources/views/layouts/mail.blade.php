<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="x-apple-disable-message-reformatting">
    <title>{{ $title }}</title>
    <!--[if mso]>
    <xml>
        <o:OfficeDocumentSettings>
            <o:AllowPNG/>
            <o:PixelsPerInch>96</o:PixelsPerInch>
        </o:OfficeDocumentSettings>
    </xml>
    <style type="text/css">
        table {border-collapse: collapse;}
        .button-table { padding: 8px 0; }
    </style>
    <![endif]-->
    <style type="text/css">
        /* Reset de base pour éviter les espacements surprises */
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
        table { border-collapse: collapse !important; }
        body { height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important; background-color: #f7f7f7; }
        
        /* Styles Responsive */
        @media screen and (max-width: 600px) {
            .container { width: 100% !important; border-radius: 0 !important; }
            .content-padding { padding: 20px !important; }
        }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #f7f7f7;">
    <div role="article" aria-roledescription="email" lang="fr" style="background-color: #f7f7f7;">
        
        <!-- Conteneur global centré -->
        <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color: #f7f7f7;">
            <tr>
                <td align="center" style="padding: 0 10px;">
                    
                    <!--[if mso]>
                    <table role="presentation" align="center" style="width:600px;">
                    <tr>
                    <td>
                    <![endif]-->
                    
                    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" class="container" style="max-width: 600px; margin: 0 auto;">
                        
                        <!-- Header -->
                        <tr>
                            <td align="center" style="padding: 30px 0 15px;">
                                <p style="margin: 0; font-family: Arial, sans-serif; font-size: 16px; color: #333333; font-weight: bold;">
                                    {{ config('app.name') }}
                                </p>
                            </td>
                        </tr>

                        <!-- Main Content Card -->
                        <tr>
                            <td style="background-color: #ffffff; border: 1px solid #e1e1e1; border-radius: 8px; padding: 30px;" class="content-padding">
                                <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
                                    <tr>
                                        <td style="font-family: Arial, sans-serif; font-size: 16px; line-height: 24px; color: #333333;">
                                            {{ $slot }}
                                        </td>
                                    </tr>

                                    <!-- Bouton CTA -->
                                    @if (isset($url))
                                    <tr>
                                        <td align="center" style="padding: 30px 0 0;">
                                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="border-collapse: separate !important;">
                                                <tr>
                                                    <td align="center" bgcolor="#068236" style="border-radius: 4px;">
                                                        <a href="{{ $url }}" target="_blank"
                                                            style="display: inline-block; padding: 12px 25px; font-family: Arial, sans-serif; font-size: 14px; color: #ffffff; text-decoration: none; font-weight: bold; border-radius: 4px; border: 1px solid #068236;">
                                                            {{ __($buttonText) }}
                                                        </a>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    @endif

                                    <!-- Section Footer Interne -->
                                    @if (!empty($footer))
                                    <tr>
                                        <td style="padding-top: 30px;">
                                            <hr style="border: 0; border-top: 1px solid #e1e1e1; margin: 0 0 20px 0;">
                                            <p style="font-family: Arial, sans-serif; font-size: 12px; color: #888888; text-align: center; margin: 0;">
                                                {{ $footer }}
                                            </p>
                                        </td>
                                    </tr>
                                    @endif
                                </table>
                            </td>
                        </tr>

                        <!-- Footer global -->
                        <tr>
                            <td align="center" style="padding: 30px 0;">
                                <p style="margin: 0; font-family: Arial, sans-serif; font-size: 12px; color: #888888; line-height: 18px;">
                                    &copy; {{ date('Y') }} {{ config('app.name') }}. {{ __('All rights reserved') }}.
                                </p>
                            </td>
                        </tr>

                    </table>
                    
                    <!--[if mso]>
                    </td>
                    </tr>
                    </table>
                    <![endif]-->

                </td>
            </tr>
        </table>
    </div>
</body>
</html>