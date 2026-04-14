import { defineConfig } from "vite";

export default defineConfig({
	build: {
		outDir: "dist",
		emptyOutDir: true,
		rollupOptions: {
			input: "assets/js/index.js",
			output: {
				entryFileNames: "wp3d-scroll.js",
				chunkFileNames: "wp3d-scroll-[hash].js",
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

