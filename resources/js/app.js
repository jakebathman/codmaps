import "./bootstrap";
import * as cheerio from "cheerio";
// import Alpine from "alpinejs";
import persist from "@alpinejs/persist";
import anchor from "@alpinejs/anchor";

// window.Alpine = Alpine;
window.cheerio = cheerio;

// Alpine.plugin(persist);
// Alpine.start();

Alpine.plugin(anchor);
