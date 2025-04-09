<?php
return [
    'refund' => [
        'title' => 'Remboursement confirmé',
        'body' => 'Votre remboursement a été confirmé. Vous recevrez le montant sur votre compte dans quelques jours.',
        'email' => [
            'title' => 'Remboursement confirmé',
            'greeting' => 'Bonjour :name,',
            'body' => 'Nous sommes heureux de vous informer que votre remboursement a été confirmé ! Le montant sera crédité sur votre compte dans les prochains jours. Vous pouvez suivre le statut du remboursement directement dans l’application.',
            'signature' => 'Merci d’utiliser notre service !',
            'salutation' => 'Cordialement,',
            'button' => 'Vérifier le statut du remboursement dans l’application',
            'footer' => 'Si vous avez des questions, n’hésitez pas à nous contacter.',
        ]
    ],
    'reminder' => [
        'title' => 'Votre séjour commence demain – soyez prêt !',
        'body' => 'Besoin d’aide pour vous y rendre ? Ouvrez l’application pour les instructions et l’itinéraire.',
        'email' => [
            'subject' => 'Votre séjour à :property commence demain !',
            'greeting' => 'Bonjour :name,',
            'intro' => 'Nous avons hâte de vous accueillir demain à **:property**.',
            'details' => 'Voici les principales informations :',
            'checkin_date' => '🗓️ **Date d’arrivée :** :date',
            'checkin_time' => '🕒 **Heure d’arrivée :** à partir de :time',
            'address' => '📍 **Adresse :** :address',
            'button' => 'Voir les instructions d’arrivée',
            'footer' => 'Si vous avez des questions, n’hésitez pas à nous contacter.',
            'salutation' => 'Bon séjour ! 🌅',
        ]
    ],
    'booking_created' => [
        'email' => [
            'subject' => 'Votre réservation à :property est confirmée !',
            'greeting' => 'Bonjour :name,',
            'intro' => 'Votre réservation à **:property** a bien été enregistrée.',
            'details' => 'Voici les principales informations de votre réservation :',
            'checkin_date' => '🗓️ **Date d’arrivée :** :date',
            'checkin_time' => '🕒 **Heure d’arrivée :** à partir de :time',
            'address' => '📍 **Adresse :** :address',
            'button' => 'Voir les détails de la réservation',
            'footer' => 'Besoin d’aide ou des questions ? Nous sommes là pour vous.',
            'salutation' => 'Au plaisir de vous accueillir ! 🏡',
        ],
    ],
    'password_reset' => [
        'email' => [
            'subject' => 'Demande de réinitialisation de mot de passe',
            'greeting' => 'Bonjour :name,',
            'intro' => 'Nous avons reçu une demande de réinitialisation de votre mot de passe.',
            'code' => '🔐 **Votre code de réinitialisation est :** :code',
            'expiry' => 'Ce code est valable pendant 1 heure à compter de l’envoi de cet email.',
            'salutation' => 'Si vous n’êtes pas à l’origine de cette demande, ignorez simplement ce message.',
        ],
    ],
];
