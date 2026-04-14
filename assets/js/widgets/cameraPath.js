import * as THREE from "three";
import { applyScrollBridge } from "../bridge/scrollBridge";

const parsePoints = (raw) => {
	try {
		const arr = JSON.parse(raw || "[]");
		if (!Array.isArray(arr)) return [];
		return arr
			.map((p) => ({
				x: Number(p.x),
				y: Number(p.y),
				z: Number(p.z),
			}))
			.filter((p) => Number.isFinite(p.x) && Number.isFinite(p.y) && Number.isFinite(p.z));
	} catch {
		return [];
	}
};

export const initCameraPath = async ({ el, camera }) => {
	const fovAttr = el.getAttribute("data-wp3d-camera-fov");
	if (fovAttr) {
		const fov = parseInt(fovAttr, 10);
		if (Number.isFinite(fov)) {
			camera.fov = fov;
			camera.updateProjectionMatrix();
		}
	}

	const pts = parsePoints(el.getAttribute("data-wp3d-points") || "[]");
	const curve = new THREE.CatmullRomCurve3(
		pts.length
			? pts.map((p) => new THREE.Vector3(p.x, p.y, p.z))
			: [new THREE.Vector3(0, 0, 6), new THREE.Vector3(0, 0, 2), new THREE.Vector3(2, 1, 0)]
	);

	const pin = el.getAttribute("data-wp3d-pin") === "1";
	const pinSpacing = el.getAttribute("data-wp3d-pin-spacing") === "1";

	const kill = applyScrollBridge({
		el,
		pin,
		pinSpacing,
		onProgress: (p) => {
			const pos = curve.getPointAt(p);
			camera.position.copy(pos);
			camera.lookAt(0, 0, 0);
		},
	});

	return () => {
		kill && kill();
	};
};

