/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                bo6: {
                    DEFAULT: "#e38535",
                    50: "#fdf6ed",
                    100: "#f8e4cd",
                    200: "#f1c796",
                    300: "#e9a560",
                    400: "#e38535",
                    500: "#dc6824",
                    600: "#c24a1d",
                    700: "#a2321b",
                    800: "#84281c",
                    900: "#6d221a",
                    950: "#3e0e0a",
                },
                mwiii: {
                    DEFAULT: "#d70000",
                    50: "#fff0f0",
                    100: "#ffdddd",
                    200: "#ffc1c1",
                    300: "#ff9595",
                    400: "#ff5959",
                    500: "#ff2626",
                    600: "#fc0606",
                    700: "#d70000",
                    800: "#af0505",
                    900: "#900c0c",
                    950: "#500000",
                },
            },
        },
    },
    plugins: [],
};
