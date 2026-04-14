# Wp3d-Scroll

WordPress + Elementor plugin scaffold for scroll-driven 3D scenes using GSAP ScrollTrigger and Three.js.

## Build (JS/CSS)

```bash
npm install
npm run build
```

The plugin enqueues `dist/wp3d-scroll-bootstrap.js` and `dist/wp3d-scroll.css`. The heavy bundle loads only if widgets exist on the page.

## Install in WordPress

Copy this repo folder to:

`wp-content/plugins/wp3d-scroll/`

Activate **Wp3D Scroll**.

## Admin Settings

- Top-level menu: **Wp3D Scroll**
- Also available under **Settings → Wp3D Scroll**
- Also appears under **Elementor** menu when Elementor is active

## Elementor Widgets (v0.1)

- 3D Scene Container
- Model Viewer
- Scroll Camera Path
- Scroll Progress Bar
