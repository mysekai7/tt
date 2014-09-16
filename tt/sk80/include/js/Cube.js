var Cube = function (size){
	
	if (size === undefined){
		size = 10;
	}
	
	this.pointsArray = [
		this.make3DPoint(-size,-size,-size),
		this.make3DPoint(size,-size,-size),
		this.make3DPoint(size,-size,size),
		this.make3DPoint(-size,-size,size),
		this.make3DPoint(-size,size,-size),
		this.make3DPoint(size,size,-size),
		this.make3DPoint(size,size,size),
		this.make3DPoint(-size,size,size),
		this.make3DPoint(0,size,-size),
		this.make3DPoint(size,size,0),
		this.make3DPoint(0,size,size),
		this.make3DPoint(-size,size,0),
		this.make3DPoint(0,-size,-size),
		this.make3DPoint(size,-size,0),
		this.make3DPoint(0,-size,size),
		this.make3DPoint(-size,-size,0),
		this.make3DPoint(-size,0,-size),
		this.make3DPoint(size,0,-size),
		this.make3DPoint(size,0,size),
		this.make3DPoint(-size,0,size)
	];
	
};

Cube.prototype = new DisplayObject3D();

