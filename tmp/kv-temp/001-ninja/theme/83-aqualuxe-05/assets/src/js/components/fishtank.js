/**
 * Interactive Fish Tank Hero Component
 * 
 * Creates an immersive underwater scene with tropical fish, coral reefs,
 * bubbles, and interactive elements using Three.js and GSAP.
 */

import * as THREE from 'three';
import { gsap } from 'gsap';

class FishTank {
    constructor(container, options = {}) {
        this.container = container;
        this.options = {
            fishCount: 12,
            bubbleCount: 20,
            enableSound: true,
            enableInteraction: true,
            autoStart: true,
            backgroundColor: 0x001122,
            ...options
        };

        this.scene = null;
        this.camera = null;
        this.renderer = null;
        this.animationId = null;
        
        this.fish = [];
        this.bubbles = [];
        this.coral = [];
        
        this.mouse = new THREE.Vector2();
        this.clock = new THREE.Clock();
        
        this.isInitialized = false;
        this.isDestroyed = false;
        
        this.init();
    }

    async init() {
        try {
            await this.createScene();
            await this.createCamera();
            await this.createRenderer();
            await this.createLighting();
            await this.createEnvironment();
            await this.createFish();
            await this.createBubbles();
            await this.createCoral();
            
            if (this.options.enableInteraction) {
                this.addEventListeners();
            }
            
            if (this.options.autoStart) {
                this.start();
            }
            
            this.isInitialized = true;
            
            // Dispatch initialization event
            this.container.dispatchEvent(new CustomEvent('fishtank:initialized', {
                detail: { fishtank: this }
            }));
            
        } catch (error) {
            console.error('Fish tank initialization failed:', error);
            throw error;
        }
    }

    createScene() {
        this.scene = new THREE.Scene();
        this.scene.background = new THREE.Color(this.options.backgroundColor);
        
        // Add fog for depth
        this.scene.fog = new THREE.Fog(this.options.backgroundColor, 50, 200);
    }

    createCamera() {
        const aspect = this.container.clientWidth / this.container.clientHeight;
        this.camera = new THREE.PerspectiveCamera(75, aspect, 0.1, 1000);
        this.camera.position.set(0, 0, 50);
    }

    createRenderer() {
        this.renderer = new THREE.WebGLRenderer({ 
            antialias: true, 
            alpha: false,
            powerPreference: 'high-performance'
        });
        
        this.renderer.setSize(this.container.clientWidth, this.container.clientHeight);
        this.renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
        this.renderer.shadowMap.enabled = true;
        this.renderer.shadowMap.type = THREE.PCFSoftShadowMap;
        this.renderer.outputEncoding = THREE.sRGBEncoding;
        this.renderer.toneMapping = THREE.ACESFilmicToneMapping;
        this.renderer.toneMappingExposure = 1.2;
        
        this.container.appendChild(this.renderer.domElement);
    }

    createLighting() {
        // Ambient light
        const ambientLight = new THREE.AmbientLight(0x4080ff, 0.4);
        this.scene.add(ambientLight);
        
        // Directional light (sun rays through water)
        const directionalLight = new THREE.DirectionalLight(0xffffff, 0.8);
        directionalLight.position.set(10, 20, 5);
        directionalLight.castShadow = true;
        directionalLight.shadow.mapSize.width = 2048;
        directionalLight.shadow.mapSize.height = 2048;
        directionalLight.shadow.camera.near = 0.5;
        directionalLight.shadow.camera.far = 100;
        this.scene.add(directionalLight);
        
        // Point lights for underwater effect
        const pointLight1 = new THREE.PointLight(0x00ffff, 0.5, 100);
        pointLight1.position.set(-20, 10, 20);
        this.scene.add(pointLight1);
        
        const pointLight2 = new THREE.PointLight(0x0080ff, 0.3, 80);
        pointLight2.position.set(20, -10, 15);
        this.scene.add(pointLight2);
    }

    async createEnvironment() {
        // Create seabed
        const seabedGeometry = new THREE.PlaneGeometry(200, 200);
        const seabedMaterial = new THREE.MeshLambertMaterial({ 
            color: 0x8B7355,
            transparent: true,
            opacity: 0.8
        });
        const seabed = new THREE.Mesh(seabedGeometry, seabedMaterial);
        seabed.rotation.x = -Math.PI / 2;
        seabed.position.y = -40;
        seabed.receiveShadow = true;
        this.scene.add(seabed);
        
        // Create water caustics effect
        this.createWaterCaustics();
        
        // Create background fish school
        this.createBackgroundSchool();
    }

