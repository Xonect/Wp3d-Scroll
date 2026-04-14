import "../css/styles.css";
import { initScenes } from "./engine/scene";
import { initProgressBars } from "./widgets/progressBar";

const ready = () =>
	new Promise((resolve) => {
		if (document.readyState === "loading") {
			document.addEventListener("DOMContentLoaded", resolve, { once: true });
			return;
		}
		resolve();
	});

ready().then(() => {
	initScenes();
	initProgressBars();
});
