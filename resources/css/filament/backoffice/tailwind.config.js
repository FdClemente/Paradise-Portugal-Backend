/** @type {import('tailwindcss').Config} */

import preset from '../../../../vendor/filament/filament/tailwind.config.preset'
import colors from "tailwindcss/colors.js";

export default {
    presets: [preset],
    content: [
        './app/Filament/**/*.php',
        './resources/views/filament/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
        './vendor/diogogpinto/filament-auth-ui-enhancer/resources/**/*.blade.php',
        './vendor/guava/calendar/resources/**/*.blade.php',
    ],
    theme: {
        extend: {
            colors: {
                ...colors,
                primary: {
                    DEFAULT:'#ff5733'
                },
            },
        },
    }
}