    createWaterCaustics() {
        const causticsGeometry = new THREE.PlaneGeometry(100, 100, 32, 32);
        const causticsMaterial = new THREE.ShaderMaterial({
            uniforms: {
                time: { value: 0 },
                opacity: { value: 0.3 }
            },
            vertexShader: `
                uniform float time;
                varying vec2 vUv;
                varying vec3 vPosition;
                
                void main() {
                    vUv = uv;
                    vPosition = position;
                    
                    vec3 pos = position;
                    pos.z += sin(pos.x * 0.1 + time) * 2.0;
                    pos.z += cos(pos.y * 0.1 + time * 0.8) * 1.5;
                    
                    gl_Position = projectionMatrix * modelViewMatrix * vec4(pos, 1.0);
                }
            `,
            fragmentShader: `
                uniform float time;
                uniform float opacity;
                varying vec2 vUv;
                
                void main() {
                    vec2 uv = vUv;
                    
                    float caustic = sin(uv.x * 10.0 + time * 2.0) * cos(uv.y * 10.0 + time * 1.5);
                    caustic += sin(uv.x * 15.0 - time * 1.8) * cos(uv.y * 12.0 + time * 2.2);
                    caustic = caustic * 0.5 + 0.5;
                    
                    gl_FragColor = vec4(0.5, 0.8, 1.0, caustic * opacity);
                }
            `,
            transparent: true,
            blending: THREE.AdditiveBlending
        });
        
        const caustics = new THREE.Mesh(causticsGeometry, causticsMaterial);
        caustics.position.y = 30;
        caustics.rotation.x = Math.PI;
        this.scene.add(caustics);
        
        this.causticsMaterial = causticsMaterial;
    }

