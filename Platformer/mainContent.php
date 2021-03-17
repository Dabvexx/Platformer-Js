<table>
    <tr> 
        <td align = "center">
            <canvas id = "platformCanvas" width = "800px" height = "800px"></canvas>
        </td> 
    </tr>

    <script>
        // Variables
        var frame;

        var jumpHeight = 2;
        var timeToJumpApex = .4;
        var gravity = -(2 * jumpHeight) / timeToJumpApex ^ 2; // Currently equals -12
        var jumpVelocity = Math.abs(gravity * timeToJumpApex); // Currently equals 4.8

        var canvas = document.getElementById("platformCanvas");
        var ctx = canvas.getContext("2d");

        //Images
        var bg = new Image();
        var playerImage = new Image();
        var tileImage1 = new Image();
        var tileImage2 = new Image();

        playerImage.src = '../images/mario3Jump.png';
        //bg.src = "../images/bg.png";
        tileImage1.src = '../images/block.png';
        tileImage2.src = '../images/ground.png';
        
        // Controlls 
        var keysDown = {}; // Keep track of which key is pressed
      
        window.addEventListener("keydown", function(event) {
            keysDown[event.keyCode] = true;
        });
        window.addEventListener("keyup", function(event) {
            delete keysDown[event.keyCode];
        })

        // Classes
        // Put classes and thing into other files
        class Object {
            constructor(objX, objY, objWidth, objHeight, objImage) {
                this.x = objX;
                this.y = objY
                this.width = objWidth;
                this.height = objHeight;
                this.image = objImage;
            }

            DrawObject() {
                ctx.drawImage(this.image, this.x, this.y,
                this.width, this.height);
            }

            // Handle collisions with the tiles
            Collisions(obj) {
                // Player Bottom to Object Top
                if (this.y < obj.y + obj.width &&
                    this.x + this.width > obj.y &&
                    this.x + this.height >= obj.x &&
                    this.x + this.height <= obj.x + 1) {
                        this.y = obj.y - this.height;
                        console.log("top");
                        console.log(this.x + " " + this.y);
                }
                // Player Top to Object Bottom
                if (this.y < obj.y + obj.width &&
                    this.y + this.width > obj.y &&
                    this.x <= obj.x + obj.height &&
                    this.x >= obj.x + obj.height - 1) {
                        this.y = obj.y + obj.height;
                        console.log("bottom");
                        console.log(this.x + " " + y);
                }
                // Player Right to Object Left
                if (this.y + this.width <= obj.y + 1 &&
                    this.y + this.width >= obj.y &&
                    this.x < obj.x + obj.height &&
                    this.height + this.x > obj.x) {
                        this.x = obj.x - this.width;
						console.log("left");
                        console.log(this.x + " " + this.y);
                }
                // Player Left to Object Right
                if (this.y >= obj.y + obj.width - 1 &&
                    this.y <= obj.y + obj.width &&
                    this.x < obj.x + obj.height &&
                    this.height + this.x > obj.x) {
                        this.x = obj.x + obj.width;
                        console.log("right");
                        console.log(this.x + " " + this.y);        
                }
            }
        }

        class Player extends Object{
            constructor(playerX, playerY, playerWidth, playerHeight, speed, playerImage){
                super(playerX, playerY, playerWidth, playerHeight, playerImage);
                this.speed = speed;
            }

            DrawPlayer() {
                super.DrawObject();
            }

            // Calculate where the player will move based tile input, then move player
            MovePlayer() {
				
                this.y -= gravity;
				
                for (var key in keysDown) {
                    var value = Number(key);

				    if (value == 37) {	
                        this.x -= this.speed;
                    } 
                        
                    if (value == 39) {
                        this.x += this.speed;
                    }

                    if (value == 38) {
                        this.y -= this.speed;
                    }

                    if (value == 40) {
                        this.y += this.speed;
                    }

                    if (value == 32) {
                        this.y -= 30;
                    }
                }
            }
        }

        var player = new Player(50, 50, 80, 100, 4, playerImage);

        class Tile extends Object{
            constructor(tileImage, tileX, tileY, tileWidth, tileHeight) {
                super(tileX, tileY, tileWidth, tileHeight, tileImage);
            }

            DrawTile() {
                super.DrawObject();
            }
        }

        var tileArray = [];

        for(var i = 1; i <= 2; i++) {
            tileArray.push(new Tile(tileImage2, i * 1.6 * 50, canvas.height - 80, 80, 80));
        }

        tileArray.push(new Tile(tileImage1, 200, 400, 80, 80));

        function RenderFrame() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            //ctx.drawImage(bg, 0, 0);

            //draw all the tiles
            for(var i = 0; i <= tileArray.length - 1; i++){
                tileArray[i].DrawTile();
            }

            player.DrawPlayer();
        }

        function MoveObjects() {
            player.MovePlayer();
        }
        

        function HandleCollisions() {
            for(var i = 0; i <= tileArray.length - 1; i++){
                tileArray[i].TileCollisions(player);
            }
        }

        function GameLoop() {
            //HandleCollisions();
            MoveObjects();

            RenderFrame();
            frame = window.requestAnimationFrame(GameLoop);
        }

        GameLoop();
    </script>
</table>
