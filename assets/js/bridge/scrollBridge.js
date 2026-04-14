import { gsap } from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";

gsap.registerPlugin(ScrollTrigger);

export const applyScrollBridge = ({ el, onProgress, start, end, pin, pinSpacing }) => {
	const debug = new URLSearchParams(window.location.search).get("wp3d_debug") === "1";
	const markers =
		debug ||
		Number(window.wp3dScrollSettings?.debugMarkers ?? 0) === 1;

	const st = ScrollTrigger.create({
		trigger: el,
		start: start || el.getAttribute("data-wp3d-start") || "top bottom",
		end: end || el.getAttribute("data-wp3d-end") || "bottom top",
		scrub: true,
		pin: !!pin,
		pinSpacing: pinSpacing !== undefined ? !!pinSpacing : true,
		markers,
		onUpdate: (self) => {
			const p = self.progress || 0;
			onProgress && onProgress(p, self.direction || 1);
			el.dispatchEvent(
				new CustomEvent("wp3d:progress", {
					bubbles: true,
					detail: {
						sceneId: el.getAttribute("data-wp3d-scene-id") || "",
						progress: p,
						direction: self.direction || 1,
					},
				})
			);
		},
	});

	return () => {
		st && st.kill(true);
	};
};
