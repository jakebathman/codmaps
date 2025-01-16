import "./bootstrap";
import * as cheerio from "cheerio";
import Alpine from "alpinejs";
import persist from "@alpinejs/persist";

window.Alpine = Alpine;
window.cheerio = cheerio;

Alpine.plugin(persist);
Alpine.start();
