import * as THREE from 'three';
import { OrbitControls } from 'three/addons/controls/OrbitControls.js';

export function initVehicle3D(container) {
  if (!container) return null;
  const scene = new THREE.Scene();
  const camera = new THREE.PerspectiveCamera(32, 1, .1, 100);
  camera.position.set(7.2, 4.1, 7.6);
  const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
  renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
  renderer.shadowMap.enabled = true;
  renderer.outputColorSpace = THREE.SRGBColorSpace;
  renderer.toneMapping = THREE.ACESFilmicToneMapping;
  container.appendChild(renderer.domElement);
  scene.add(new THREE.HemisphereLight(0xffffff, 0x687080, 2.2));
  const light = new THREE.DirectionalLight(0xffffff, 4.5);
  light.position.set(5, 8, 6); light.castShadow = true; scene.add(light);
  const floor = new THREE.Mesh(new THREE.CircleGeometry(7, 96), new THREE.MeshStandardMaterial({ color: 0xd8d9d9, roughness: .82 }));
  floor.rotation.x = -Math.PI / 2; floor.position.y = -1.03; floor.receiveShadow = true; scene.add(floor);

  const car = new THREE.Group(); car.rotation.y = -.2; scene.add(car);
  const paint = new THREE.MeshPhysicalMaterial({ color: 0xe2232e, metalness: .32, roughness: .22, clearcoat: 1 });
  const dark = new THREE.MeshStandardMaterial({ color: 0x111318, metalness: .5, roughness: .3 });
  const glass = new THREE.MeshPhysicalMaterial({ color: 0x182631, roughness: .12, transparent: true, opacity: .88 });
  const carbon = new THREE.MeshStandardMaterial({ color: 0x15171a, metalness: .65, roughness: .26 });
  const rim = new THREE.MeshStandardMaterial({ color: 0xbfc2c6, metalness: .95, roughness: .18 });
  const tire = new THREE.MeshStandardMaterial({ color: 0x090a0b, roughness: .88 });
  const add = (geometry, material, position) => {
    const object = new THREE.Mesh(geometry, material); object.position.set(...position);
    object.castShadow = true; object.receiveShadow = true; car.add(object); return object;
  };
  add(new THREE.BoxGeometry(4.75, .72, 1.86), paint, [0, -.18, 0]);
  add(new THREE.BoxGeometry(2.35, .64, 1.7), paint, [-.7, .43, 0]);
  add(new THREE.BoxGeometry(2.12, .58, 1.58), glass, [-.62, .78, 0]);
  add(new THREE.BoxGeometry(1.6, .16, 1.72), paint, [1.45, .25, 0]);
  const hood = add(new THREE.BoxGeometry(1.65, .08, 1.64), paint, [1.43, .66, 0]);
  add(new THREE.BoxGeometry(.12, .34, 1.72), dark, [2.38, -.05, 0]);
  const bodykit = [
    add(new THREE.BoxGeometry(1.05, .12, 2.06), dark, [2.2, -.72, 0]),
    add(new THREE.BoxGeometry(3.55, .1, .18), dark, [-.12, -.69, 1.01]),
    add(new THREE.BoxGeometry(3.55, .1, .18), dark, [-.12, -.69, -1.01]),
    add(new THREE.BoxGeometry(.55, .15, 2.02), dark, [-2.22, -.66, 0]),
  ];
  const spoiler = new THREE.Group();
  const wing = new THREE.Mesh(new THREE.BoxGeometry(.72, .08, 2.05), carbon); wing.position.set(-2.08, 1.05, 0); spoiler.add(wing);
  [-.62, .62].forEach(z => { const mount = new THREE.Mesh(new THREE.BoxGeometry(.08, .48, .08), carbon); mount.position.set(-1.92, .82, z); spoiler.add(mount); });
  car.add(spoiler);
  const wheels = [];
  [[1.48,.94],[1.48,-.94],[-1.55,.94],[-1.55,-.94]].forEach(([x,z]) => {
    const group = new THREE.Group();
    const tyreMesh = new THREE.Mesh(new THREE.CylinderGeometry(.57,.57,.3,40), tire); tyreMesh.rotation.x = Math.PI/2;
    const rimMesh = new THREE.Mesh(new THREE.CylinderGeometry(.39,.39,.315,12), rim); rimMesh.rotation.x = Math.PI/2;
    group.add(tyreMesh,rimMesh); group.position.set(x,-.63,z); car.add(group); wheels.push({group,rim:rimMesh});
  });
  const livery = new THREE.Group();
  [-.18,.18].forEach(z => { const stripe = new THREE.Mesh(new THREE.BoxGeometry(3.1,.025,.17),dark); stripe.position.set(.42,.755,z); livery.add(stripe); });
  car.add(livery);
  const controls = new OrbitControls(camera, renderer.domElement); controls.enableDamping=true; controls.enablePan=false; controls.minDistance=7; controls.maxDistance=14; controls.maxPolarAngle=Math.PI/2.05;
  const resize=()=>{const w=container.clientWidth,h=container.clientHeight;renderer.setSize(w,h,false);camera.aspect=w/Math.max(h,1);camera.updateProjectionMatrix();};
  new ResizeObserver(resize).observe(container); resize(); renderer.setAnimationLoop(()=>{controls.update();renderer.render(scene,camera);});
  return { update(chosen) {
    const choice=kind=>chosen.find(input=>input.dataset.kind===kind);
    paint.color.set(choice('color')?.dataset.color||'#e2232e');
    rim.color.set(choice('wheel')?.dataset.color||'#bfc2c6');
    const aero=choice('bodykit')?.dataset.visualActive==='1'; bodykit.forEach(part=>part.visible=aero); spoiler.visible=!aero;
    const lowered=choice('stance')?.dataset.visualActive==='1'; car.position.y=lowered?-.13:0; wheels.forEach(({group})=>group.position.y=lowered?-.54:-.63);
    hood.material=choice('hood')?.dataset.visualActive==='1'?carbon:paint;
    livery.visible=choice('livery')?.dataset.visualActive==='1';
  }};
}
