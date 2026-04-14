import { gsap } from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";

gsap.registerPlugin(ScrollTrigger);

export const initProgressBars = () => {
	const els = Array.from(document.querySelectorAll("[data-wp3d-progress]"));
	if (!els.length) return;

	for (const el of els) {
		ScrollTrigger.create({
			trigger: document.documentElement,
			start: "top top",
			end: "bottom bottom",
			scrub: true,
			onUpdate: (self) => {
				const pct = Math.max(0, Math.min(1, self.progress || 0)) * 100;
				el.style.setProperty("--wp3d-progress", `${pct}%`);
			},
		});
	}
};

