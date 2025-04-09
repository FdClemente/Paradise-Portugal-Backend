<?php
return [
    'refund' => [
        'title' => 'Rückerstattung bestätigt',
        'body' => 'Ihre Rückerstattung wurde bestätigt. Der Betrag wird in den nächsten Tagen auf Ihrem Konto gutgeschrieben.',
        'email' => [
            'title' => 'Rückerstattung bestätigt',
            'greeting' => 'Hallo :name,',
            'body' => 'Wir freuen uns, Ihnen mitteilen zu können, dass Ihre Rückerstattung bestätigt wurde! Der Betrag wird in den nächsten Tagen auf Ihr Konto überwiesen. Sie können den Status direkt in der App überprüfen.',
            'signature' => 'Vielen Dank für die Nutzung unseres Dienstes!',
            'salutation' => 'Mit freundlichen Grüßen,',
            'button' => 'Rückerstattungsstatus in der App prüfen',
            'footer' => 'Wenn Sie Fragen haben, zögern Sie bitte nicht, uns zu kontaktieren.',
        ]
    ],
    'reminder' => [
        'title' => 'Ihr Aufenthalt beginnt morgen – seien Sie bereit!',
        'body' => 'Brauchen Sie Hilfe bei der Anreise? Öffnen Sie die App für Wegbeschreibung und Check-in-Anweisungen.',
        'email' => [
            'subject' => 'Ihr Aufenthalt in :property beginnt morgen!',
            'greeting' => 'Hallo :name,',
            'intro' => 'Wir freuen uns, Sie morgen in **:property** begrüßen zu dürfen.',
            'details' => 'Hier sind die wichtigsten Details:',
            'checkin_date' => '🗓️ **Check-in-Datum:** :date',
            'checkin_time' => '🕒 **Check-in-Zeit:** ab :time',
            'address' => '📍 **Adresse:** :address',
            'button' => 'Check-in-Anweisungen anzeigen',
            'footer' => 'Bei Fragen stehen wir Ihnen gerne zur Verfügung.',
            'salutation' => 'Wir wünschen Ihnen einen tollen Aufenthalt! 🌅',
        ]
    ],
    'booking_created' => [
        'email' => [
            'subject' => 'Ihre Reservierung in :property ist bestätigt!',
            'greeting' => 'Hallo :name,',
            'intro' => 'Ihre Reservierung in **:property** wurde erfolgreich erstellt.',
            'details' => 'Hier sind die wichtigsten Buchungsdetails:',
            'checkin_date' => '🗓️ **Check-in-Datum:** :date',
            'checkin_time' => '🕒 **Check-in-Zeit:** ab :time',
            'address' => '📍 **Adresse:** :address',
            'button' => 'Reservierungsdetails anzeigen',
            'footer' => 'Brauchen Sie Hilfe oder haben Sie Fragen? Wir sind für Sie da.',
            'salutation' => 'Wir freuen uns darauf, Sie zu empfangen! 🏡',
        ],
    ],
    'password_reset' => [
        'email' => [
            'subject' => 'Passwort-Zurücksetzung angefordert',
            'greeting' => 'Hallo :name,',
            'intro' => 'Wir haben eine Anfrage zur Zurücksetzung Ihres Passworts erhalten.',
            'code' => '🔐 **Ihr Zurücksetzungscode lautet:** :code',
            'expiry' => 'Dieser Code ist eine Stunde lang gültig ab dem Zeitpunkt des Versands dieser E-Mail.',
            'salutation' => 'Wenn Sie dies nicht angefordert haben, ignorieren Sie bitte diese Nachricht.',
        ],
    ],
];
