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
        class Player {
            constructor(playerX, playerY, playerWidth, playerHeight, playerSpeed){
                this.playerX = playerX;
                this.playerY = playerY;
                this.playerWidth = playerWidth;
                this.playerHeight = playerHeight;
                this.playerSpeed = playerSpeed;
            }

            DrawPlayer() {
                ctx.drawImage(playerImage, this.playerX, this.playerY,
                this.playerWidth, this.playerHeight);
            }

            // Calculate where the player will move based off input, then move player
            MovePlayer() {
                for (var key in keysDown) {
                    var value = Number(key);

				    if (value == 37) {	
                        this.playerX -= this.playerSpeed;
                    } 
                        
                    if (value == 39) {
                        this.playerX += this.playerSpeed;
                    }

                    if (value == 32) {
                        this.playerY -= 30;
                    }
                }

                this.playerY -= gravity;
            }
        }

        var player = new Player(50, 50, 80, 100, 4);

        class Tile {
            constructor(tileImage, tileX, tileY, tileWidth, tileHeight) {
                this.tileImage = tileImage;
                this.tileX = tileX;
                this.tileY = tileY;
                this.tileWidth = tileWidth;
                this.tileHeight = tileHeight;
            }

            DrawTile() {
                ctx.drawImage(this.tileImage, this.tileX, this.tileY,
                this.tileWidth, this.tileHeight);
            }

            // Handle collisions with the tiles
            TileCollisions(plr) {

                // Right side
                if(plr.playerX < this.tileX + this.tileWidth &&
                // Left side
                plr.playerX + plr.playerWidth > this.tileX &&
                // Top side
                plr.playerY + plr.playerHeight > this.tileY &&
                // Bottom side
                plr.playerY < this.tileY + this.tileHeight) {
                        plr.playerY = this.tileY - plr.playerHeight;
                }
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
            HandleCollisions();
            MoveObjects();

            RenderFrame();
            frame = window.requestAnimationFrame(GameLoop);
        }

        GameLoop();
    </script>
</table>
