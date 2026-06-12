---
name: room-homepage
description: |
  Reference skill from the room-homepage Frontend Mentor project. A minimalist,
  responsive landing page with a content carousel, mobile slide-in navigation,
  and a 3-column grid about section. Use as inspiration for elegant, clean web
  layouts with interactive hero sections.
compatibility:
  - HTML5
  - CSS3 (Grid, Flexbox, Keyframes)
  - JavaScript (vanilla)
  - SCSS
---

# Room Homepage — Reference Skill

## Core Design Patterns

### Hero Split Layout (Grid)
```
header {
  display: grid;
  grid-template-columns: 2fr 1fr;  /* image 2/3, text 1/3 */
}
```
Image takes 60vw × 60vh with `object-fit: fill`. Text panel vertically centers content with `flex-direction: column; justify-content: center`.

### Content Carousel (Slide + Swap)
1. On click, slide image to `translateX(-100%)` with 300ms transition
2. After 300ms timeout, swap `img.src` and reset `transform: translateX(0)` without transition
3. Track index with modulo: `count = (count + 1) % texts.length`

```js
function changeImage(direction) {
  imgMain.style.transition = 'transform 0.3s ease-in';
  count = (count + 1) % texts.length; // or -1 + length
  imgMain.style.transform = 'translateX(-100%)';
  setTimeout(() => {
    imgMain.src = `./images/desktop-image-hero-${count + 1}.jpg`;
    title.textContent = texts[count].title;
    description.textContent = texts[count].description;
    imgMain.style.transition = 'none';
    imgMain.style.transform = 'translateX(0)';
  }, 300);
}
```

### Angle Navigation Overlap
Position the prev/next buttons so they visually overlap both the image and text columns:
```css
.angle {
  position: absolute;
  bottom: 0;
  left: -7.3em; /* negative margin pulls into image column */
}
.angle__left, .angle__right {
  padding: 1.5em 2.05em;
  background: #000;
  transition: background 0.3s ease;
}
.angle__left:hover, .angle__right:hover {
  background: hsl(0, 0%, 27%);
}
```

### Mobile Slide-in Menu
CSS keyframes for slide-in/slide-out:
```css
@keyframes slideIn {
  from { transform: translateX(100%); opacity: 0; }
  to   { transform: translateX(0); opacity: 1; }
}
@keyframes slideOut {
  from { transform: translateX(0); opacity: 1; }
  to   { transform: translateX(100%); opacity: 0; }
}
/* Toggle classes via JS — active for show, closing for hide */
```

JS toggle with timeout matching animation duration:
```js
menuMobile.addEventListener("click", () => {
  logo.classList.add("disabled");
  menu.classList.add("active");
});
closeMenu.addEventListener("click", () => {
  menu.classList.add('closing');
  setTimeout(() => {
    menu.classList.remove('active', 'closing');
    logo.classList.remove('disabled');
  }, 300);
});
```

### Nav Link Hover Underline
```css
.menu__item a {
  position: relative;
}
.menu__item a::after {
  content: "";
  position: absolute;
  left: 0; bottom: -0.8em;
  width: 0; height: 2px;
  background: #fff;
  transition: width 0.3s ease;
}
.menu__item a:hover::after {
  width: 100%;
}
```

### 3-Column About Grid
```css
.about {
  display: grid;
  grid-template-columns: 600px 630px 600px; /* image | text | image */
  justify-content: space-between;
}
```
Side columns use `display: flex; align-items: stretch` so images fill container height.

### Button with Arrow via CSS
```css
.btn::after {
  content: url("../images/icon-arrow.svg");
  display: inline-block;
  margin-left: 0.8em;
}
.btn:hover {
  transform: translateX(0.8em);
}
```

## Color Palette (Monochromatic)
- Dark Gray: `hsl(0, 0%, 63%)` (body text)
- Black: `hsl(0, 0%, 0%)` (headings)
- White: `hsl(0, 0%, 100%)` (background, nav links)
- Very Dark Gray: `hsl(0, 0%, 27%)` (hover states)

## Typography
- Font: `'League Spartan', serif`
- H1: 3rem / 600 weight
- Body: 16px / `hsl(0, 0%, 63%)`
- Button: 1.1rem / 600 / letter-spacing 0.8em / uppercase
- Section subtitle: 1.2rem / letter-spacing 5px / uppercase

## Responsive Breakpoints
- `<=440px`: Full mobile redesign — overlay nav, single column
- `476px–769px`: Tablet — single column, repositioned angles
- `1439px–1919px`: Large desktop — subtle size adjustments

## Key Techniques to Steal
1. Carousel slide illusion (translateX + setTimeout swap)
2. Angle buttons overlapping grid columns (negative positioning)
3. Mobile menu with matching JS/CSS animation timing (300ms)
4. Stretch image containers (`display: flex; align-items: stretch`)
5. Arrow icon as CSS `::after` content on buttons
6. Nav underline hover with `::after` pseudo-element
