/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",

        "./packages/Webkul/Shop/src/Resources/views/**/*.blade.php",
        "./packages/Webkul/Shop/src/Resources/**/*.js",
        "./packages/Webkul/Admin/src/Resources/views/**/*.blade.php",
        "./packages/Webkul/Installer/src/Resources/views/**/*.blade.php",
    ],

    theme: {
        container: {
            center: true,
            screens: {
                "2xl": "1440px",
            },
            padding: {
                DEFAULT: "90px",
            },
        },
        screens: {
            sm: "525px",
            md: "768px",
            lg: "1024px",
            xl: "1240px",
            "2xl": "1440px",
            1180: "1180px",
            1060: "1060px",
            991: "991px",
            868: "868px",
        },
        extend: {
            colors: {
                navyBlue: "#060C3B",
                lightOrange: "#F6F2EB",
                darkGreen: "#40994A",
                darkBlue: "#0044F2",
                darkPink: "#F85156",
            },
            fontFamily: {
                poppins: ["Poppins", "sans-serif"],
                dmserif: ["DM Serif Display", "serif"],
            },
        },
    },

    plugins: [],

    safelist: [
        "min-h-[50px]",
        "min-h-[120px]",
        { pattern: /icon-/ },
        "mobile-menu",
        "mm-spn--light",
        "mm-spn--navbar",
        "mm-spn--main",
        "btn--close",
        "sicon-cancel",
    ],
};
