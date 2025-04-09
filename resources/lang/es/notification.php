<?php
return [
    'refund' => [
        'title' => 'Reembolso confirmado',
        'body' => 'Tu reembolso ha sido confirmado. Recibirás el importe en tu cuenta en unos días.',
        'email' => [
            'title' => 'Reembolso confirmado',
            'greeting' => 'Hola :name,',
            'body' => '¡Nos complace informarte que tu reembolso ha sido confirmado! El importe será abonado en tu cuenta en los próximos días. Puedes consultar el estado del reembolso directamente en la app.',
            'signature' => '¡Gracias por utilizar nuestro servicio!',
            'salutation' => 'Un cordial saludo,',
            'button' => 'Consultar estado del reembolso en la app',
            'footer' => 'Si tienes alguna pregunta, no dudes en contactarnos.',
        ]
    ],
    'reminder' => [
        'title' => 'Tu estancia comienza mañana – ¡prepárate!',
        'body' => '¿Necesitas ayuda para llegar? Abre la app para ver las instrucciones y la dirección.',
        'email' => [
            'subject' => '¡Tu estancia en :property comienza mañana!',
            'greeting' => 'Hola :name,',
            'intro' => 'Estamos deseando darte la bienvenida mañana en **:property**.',
            'details' => 'Aquí tienes los detalles principales:',
            'checkin_date' => '🗓️ **Fecha de llegada:** :date',
            'checkin_time' => '🕒 **Hora de llegada:** desde las :time',
            'address' => '📍 **Dirección:** :address',
            'button' => 'Ver instrucciones de llegada',
            'footer' => 'Si tienes alguna pregunta, estamos aquí para ayudarte.',
            'salutation' => '¡Que tengas una estancia estupenda! 🌅',
        ]
    ],
    'booking_created' => [
        'email' => [
            'subject' => '¡Tu reserva en :property está confirmada!',
            'greeting' => 'Hola :name,',
            'intro' => 'Tu reserva en **:property** ha sido creada con éxito.',
            'details' => 'Aquí están los detalles principales de tu reserva:',
            'checkin_date' => '🗓️ **Fecha de llegada:** :date',
            'checkin_time' => '🕒 **Hora de llegada:** desde las :time',
            'address' => '📍 **Dirección:** :address',
            'button' => 'Ver detalles de la reserva',
            'footer' => '¿Necesitas ayuda o tienes preguntas? Estamos aquí para ayudarte.',
            'salutation' => '¡Esperamos darte la bienvenida! 🏡',
        ],
    ],
    'password_reset' => [
        'email' => [
            'subject' => 'Solicitud de restablecimiento de contraseña',
            'greeting' => 'Hola :name,',
            'intro' => 'Hemos recibido una solicitud para restablecer la contraseña de tu cuenta.',
            'code' => '🔐 **Tu código de restablecimiento es:** :code',
            'expiry' => 'Este código es válido durante 1 hora desde el envío de este correo.',
            'salutation' => 'Si no solicitaste esto, puedes ignorar este mensaje.',
        ],
    ],
];
