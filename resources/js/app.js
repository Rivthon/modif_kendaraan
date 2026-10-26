document.addEventListener('DOMContentLoaded', () => {
  const form = document.querySelector('[data-customizer]');
  if (!form) return;
  const base = Number(form.dataset.base || 0), total = document.querySelector('[data-total]');
  const body = document.querySelector('[data-vehicle-body]'), wheels = document.querySelectorAll('[data-wheel]');
  const spoiler = document.querySelector('[data-spoiler]'), accent = document.querySelector('[data-accent]');
  const realStack = document.querySelector('[data-real-vehicle]');
  const colorLayers = [...document.querySelectorAll('[data-color-layer]')];
  const modificationLayers = [...document.querySelectorAll('[data-mod-layer]')];
  let activeColorLayer = 0, photoRequest = 0;
  const rupiah = value => new Intl.NumberFormat('id-ID',{style:'currency',currency:'IDR',maximumFractionDigits:0}).format(value);
  form.querySelectorAll('.category').forEach((category, index) => {
    const inputs = category.querySelectorAll('input[name="items[]"]');
    inputs.forEach(input => input.name = `items[${index}]`);
    if (inputs[0]) inputs[0].checked = true;
  });
  const swapRealPhoto = src => {
    if (!src || !colorLayers.length || colorLayers[activeColorLayer].src === src) return;
    const request = ++photoRequest, nextIndex = activeColorLayer === 0 ? 1 : 0, next = colorLayers[nextIndex];
    const preload = new Image();
    preload.onload = () => {
      if (request !== photoRequest) return;
      next.src = src;
      requestAnimationFrame(() => {
        next.classList.add('is-visible');
        colorLayers[activeColorLayer].classList.remove('is-visible');
        activeColorLayer = nextIndex;
      });
    };
    preload.src = src;
  };
  const updateRealComposition = (chosen, changedInput = null) => {
    const color = chosen.find(input => input.dataset.layer === 'color');
    swapRealPhoto(changedInput?.dataset.image || color?.dataset.image);
    colorLayers.forEach(layer => { layer.style.filter = color?.dataset.colorFilter || 'none'; });

    modificationLayers.forEach(layer => {
      const choice = chosen.find(input => input.dataset.layer === layer.dataset.modLayer);
      const isActive = choice?.dataset.visualActive === '1';

      if (isActive && choice.dataset.image && layer instanceof HTMLImageElement && layer.src !== choice.dataset.image) layer.src = choice.dataset.image;
      if (layer.dataset.modLayer === 'wheel') layer.style.backgroundColor = choice?.dataset.color || 'transparent';
      layer.classList.toggle('is-visible', Boolean(isActive));
    });

    const stance = chosen.find(input => input.dataset.layer === 'stance');
    realStack?.classList.toggle('is-lowered', stance?.dataset.visualActive === '1');
  };
  const update = changedInput => {
    let sum = base, chosen = [];
    form.querySelectorAll('input[name^="items["]:checked').forEach(input => { sum += Number(input.dataset.price || 0); chosen.push(input); });
    total.textContent = rupiah(sum);
    const color = chosen.find(i => i.dataset.kind === 'color'); if (body && color) body.style.fill = color.dataset.color;
    updateRealComposition(chosen, changedInput);
    const wheel = chosen.find(i => i.dataset.kind === 'wheel'); wheels.forEach(el => { if (wheel) el.style.fill = wheel.dataset.color || '#202126'; });
    if (spoiler) spoiler.style.opacity = chosen.some(i => i.dataset.kind === 'spoiler') ? '1' : '0';
    if (accent) accent.style.opacity = chosen.some(i => i.dataset.kind === 'bodykit') ? '1' : '0';
  };
  form.addEventListener('change', event => update(event.target)); update();
});
