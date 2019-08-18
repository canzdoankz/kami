<!DOCTYPE html>
<html lang="en">
	<head>
		<title>KM001-GENKAN HOUSE</title>
	</head>
	<body>
		<script type="module">

			import * as THREE from './build/three.module.js';


			import { GUI } from './html/jsm/libs/dat.gui.module.js';

			import { OrbitControls } from './html/jsm/controls/OrbitControls.js';
			import { GLTFLoader } from './html/jsm/loaders/GLTFLoader.js';

			var container, clock, gui, mixer, actions, activeAction, previousAction;
			var camera, scene, renderer, model, face;

			var api = { state: 'Walking' };

			init();
			animate();

			var controls = new OrbitControls( camera, renderer.domElement );
			controls.target.set( 0, 0, 0 );
			controls.enablePan = true;

			function init() {

				container = document.createElement( 'div' );
				document.body.appendChild( container );

				camera = new THREE.PerspectiveCamera( 50, window.innerWidth / window.innerHeight, 0.25, 100 );
				camera.position.set( -10, 20, 15 );
				camera.lookAt( new THREE.Vector3( 0,0,0 ) );

				scene = new THREE.Scene();
				scene.background = new THREE.Color( 0xe0e0e0 );
				scene.fog = new THREE.Fog( 0xe0e0e0, 13, 100 );

				clock = new THREE.Clock();

				// lights

				var light = new THREE.HemisphereLight( 0xffffff, 0 );
				light.position.set( 0, 20, 0 );
				scene.add( light );

				light = new THREE.DirectionalLight( 0xffffff, 0 );
				light.position.set( 0, 20, 10 );
				scene.add( light );

				// ground

				var mesh = new THREE.Mesh( new THREE.PlaneBufferGeometry( 2000, 2000 ), new THREE.MeshPhongMaterial( { color: 0x999999, depthWrite: false } ) );
				mesh.rotation.x = - Math.PI / 2;
				scene.add( mesh );

				var grid = new THREE.GridHelper( 200, 40, 0x000000, 0x000000 );
				grid.material.opacity = 0.1;
				grid.material.transparent = true;
				scene.add( grid );

				// model

				var loader = new GLTFLoader();
				loader.load( 'html/models/GenkanHouse.glb', function ( gltf ) {

					model = gltf.scene;
					model.position.set(-1,0,1);
					model.scale.set (0.5,0.5,0.5);
					model.position.y = 5;
					scene.add( model );
					mixer = new THREE.AnimationMixer(gltf.scene);
					mixer.clipAction(gltf.animations[0]).play();

				} );

				// renderer
				renderer = new THREE.WebGLRenderer( { antialias: true } );
				renderer.setPixelRatio( window.devicePixelRatio );
				renderer.setSize( window.innerWidth, window.innerHeight );
				renderer.gammaOutput = true;
				renderer.gammaFactor = 2.2;
				container.appendChild( renderer.domElement );

				window.addEventListener( 'resize', onWindowResize, false );

			}

			function onWindowResize() {

				camera.aspect = window.innerWidth / window.innerHeight;
				camera.updateProjectionMatrix();

				renderer.setSize( window.innerWidth, window.innerHeight );

			}

			//

			function animate() {

				var dt = clock.getDelta();

				if ( mixer ) mixer.update( dt );

				requestAnimationFrame( animate );

				renderer.render( scene, camera );


			}

			animate();

		</script>

	</body>
</html>
