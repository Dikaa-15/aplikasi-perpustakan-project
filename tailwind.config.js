/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["/perpustakaan-jp/App/Views/  /**/*.{html,js}"],
  theme: {
    extend: {
      fontFamily: {
        fontMain: "Manrope",
      },
      colors: {
        main: "#3E00FA",
        grey: "#404852",
        pink: "#FA003F",
        pinkSec: "#ECE6FF",
        secondary: "#B3B6BA",
        greenyellow: "#FDE616",
        secondaryBrigt: "#00FABB",
        inputColors: "#F3F4F8",
        primaryBlue: "#784DFC",
        pinkButton: "#FC4D79",
        greens: "#00FABB",
        fontColor: "#797F86",
      },
    },
  },
  // plugins: [(module.exports = { plugins: [require("@tailwindcss/forms")] })],
};
