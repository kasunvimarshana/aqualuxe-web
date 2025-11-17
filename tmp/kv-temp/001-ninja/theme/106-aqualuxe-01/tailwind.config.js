module.exports = {
	content: [
		"./*.php",
		"./inc/**/*.php",
		"./templates/**/*.php",
		"./modules/**/*.php",
	],
	darkMode: "class",
	theme: {
		extend: {
			colors: {
				brand: "#0a6faa",
				accent: "#00b4d8",
				dark: "#0a0f14",
			},
		},
	},
	plugins: [
		require("@tailwindcss/forms"),
		require("@tailwindcss/typography"),
	],
};
