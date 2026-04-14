import * as THREE from "three";
import { applyScrollBridge } from "../bridge/scrollBridge";

const loadGltf = async (url) => {
	const mod = await import("three/addons/loaders/GLTFLoader.js");
	const loader = new mod.GLTFLoader();
	return new Promise((resolve, reject) => {
		loader.load(url, resolve, undefined, reject);
	});
};

export const initModelViewer = async ({ el, scene, cube, enableScroll = true }) => {
	const url = el.getAttribute("data-wp3d-model-url") || "";
	let model = null;

	if (url) {
		try {
			const gltf = await loadGltf(url);
			model = gltf.scene;
			scene.add(model);
			scene.remove(cube);
			el.dispatchEvent(
				new CustomEvent("wp3d:model:loaded", {
					bubbles: true,
					detail: { sceneId: el.getAttribute("data-wp3d-scene-id") || "", gltf },
				})
			);
		} catch (error) {
			el.dispatchEvent(
				new CustomEvent("wp3d:error", {
					bubbles: true,
					detail: { sceneId: el.getAttribute("data-wp3d-scene-id") || "", error },
				})
			);
		}
	}

	const target = model || cube;
	const preset = el.getAttribute("data-wp3d-preset") || "rotate";
	const axis = el.getAttribute("data-wp3d-rotation-axis") || "y";

	const applyAt = (p) => {
			if (preset === "rotate") {
				if (axis === "x") target.rotation.x = p * Math.PI * 2;
				else if (axis === "z") target.rotation.z = p * Math.PI * 2;
				else target.rotation.y = p * Math.PI * 2;
				return;
			}

			if (preset === "translate") {
				target.position.y = THREE.MathUtils.lerp(-1, 1, p);
				return;
			}

			if (preset === "scale") {
				const s = THREE.MathUtils.lerp(0.7, 1.3, p);
				target.scale.setScalar(s);
				return;
			}

			if (preset === "float") {
				target.position.y = Math.sin(p * Math.PI * 2) * 0.6;
			}
	};

	applyAt(0);

	if (!enableScroll) {
		return () => {};
	}

	const kill = applyScrollBridge({
		el,
		onProgress: (p) => applyAt(p),
	});

	return () => {
		kill && kill();
	};
};
