THREE.OrbitControls = function (object, domElement) {
  const scope = this;
  this.object = object;
  this.domElement = domElement;
  this.enableDamping = false;
  this.dampingFactor = 0.05;

  const spherical = new THREE.Spherical();
  let isDragging = false;
  let previousMousePosition = { x: 0, y: 0 };

  domElement.addEventListener("mousedown", function (event) {
    isDragging = true;
    previousMousePosition.x = event.clientX;
    previousMousePosition.y = event.clientY;
  });

  domElement.addEventListener("mouseup", function () {
    isDragging = false;
  });

  domElement.addEventListener("mousemove", function (event) {
    if (!isDragging) return;

    const deltaX = event.clientX - previousMousePosition.x;
    const deltaY = event.clientY - previousMousePosition.y;

    spherical.setFromVector3(scope.object.position);
    spherical.theta -= deltaX * 0.005;
    spherical.phi -= deltaY * 0.005;
    spherical.makeSafe();
    scope.object.position.setFromSpherical(spherical);
    scope.object.lookAt(0, 0, 0);

    previousMousePosition.x = event.clientX;
    previousMousePosition.y = event.clientY;
  });

  this.update = function () {};
};
