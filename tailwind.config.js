/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js"
    ],
    theme: {
        extend: {
            colors: {
                "black": "#060606"
            },
            fontFamily: {
                "hanken-grotesk": ["Hanken Grotesk", "sans-serif"]
            },
            fontSize: {
                "2xs": ".625rem" // 10px
            },
            // keyframes: {
            //     dash: {
            //       '0%': { backgroundPosition: '0% 100%' },
            //       '100%': { backgroundPosition: '100% 100%' },
            //     },
            //   },
            //   animation: {
            //     dash: 'dash 2s linear infinite',
            //   },
              
        },
    },
    plugins: [],
}

