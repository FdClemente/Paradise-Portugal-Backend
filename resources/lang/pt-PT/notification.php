<?php
return [
    'refund' => [
        'title' => 'Reembolso confirmado',
        'body' => 'O teu reembolso foi confirmado. Irás receber o montante na tua conta dentro de alguns dias.',
        'email' => [
            'title' => 'Reembolso Confirmado',
            'greeting' => 'Olá :name,',
            'body' => 'Temos o prazer de te informar que o teu reembolso foi confirmado! O montante será creditado na tua conta nos próximos dias. Podes verificar o estado do reembolso diretamente na app.',
            'signature' => 'Obrigado por utilizares o nosso serviço!',
            'salutation' => 'Com os melhores cumprimentos,',
            'button' => 'Verificar estado do reembolso na app',
            'footer' => 'Se tiveres alguma dúvida, não hesites em entrar em contacto connosco.',
        ]
    ],
    'reminder' => [
        'title' => 'A tua estadia começa amanhã – prepara-te!',
        'body' => 'Precisas de ajuda para lá chegar? Abre a app para ver direções e instruções de check-in.',
        'email' => [
            'subject' => 'A tua estadia em :property começa amanhã!',
            'greeting' => 'Olá :name,',
            'intro' => 'Estamos ansiosos por te receber amanhã na tua estadia em **:property**.',
            'details' => 'Aqui estão os detalhes principais:',
            'checkin_date' => '🗓️ **Data de check-in:** :date',
            'checkin_time' => '🕒 **Hora de check-in:** a partir das :time',
            'address' => '📍 **Morada:** :address',
            'button' => 'Ver instruções de check-in',
            'footer' => 'Se tiveres dúvidas, estamos aqui para ajudar.',
            'salutation' => 'Boa estadia! 🌅',
        ]
    ],
    'booking_created' => [
        'email' => [
            'subject' => 'A tua reserva em :property foi confirmada!',
            'greeting' => 'Olá :name,',
            'intro' => 'A tua reserva em **:property** foi criada com sucesso.',
            'details' => 'Aqui estão os principais detalhes da tua reserva:',
            'checkin_date' => '🗓️ **Data de check-in:** :date',
            'checkin_time' => '🕒 **Hora de check-in:** a partir das :time',
            'address' => '📍 **Morada:** :address',
            'button' => 'Ver detalhes da reserva',
            'footer' => 'Precisas de ajuda ou tens perguntas? Estamos cá para ti.',
            'salutation' => 'Estamos à tua espera! 🏡',
        ],
    ],
    'password_reset' => [
        'email' => [
            'subject' => 'Pedido de reposição de palavra-passe',
            'greeting' => 'Olá :name,',
            'intro' => 'Recebemos um pedido para repor a palavra-passe da tua conta.',
            'code' => '🔐 **O teu código de reposição é:** :code',
            'expiry' => 'Este código é válido durante 1 hora a partir do envio deste email.',
            'salutation' => 'Se não foste tu a fazer este pedido, ignora esta mensagem.',
        ],
    ],
];
