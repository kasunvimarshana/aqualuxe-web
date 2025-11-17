// Main JavaScript file for the AquaLuxe theme.

import Alpine from "alpinejs";
import { gsap } from "gsap";

window.Alpine = Alpine;
Alpine.start();

console.log("AquaLuxe theme JS loaded.");

// Example GSAP animation
gsap.to(".site-title", { duration: 1, x: 50, ease: "bounce" });

// Import module scripts
import "../../../modules/dark-mode/assets/js/dark-mode.js";