    createBackgroundSchool() {
        const schoolGeometry = new THREE.BufferGeometry();
        const schoolCount = 50;
        const positions = new Float32Array(schoolCount * 3);
        
        for (let i = 0; i < schoolCount; i++) {
            positions[i * 3] = (Math.random() - 0.5) * 200;
            positions[i * 3 + 1] = (Math.random() - 0.5) * 60;
            positions[i * 3 + 2] = -80 - Math.random() * 40;
        }
        
        schoolGeometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));
        
        const schoolMaterial = new THREE.PointsMaterial({
            color: 0x4080ff,
            size: 0.5,
            transparent: true,
            opacity: 0.6
        });
        
        this.backgroundSchool = new THREE.Points(schoolGeometry, schoolMaterial);
        this.scene.add(this.backgroundSchool);
    }

    async createFish() {
        const fishTypes = [
            { color: 0xff6600, size: 1.2, speed: 0.8 }, // Clownfish
            { color: 0x4169e1, size: 1.0, speed: 1.0 }, // Blue Tang
            { color: 0xffd700, size: 0.8, speed: 1.2 }, // Yellow Tang
            { color: 0x9932cc, size: 1.1, speed: 0.9 }, // Purple Fish
            { color: 0x00ced1, size: 0.9, speed: 1.1 }  // Turquoise Fish
        ];

        for (let i = 0; i < this.options.fishCount; i++) {
            const fishType = fishTypes[i % fishTypes.length];
            const fish = this.createSingleFish(fishType);
            
            // Random starting position
            fish.position.set(
                (Math.random() - 0.5) * 80,
                (Math.random() - 0.5) * 40,
                (Math.random() - 0.5) * 30
            );
            
            // Random rotation
            fish.rotation.y = Math.random() * Math.PI * 2;
            
            // Add swimming animation
            this.addFishAnimation(fish, fishType.speed);
            
            this.fish.push(fish);
            this.scene.add(fish);
        }
    }

    createSingleFish(fishType) {
        const fishGroup = new THREE.Group();
        
        // Fish body
        const bodyGeometry = new THREE.SphereGeometry(1, 8, 6);
        bodyGeometry.scale(1.5, 1, 0.8);
        const bodyMaterial = new THREE.MeshPhongMaterial({ 
            color: fishType.color,
            shininess: 100,
            transparent: true,
            opacity: 0.9
        });
        const body = new THREE.Mesh(bodyGeometry, bodyMaterial);
        body.castShadow = true;
        fishGroup.add(body);
        
        // Fish tail
        const tailGeometry = new THREE.ConeGeometry(0.8, 1.2, 6);
        const tailMaterial = new THREE.MeshPhongMaterial({ 
            color: new THREE.Color(fishType.color).multiplyScalar(0.8),
            transparent: true,
            opacity: 0.8
        });
        const tail = new THREE.Mesh(tailGeometry, tailMaterial);
        tail.position.x = -1.8;
        tail.rotation.z = Math.PI / 2;
        fishGroup.add(tail);
        
        // Fish fins
        const finGeometry = new THREE.ConeGeometry(0.3, 0.8, 4);
        const finMaterial = new THREE.MeshPhongMaterial({ 
            color: new THREE.Color(fishType.color).multiplyScalar(0.7),
            transparent: true,
            opacity: 0.7
        });
        
        const topFin = new THREE.Mesh(finGeometry, finMaterial);
        topFin.position.set(0, 1, 0);
        topFin.rotation.x = -Math.PI / 2;
        fishGroup.add(topFin);
        
        const bottomFin = new THREE.Mesh(finGeometry, finMaterial);
        bottomFin.position.set(0, -1, 0);
        bottomFin.rotation.x = Math.PI / 2;
        fishGroup.add(bottomFin);
        
        // Eyes
        const eyeGeometry = new THREE.SphereGeometry(0.15, 8, 6);
        const eyeMaterial = new THREE.MeshPhongMaterial({ color: 0xffffff });
        
        const leftEye = new THREE.Mesh(eyeGeometry, eyeMaterial);
        leftEye.position.set(1.2, 0.3, 0.6);
        fishGroup.add(leftEye);
        
        const rightEye = new THREE.Mesh(eyeGeometry, eyeMaterial);
        rightEye.position.set(1.2, 0.3, -0.6);
        fishGroup.add(rightEye);
        
        // Eye pupils
        const pupilGeometry = new THREE.SphereGeometry(0.08, 6, 4);
        const pupilMaterial = new THREE.MeshBasicMaterial({ color: 0x000000 });
        
        const leftPupil = new THREE.Mesh(pupilGeometry, pupilMaterial);
        leftPupil.position.set(1.3, 0.3, 0.6);
        fishGroup.add(leftPupil);
        
        const rightPupil = new THREE.Mesh(pupilGeometry, pupilMaterial);
        rightPupil.position.set(1.3, 0.3, -0.6);
        fishGroup.add(rightPupil);
        
        // Scale the entire fish
        fishGroup.scale.setScalar(fishType.size);
        
        // Store fish data
        fishGroup.userData = {
            type: fishType,
            speed: fishType.speed,
            direction: new THREE.Vector3(
                (Math.random() - 0.5) * 2,
                (Math.random() - 0.5) * 0.5,
                (Math.random() - 0.5) * 2
            ).normalize(),
            targetPosition: new THREE.Vector3()
        };
        
        return fishGroup;
    }

    addFishAnimation(fish, speed) {
        const userData = fish.userData;
        
        // Continuous swimming animation
        const swimTimeline = gsap.timeline({ repeat: -1 });
        
        // Body swaying
        swimTimeline.to(fish.rotation, {
            duration: 2 / speed,
            z: 0.1,
            ease: "sine.inOut",
            yoyo: true,
            repeat: -1
        });
        
        // Tail movement
        const tail = fish.children.find(child => child.position.x < 0);
        if (tail) {
            gsap.to(tail.rotation, {
                duration: 0.5 / speed,
                y: 0.3,
                ease: "sine.inOut",
                yoyo: true,
                repeat: -1
            });
        }
        
        // Fin movement
        const fins = fish.children.filter(child => child.position.y !== 0);
        fins.forEach((fin, index) => {
            gsap.to(fin.rotation, {
                duration: (0.8 + index * 0.2) / speed,
                x: fin.rotation.x + 0.2,
                ease: "sine.inOut",
                yoyo: true,
                repeat: -1
            });
        });
    }

    async createBubbles() {
        const bubbleGeometry = new THREE.SphereGeometry(0.1, 6, 4);
        const bubbleMaterial = new THREE.MeshPhongMaterial({
            color: 0xffffff,
            transparent: true,
            opacity: 0.3,
            shininess: 100
        });

        for (let i = 0; i < this.options.bubbleCount; i++) {
            const bubble = new THREE.Mesh(bubbleGeometry, bubbleMaterial);
            
            // Random starting position at bottom
            bubble.position.set(
                (Math.random() - 0.5) * 60,
                -35 - Math.random() * 5,
                (Math.random() - 0.5) * 40
            );
            
            // Random size
            const scale = 0.5 + Math.random() * 1.5;
            bubble.scale.setScalar(scale);
            
            // Add rising animation
            this.addBubbleAnimation(bubble);
            
            this.bubbles.push(bubble);
            this.scene.add(bubble);
        }
    }

    addBubbleAnimation(bubble) {
        const startY = bubble.position.y;
        const endY = 40;
        const duration = 8 + Math.random() * 4;
        
        // Rising animation
        gsap.to(bubble.position, {
            duration: duration,
            y: endY,
            ease: "none",
            onComplete: () => {
                // Reset bubble position
                bubble.position.y = startY;
                bubble.position.x = (Math.random() - 0.5) * 60;
                bubble.position.z = (Math.random() - 0.5) * 40;
                this.addBubbleAnimation(bubble);
            }
        });
        
        // Floating motion
        gsap.to(bubble.position, {
            duration: duration / 2,
            x: bubble.position.x + (Math.random() - 0.5) * 4,
            z: bubble.position.z + (Math.random() - 0.5) * 4,
            ease: "sine.inOut",
            yoyo: true,
            repeat: 1
        });
        
        // Scale pulsing
        gsap.to(bubble.scale, {
            duration: 2 + Math.random() * 2,
            x: bubble.scale.x * 1.2,
            y: bubble.scale.y * 1.2,
            z: bubble.scale.z * 1.2,
            ease: "sine.inOut",
            yoyo: true,
            repeat: -1
        });
    }

    async createCoral() {
        const coralColors = [0xff6b6b, 0x4ecdc4, 0x45b7d1, 0x96ceb4, 0xfeca57];
        
        for (let i = 0; i < 8; i++) {
            const coral = this.createSingleCoral(coralColors[i % coralColors.length]);
            
            // Position coral on seabed
            coral.position.set(
                (Math.random() - 0.5) * 80,
                -35 + Math.random() * 3,
                (Math.random() - 0.5) * 40
            );
            
            coral.rotation.y = Math.random() * Math.PI * 2;
            
            // Add gentle swaying animation
            this.addCoralAnimation(coral);
            
            this.coral.push(coral);
            this.scene.add(coral);
        }
    }

    createSingleCoral(color) {
        const coralGroup = new THREE.Group();
        
        // Main coral trunk
        const trunkGeometry = new THREE.CylinderGeometry(0.5, 1, 4, 6);
        const trunkMaterial = new THREE.MeshPhongMaterial({ 
            color: new THREE.Color(color).multiplyScalar(0.8)
        });
        const trunk = new THREE.Mesh(trunkGeometry, trunkMaterial);
        trunk.position.y = 2;
        trunk.castShadow = true;
        coralGroup.add(trunk);
        
        // Coral branches
        for (let i = 0; i < 3; i++) {
            const branchGeometry = new THREE.SphereGeometry(0.8, 6, 4);
            branchGeometry.scale(1 + Math.random() * 0.5, 0.8, 1 + Math.random() * 0.5);
            const branchMaterial = new THREE.MeshPhongMaterial({ 
                color: color,
                transparent: true,
                opacity: 0.8
            });
            const branch = new THREE.Mesh(branchGeometry, branchMaterial);
            
            branch.position.set(
                (Math.random() - 0.5) * 2,
                3 + i * 1.5,
                (Math.random() - 0.5) * 2
            );
            
            branch.castShadow = true;
            coralGroup.add(branch);
        }
        
        return coralGroup;
    }

    addCoralAnimation(coral) {
        // Gentle swaying
        gsap.to(coral.rotation, {
            duration: 4 + Math.random() * 2,
            z: 0.05,
            ease: "sine.inOut",
            yoyo: true,
            repeat: -1
        });
        
        gsap.to(coral.rotation, {
            duration: 6 + Math.random() * 3,
            x: 0.03,
            ease: "sine.inOut",
            yoyo: true,
            repeat: -1
        });
    }

    addEventListeners() {
        // Mouse interaction
        this.container.addEventListener('mousemove', (event) => {
            const rect = this.container.getBoundingClientRect();
            this.mouse.x = ((event.clientX - rect.left) / rect.width) * 2 - 1;
            this.mouse.y = -((event.clientY - rect.top) / rect.height) * 2 + 1;
            
            this.onMouseMove();
        });
        
        // Click interaction
        this.container.addEventListener('click', (event) => {
            const rect = this.container.getBoundingClientRect();
            const clickX = ((event.clientX - rect.left) / rect.width) * 2 - 1;
            const clickY = -((event.clientY - rect.top) / rect.height) * 2 + 1;
            
            this.onMouseClick(clickX, clickY);
        });
        
        // Resize handling
        window.addEventListener('resize', () => this.onWindowResize());
        
        // Visibility change handling
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                this.pause();
            } else {
                this.resume();
            }
        });
    }

    onMouseMove() {
        // Make fish react to mouse movement
        this.fish.forEach(fish => {
            const distance = fish.position.distanceTo(
                new THREE.Vector3(this.mouse.x * 40, this.mouse.y * 20, fish.position.z)
            );
            
            if (distance < 15) {
                // Fish moves away from cursor
                const direction = fish.position.clone()
                    .sub(new THREE.Vector3(this.mouse.x * 40, this.mouse.y * 20, fish.position.z))
                    .normalize();
                
                gsap.to(fish.position, {
                    duration: 0.5,
                    x: fish.position.x + direction.x * 5,
                    y: fish.position.y + direction.y * 3,
                    ease: "power2.out"
                });
            }
        });
    }

    onMouseClick(x, y) {
        // Create bubble burst at click position
        this.createBubbleBurst(x * 40, y * 20, 0);
        
        // Attract nearby fish
        this.fish.forEach(fish => {
            const clickPos = new THREE.Vector3(x * 40, y * 20, fish.position.z);
            const distance = fish.position.distanceTo(clickPos);
            
            if (distance < 20) {
                gsap.to(fish.position, {
                    duration: 1,
                    x: clickPos.x + (Math.random() - 0.5) * 4,
                    y: clickPos.y + (Math.random() - 0.5) * 2,
                    ease: "power2.inOut"
                });
            }
        });
    }

    createBubbleBurst(x, y, z) {
        const burstCount = 8;
        const bubbleGeometry = new THREE.SphereGeometry(0.2, 6, 4);
        const bubbleMaterial = new THREE.MeshPhongMaterial({
            color: 0xffffff,
            transparent: true,
            opacity: 0.6
        });
        
        for (let i = 0; i < burstCount; i++) {
            const bubble = new THREE.Mesh(bubbleGeometry, bubbleMaterial);
            bubble.position.set(x, y, z);
            
            const direction = new THREE.Vector3(
                (Math.random() - 0.5) * 2,
                Math.random(),
                (Math.random() - 0.5) * 2
            ).normalize();
            
            this.scene.add(bubble);
            
            gsap.to(bubble.position, {
                duration: 2,
                x: x + direction.x * 10,
                y: y + direction.y * 8,
                z: z + direction.z * 5,
                ease: "power2.out"
            });
            
            gsap.to(bubble.scale, {
                duration: 2,
                x: 0,
                y: 0,
                z: 0,
                ease: "power2.out",
                onComplete: () => {
                    this.scene.remove(bubble);
                }
            });
            
            gsap.to(bubble.material, {
                duration: 2,
                opacity: 0,
                ease: "power2.out"
            });
        }
    }

    onWindowResize() {
        if (!this.camera || !this.renderer) return;
        
        const width = this.container.clientWidth;
        const height = this.container.clientHeight;
        
        this.camera.aspect = width / height;
        this.camera.updateProjectionMatrix();
        
        this.renderer.setSize(width, height);
        this.renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    }

    start() {
        if (this.animationId) return;
        this.animate();
    }

    pause() {
        if (this.animationId) {
            cancelAnimationFrame(this.animationId);
            this.animationId = null;
        }
    }

    resume() {
        if (!this.animationId) {
            this.animate();
        }
    }

    animate() {
        this.animationId = requestAnimationFrame(() => this.animate());
        
        if (this.isDestroyed) return;
        
        const deltaTime = this.clock.getDelta();
        const elapsedTime = this.clock.getElapsedTime();
        
        // Update fish AI
        this.updateFishAI(deltaTime, elapsedTime);
        
        // Update caustics
        if (this.causticsMaterial) {
            this.causticsMaterial.uniforms.time.value = elapsedTime;
        }
        
        // Update background school
        if (this.backgroundSchool) {
            this.backgroundSchool.rotation.y += 0.001;
        }
        
        // Render scene
        this.renderer.render(this.scene, this.camera);
    }

    updateFishAI(deltaTime, elapsedTime) {
        this.fish.forEach((fish, index) => {
            const userData = fish.userData;
            
            // Simple flocking behavior
            const separation = this.getSeparation(fish, index);
            const alignment = this.getAlignment(fish, index);
            const cohesion = this.getCohesion(fish, index);
            
            // Combine forces
            userData.direction.add(separation.multiplyScalar(1.5));
            userData.direction.add(alignment.multiplyScalar(0.8));
            userData.direction.add(cohesion.multiplyScalar(0.5));
            
            // Boundary avoidance
            const boundary = this.getBoundaryAvoidance(fish);
            userData.direction.add(boundary.multiplyScalar(2.0));
            
            // Normalize and apply
            userData.direction.normalize();
            
            // Update position
            const velocity = userData.direction.clone().multiplyScalar(userData.speed * deltaTime * 10);
            fish.position.add(velocity);
            
            // Update rotation to face direction
            const targetRotation = Math.atan2(userData.direction.x, userData.direction.z);
            fish.rotation.y = THREE.MathUtils.lerp(fish.rotation.y, targetRotation, deltaTime * 2);
            
            // Add some randomness
            if (Math.random() < 0.02) {
                userData.direction.add(new THREE.Vector3(
                    (Math.random() - 0.5) * 0.2,
                    (Math.random() - 0.5) * 0.1,
                    (Math.random() - 0.5) * 0.2
                ));
            }
        });
    }

    getSeparation(fish, index) {
        const separationRadius = 8;
        const force = new THREE.Vector3();
        let count = 0;
        
        this.fish.forEach((otherFish, otherIndex) => {
            if (index === otherIndex) return;
            
            const distance = fish.position.distanceTo(otherFish.position);
            if (distance < separationRadius && distance > 0) {
                const diff = fish.position.clone().sub(otherFish.position);
                diff.normalize();
                diff.divideScalar(distance); // Weight by distance
                force.add(diff);
                count++;
            }
        });
        
        if (count > 0) {
            force.divideScalar(count);
            force.normalize();
        }
        
        return force;
    }

    getAlignment(fish, index) {
        const alignmentRadius = 15;
        const force = new THREE.Vector3();
        let count = 0;
        
        this.fish.forEach((otherFish, otherIndex) => {
            if (index === otherIndex) return;
            
            const distance = fish.position.distanceTo(otherFish.position);
            if (distance < alignmentRadius) {
                force.add(otherFish.userData.direction);
                count++;
            }
        });
        
        if (count > 0) {
            force.divideScalar(count);
            force.normalize();
        }
        
        return force;
    }

    getCohesion(fish, index) {
        const cohesionRadius = 20;
        const force = new THREE.Vector3();
        let count = 0;
        
        this.fish.forEach((otherFish, otherIndex) => {
            if (index === otherIndex) return;
            
            const distance = fish.position.distanceTo(otherFish.position);
            if (distance < cohesionRadius) {
                force.add(otherFish.position);
                count++;
            }
        });
        
        if (count > 0) {
            force.divideScalar(count);
            force.sub(fish.position);
            force.normalize();
        }
        
        return force;
    }

    getBoundaryAvoidance(fish) {
        const force = new THREE.Vector3();
        const margin = 35;
        
        if (fish.position.x > margin) force.x = -1;
        if (fish.position.x < -margin) force.x = 1;
        if (fish.position.y > 20) force.y = -1;
        if (fish.position.y < -30) force.y = 1;
        if (fish.position.z > 25) force.z = -1;
        if (fish.position.z < -25) force.z = 1;
        
        return force;
    }

    destroy() {
        this.isDestroyed = true;
        
        if (this.animationId) {
            cancelAnimationFrame(this.animationId);
            this.animationId = null;
        }
        
        // Clean up event listeners
        window.removeEventListener('resize', this.onWindowResize);
        
        // Clean up Three.js objects
        if (this.scene) {
            this.scene.traverse((object) => {
                if (object.geometry) object.geometry.dispose();
                if (object.material) {
                    if (Array.isArray(object.material)) {
                        object.material.forEach(material => material.dispose());
                    } else {
                        object.material.dispose();
                    }
                }
            });
            this.scene.clear();
        }
        
        if (this.renderer) {
            this.renderer.dispose();
            if (this.renderer.domElement && this.renderer.domElement.parentNode) {
                this.renderer.domElement.parentNode.removeChild(this.renderer.domElement);
            }
        }
        
        // Clear arrays
        this.fish = [];
        this.bubbles = [];
        this.coral = [];
    }
}

export default FishTank;
