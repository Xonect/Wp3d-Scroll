import "../css/styles.css";

const ready = () =>
	new Promise((resolve) => {
		if (document.readyState === "loading") {
			document.addEventListener("DOMContentLoaded", resolve, { once: true });
			return;
		}
		resolve();
	});

const hasWidgets = () => {
	return (
		!!document.querySelector("[data-wp3d-scene]") ||
		!!document.querySelector("[data-wp3d-progress]")
	);
};

ready().then(async () => {
	if (!hasWidgets()) return;

	const mainUrl = window.wp3dScrollSettings && window.wp3dScrollSettings.mainUrl;
	if (mainUrl) {
		await import(/* @vite-ignore */ mainUrl);
		return;
	}

	await import("./index.js");
});

