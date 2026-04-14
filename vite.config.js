import { defineConfig } from "vite";

export default defineConfig({
	build: {
		outDir: "dist",
		emptyOutDir: true,
		rollupOptions: {
			input: {
				bootstrap: "assets/js/bootstrap.js",
				main: "assets/js/index.js",
			},
			output: {
				entryFileNames: (chunkInfo) => {
					if (chunkInfo.name === "bootstrap") return "wp3d-scroll-bootstrap.js";
					if (chunkInfo.name === "main") return "wp3d-scroll.js";
					return "wp3d-scroll-[name].js";
				},
				chunkFileNames: "wp3d-scroll-[name]-[hash].js",
				assetFileNames: (assetInfo) => {
					if (assetInfo.name && assetInfo.name.endsWith(".css")) {
						return "wp3d-scroll.css";
					}
					return "wp3d-scroll-[hash][extname]";
				},
			},
		},
	},
});
