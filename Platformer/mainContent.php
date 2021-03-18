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
                if (this.x < obj.x + obj.width &&
                    this.x + this.width > obj.x &&
                    this.y + this.height >= obj.y &&
                    this.y + this.height <= obj.y + 1) {
                        this.y = obj.y - this.height;
                        console.log("top");
                        console.log(this.x + " " + this.y);
                }
                // Player Top to Object Bottom
                if (this.x < obj.x + obj.width &&
                    this.x + this.width > obj.x &&
                    this.y <= obj.y + obj.height &&
                    this.y >= obj.y + obj.height - 1) {
                        this.y = obj.y + obj.height;
                        console.log("bottom");
                        console.log(this.x + " " + this.y);
                }
                // Player Right to Object Left
                if (this.x + this.width <= obj.x + 1 &&
                    this.x + this.width >= obj.x &&
                    this.y < obj.y + obj.height &&
                    this.height + this.y > obj.y) {
                        this.x = obj.x + obj.width;
						console.log("left");
                        console.log(this.x + " " + this.y);
                }
                // Player Left to Object Right
                if (this.x >= obj.x + obj.width - 1 &&
                    this.x <= obj.x + obj.width &&
                    this.y < obj.y + obj.height &&
                    this.height + this.y > obj.y) {
                        this.x = obj.x - this.width;
                        console.log("right");
                        console.log(this.x + " " + this.y);        
                }

                /*// Player Right to Object Left
                if (player.offsetLeft + player.offsetWidth <= object[i].offsetLeft + 1 &&
                    player.offsetLeft + player.offsetWidth >= object[i].offsetLeft &&
                    player.offsetTop < object[i].offsetTop + object[i].offsetHeight &&
                    player.offsetHeight + player.offsetTop > object[i].offsetTop) {
                player.style.left = object[i].offsetLeft - player.offsetWidth + "px";   
                }
                // Player Left to Object Right
                if (player.offsetLeft >= object[i].offsetLeft + object[i].offsetWidth - 1 &&
                    player.offsetLeft <= object[i].offsetLeft + object[i].offsetWidth &&
                    player.offsetTop < object[i].offsetTop + object[i].offsetHeight &&
                    player.offsetHeight + player.offsetTop > object[i].offsetTop) {
                player.style.left = object[i].offsetLeft + object[i].offsetWidth + "px";   
                }
                // Player Bottom to Object Top
                if (player.offsetLeft < object[i].offsetLeft + object[i].offsetWidth &&
                    player.offsetLeft + player.offsetWidth > object[i].offsetLeft &&
                    player.offsetTop + player.offsetHeight >= object[i].offsetTop &&
                    player.offsetTop + player.offsetHeight <= object[i].offsetTop + 1) {
                player.style.top = object[i].offsetTop - player.offsetHeight + "px";   
                }
                // Player Top to Object Bottom
                if (player.offsetLeft < object[i].offsetLeft + object[i].offsetWidth &&
                    player.offsetLeft + player.offsetWidth > object[i].offsetLeft &&
                    player.offsetTop <= object[i].offsetTop + object[i].offsetHeight &&
                    player.offsetTop >= object[i].offsetTop + object[i].offsetHeight - 1) {
                player.style.top = object[i].offsetTop + object[i].offsetHeight + "px";   
                }*/
            }
        }

        class Player extends Object{
            constructor(playerX, playerY, playerWidth, playerHeight, speed, playerImage){
                super(playerX, playerY, playerWidth, playerHeight, playerImage);
                this.speed = speed;
            }

            // Calculate where the player will move based tile input, then move player
            MovePlayer() {
				
                //this.y -= gravity;
                this.y -= -4;
                this.x -= -0.2;
				
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

        var player = new Player(50, 50, 80, 100, 8, playerImage);

        class Tile extends Object{
            constructor(tileImage, tileX, tileY, tileWidth, tileHeight) {
                super(tileX, tileY, tileWidth, tileHeight, tileImage);
            }
        }

        var tileArray = [];

        for(var i = 1; i <= 5; i++) {
            tileArray.push(new Tile(tileImage2, i * 1.6 * 50, canvas.height - 80, 80, 80));
        }

        tileArray.push(new Tile(tileImage1, 200, 400, 80, 80));

        var stop = false;
        var frameCount = 0;
        var results = ("#results");
        var fps, fpsInterval, startTime, now, then, elapsed;


        // initialize the timer variables and start the animation

        function startAnimating(fps) {
            fpsInterval = 1000 / fps;
            then = Date.now();
            startTime = then;
            animate();
        }

        // the animation loop calculates time elapsed since the last loop
        // and only draws if your specified fps interval is achieved

        function animate() {

            // request another frame

            requestAnimationFrame(animate);

            // calc elapsed time since last loop

            now = Date.now();
            elapsed = now - then;

            // if enough time has elapsed, draw the next frame

            if (elapsed > fpsInterval) {

                // Get ready for next frame by setting then=now, but also adjust for your
                // specified fpsInterval not being a multiple of RAF's interval (16.7ms)
                then = now - (elapsed % fpsInterval);

                GameLoop();
            }
        }

        function RenderFrame() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            //ctx.drawImage(bg, 0, 0);

            //draw all the tiles
            for(var i = 0; i <= tileArray.length - 1; i++){
                tileArray[i].DrawObject();
            }

            player.DrawObject();
        }

        function MoveObjects() {
            player.MovePlayer();
        }
        

        function HandleCollisions() {
            for(var i = 0; i <= tileArray.length - 1; i++){
                player.Collisions(tileArray[i]);
            }
        }

        function GameLoop() {
            MoveObjects();
            HandleCollisions();

            RenderFrame();
        }

        startAnimating(60);
    </script>
</table>
