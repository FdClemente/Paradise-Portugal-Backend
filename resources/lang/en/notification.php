<?php
return [
    'refund' => [
        'title' => 'Refunded confirmed',
        'body' => 'Your refund has been confirmed. You will receive the amount in your account within a few days.',
        'email' => [
            'title' => 'Refund Confirmed',
            'greeting' => 'Hello :name,',
            'body' => 'We are happy to inform you that your refund has been confirmed! The amount will be credited to your account within a few days. You can check the status of your refund directly in the app.',
            'signature' => 'Thank you for using our service!',
            'salutation' => 'Best regards,',
            'button' => 'Check refund status in the app',
            'footer' => 'If you have any questions, don’t hesitate to reach out to us.',
        ]
    ],
    'reminder' => [
        'title' => 'Your stay starts tomorrow – get ready!',
        'body' => 'Need help getting there? Open the app for directions and check-in instructions.',
        'email' => [
            'subject' => 'Your stay at :property starts tomorrow!',
            'greeting' => 'Hi :name,',
            'intro' => 'We’re looking forward to welcoming you tomorrow for your stay at **:property**.',
            'details' => 'Here are the main details:',
            'checkin_date' => '🗓️ **Check-in Date:** :date',
            'checkin_time' => '🕒 **Check-in Time:** from :time',
            'address' => '📍 **Address:** :address',
            'button' => 'View Check-in Instructions',
            'footer' => 'If you have any questions, feel free to contact us.',
            'salutation' => 'Have a great stay! 🌅',
        ]
    ],
    'booking_created' => [
        'email' => [
            'subject' => 'Your reservation at :property is confirmed!',
            'greeting' => 'Hi :name,',
            'intro' => 'Your reservation at **:property** has been successfully created.',
            'details' => 'Here are the main details of your booking:',
            'checkin_date' => '🗓️ **Check-in Date:** :date',
            'checkin_time' => '🕒 **Check-in Time:** from :time',
            'address' => '📍 **Address:** :address',
            'button' => 'View Reservation Details',
            'footer' => 'Need help or have questions? We’re here for you.',
            'salutation' => 'We look forward to hosting you! 🏡',
        ],
    ],
    'password_reset' => [
        'email' => [
            'subject' => 'Password Reset Request',
            'greeting' => 'Hi :name,',
            'intro' => 'We received a request to reset your account password.',
            'code' => '🔐 **Your reset code is:** :code',
            'expiry' => 'This code is valid for 1 hour from the time this email was sent.',
            'salutation' => 'If you did not request this, please ignore this message.',
        ],
    ],
];
