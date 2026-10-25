document.addEventListener('DOMContentLoaded', () => {
  const form = document.querySelector('[data-customizer]');
  if (!form) return;
  const base = Number(form.dataset.base || 0), total = document.querySelector('[data-total]');
  const body = document.querySelector('[data-vehicle-body]'), wheels = document.querySelectorAll('[data-wheel]');
  const spoiler = document.querySelector('[data-spoiler]'), accent = document.querySelector('[data-accent]');
  const rupiah = value => new Intl.NumberFormat('id-ID',{style:'currency',currency:'IDR',maximumFractionDigits:0}).format(value);
  form.querySelectorAll('.category').forEach((category, index) => {
    const inputs = category.querySelectorAll('input[name="items[]"]');
    inputs.forEach(input => input.name = `items[${index}]`);
    if (inputs[0]) inputs[0].checked = true;
  });
  const update = () => {
    let sum = base, chosen = [];
    form.querySelectorAll('input[name^="items["]:checked').forEach(input => { sum += Number(input.dataset.price || 0); chosen.push(input); });
    total.textContent = rupiah(sum);
    const color = chosen.find(i => i.dataset.kind === 'color'); if (body && color) body.style.fill = color.dataset.color;
    const wheel = chosen.find(i => i.dataset.kind === 'wheel'); wheels.forEach(el => { if (wheel) el.style.fill = wheel.dataset.color || '#202126'; });
    if (spoiler) spoiler.style.opacity = chosen.some(i => i.dataset.kind === 'spoiler') ? '1' : '0';
    if (accent) accent.style.opacity = chosen.some(i => i.dataset.kind === 'bodykit') ? '1' : '0';
  };
  form.addEventListener('change', update); update();
});
