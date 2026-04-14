import * as THREE from "three";
import { gsap } from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import { applyScrollBridge } from "../bridge/scrollBridge";
import { initModelViewer } from "../widgets/modelViewer";
import { initCameraPath } from "../widgets/cameraPath";

gsap.registerPlugin(ScrollTrigger);

const prefersReducedMotion = () =>
	window.matchMedia &&
	window.matchMedia("(prefers-reduced-motion: reduce)").matches;

const isLowPowerDevice = () => {
	const hc = navigator.hardwareConcurrency || 0;
	const dm = navigator.deviceMemory || 0;
	return (hc && hc < 4) || (dm && dm < 4);
};

const hasWebGL2 = () => {
	try {
		const c = document.createElement("canvas");
		return !!(c.getContext("webgl2") || c.getContext("experimental-webgl2"));
	} catch {
		return false;
	}
};

const applyFallback = (el) => {
	const fallback = el.getAttribute("data-wp3d-fallback") || "";
	if (!fallback) return;
	el.classList.add("is-wp3d-fallback");
	el.style.backgroundImage = `url("${fallback}")`;
};

const sizeCanvas = (el, renderer, camera) => {
	const hAttr = el.getAttribute("data-wp3d-height");
	const height = hAttr ? parseInt(hAttr, 10) : null;
	if (height && Number.isFinite(height)) {
		el.style.height = `${height}px`;
	}

	const rect = el.getBoundingClientRect();
	const w = Math.max(1, Math.floor(rect.width));
	const h = Math.max(1, Math.floor(rect.height));

	renderer.setSize(w, h, false);
	camera.aspect = w / h;
	camera.updateProjectionMatrix();
};

const createBaseScene = (el, canvas) => {
	const bg = el.getAttribute("data-wp3d-bg-color") || "#000000";
	const renderer = new THREE.WebGLRenderer({
		canvas,
		antialias: true,
		alpha: false,
		powerPreference: "high-performance",
	});
	renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, 2));
	renderer.setClearColor(new THREE.Color(bg), 1);

	const scene = new THREE.Scene();

	const camera = new THREE.PerspectiveCamera(50, 1, 0.1, 100);
	camera.position.set(0, 0, 6);

	const ambient = new THREE.AmbientLight(0xffffff, 0.7);
	scene.add(ambient);

	const dir = new THREE.DirectionalLight(0xffffff, 0.8);
	dir.position.set(4, 6, 3);
	scene.add(dir);

	const geom = new THREE.BoxGeometry(1, 1, 1);
	const mat = new THREE.MeshStandardMaterial({ color: 0xdddddd });
	const cube = new THREE.Mesh(geom, mat);
	scene.add(cube);

	return { renderer, scene, camera, cube };
};

const initScene = async (el) => {
	const canvas = el.querySelector("canvas.wp3d-scroll-canvas");
	if (!canvas) return;

	const disableMobile = el.getAttribute("data-wp3d-disable-mobile") === "1";
	const isMobile = window.matchMedia && window.matchMedia("(max-width: 767px)").matches;

	if (prefersReducedMotion()) {
		applyFallback(el);
		return;
	}

	if (!hasWebGL2() || isLowPowerDevice() || (disableMobile && isMobile)) {
		applyFallback(el);
		return;
	}

	const { renderer, scene, camera, cube } = createBaseScene(el, canvas);

	const widget = el.getAttribute("data-wp3d-widget") || "scene";
	let cleanup = () => {};

	if (widget === "model-viewer") {
		cleanup = await initModelViewer({ el, scene, camera, renderer, cube });
	} else if (widget === "camera-path") {
		cleanup = await initCameraPath({ el, scene, camera, renderer, cube });
	} else {
		applyScrollBridge({
			el,
			onProgress: (p) => {
				cube.rotation.y = p * Math.PI * 2;
			},
		});
	}

	const render = () => {
		sizeCanvas(el, renderer, camera);
		renderer.render(scene, camera);
	};

	const onResize = () => render();
	window.addEventListener("resize", onResize, { passive: true });

	const ticker = () => render();
	gsap.ticker.add(ticker);

	el.dispatchEvent(
		new CustomEvent("wp3d:init", {
			bubbles: true,
			detail: { renderer, scene, camera, sceneId: el.getAttribute("data-wp3d-scene-id") || "" },
		})
	);

	return () => {
		try {
			window.removeEventListener("resize", onResize);
			gsap.ticker.remove(ticker);
			cleanup && cleanup();
			renderer.dispose();
		} catch {
		}
	};
};

export const initScenes = () => {
	const els = Array.from(document.querySelectorAll("[data-wp3d-scene]"));
	if (!els.length) return;

	const initialized = new WeakSet();

	const observer = new IntersectionObserver(
		(entries) => {
			for (const entry of entries) {
				if (!entry.isIntersecting) continue;
				const el = entry.target;
				if (initialized.has(el)) continue;
				initialized.add(el);
				initScene(el);
			}
		},
		{ root: null, rootMargin: "200px 0px", threshold: 0.01 }
	);

	for (const el of els) {
		observer.observe(el);
	}
};

