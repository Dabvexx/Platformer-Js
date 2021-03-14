<table>
    <tr> 
        <td align = "center">
            <canvas id = "pongCanvas" width = "800px" height = "800px"></canvas>
        </td> 
    </tr>

    <script>
        // Canvas Context
        var pongCanvas = document.getElementById('pongCanvas');
        var pongContext = pongCanvas.getContext('2d');

        //Game managing variables
        var blinkTimer;
        var req;
        var p1Score = 0;
        var p2Score = 0;
        var pongCourt = new Image();
        pongCourt.src = "../images/Court.png";

        // Controlls 
        var keysDown = {}; // Keep track of which key is pressed
      
        window.addEventListener("keydown", function(event) {
            keysDown[event.keyCode] = true;
        });
        window.addEventListener("keyup", function(event) {
            delete keysDown[event.keyCode];
        })

        // Pong Ball creation
        class PongBall {
            constructor(ballx, bally, ballRadius, balldx, balldy, ballColor) {
                this.ballx = ballx;
                this.bally = bally;
                this.ballRadius = ballRadius;
                this.balldx = balldx;
                this.balldy = balldy;
                this.ballColor = ballColor;
            }

            DrawBall() {
                pongContext.beginPath();
                pongContext.arc(this.ballx, this.bally, this.ballRadius, 0, Math.PI * 2, false);

                pongContext.fillStyle = this.ballColor;
                pongContext.fill();
                pongContext.closePath();
            }

            MoveBall() {

                if(this.bally + this.balldy > pongCanvas.height - this.ballRadius
		        || this.bally + this.balldy < this.ballRadius) {
			        this.balldy = -this.balldy;
		        }	
                
                this.ballx += this.balldx;
                this.bally += this.balldy;
            }

            BallCollision() {
                
                //need to do the math for a sideways paddle
                if ((this.ballx >= p1Paddle.paddelx && this.ballx <= p1Paddle.paddlex + p1Paddle.paddleWidth)
                && (this.bally >= p1Paddle.paddley && this.bally <= p1Paddle.paddley + p1Paddle.paddleHeight)) {
                    //speed up ball, may come back to change angle depending on area hit
                    this.balldx += .2;
                    this.balldy += .2;
                    this.balldy = -this.balldy;
                }

                if ((this.ballx <= p2Paddle.paddelx && this.ballx >= p2Paddle.paddlex + p2Paddle.paddleWidth)
                && (this.bally <= p2Paddle.paddley && this.bally >= p2Paddle.paddley + p2Paddle.paddleHeight)) {
                    //speed up ball, may come back to change angle depending on area hit
                    this.balldx += .2;
                    this.balldy += .2;
                    this.balldy = -this.balldy;
                }
            }
        }

        // Paddle creation
        class pongPaddle{
            constructor(paddlex, paddley, paddleWidth, paddleHeight, paddleSpeed, keyUp, keyDown) {
                this.paddlex = paddlex;
                this.paddley = paddley;
                this.paddleWidth = paddleWidth;
                this.paddleHeight = paddleHeight;
				this.paddleSpeed = paddleSpeed;
                this.keyUp = keyUp;
                this.keyDown = keyDown;
            }
            
            DrawPaddle() {
                pongContext.beginPath();
				pongContext.fillRect(this.paddlex, this.paddley, this.paddleHeight, this.paddleWidth);
				pongContext.stroke();
                pongContext.closePath();
            }

            MovePaddle() {
                for (var key in keysDown) {
                    var value = Number(key);

				    if (value == this.keyUp) {	
                        if (!(this.paddley < this.paddleHeight + 10)) {				
                            this.paddley -= this.paddleSpeed;
                        }
                    } 
                        
                    if (value == this.keyDown) {
                        if(!(this.paddley > pongCanvas.height - this.paddleHeight - 110)) {
                            this.paddley += this.paddleSpeed;
                        }
                    }
                }
            }
        }

        // Ball and Paddle objects
        var ball =new PongBall(pongCanvas.width / 2 + 20, pongCanvas.height / 2 + 60, 10, 2, 2, "#B18C77"); // Ball color is '#B18C77'

        var p1Paddle = new pongPaddle(10, pongCanvas.height / 2, 100, 10, 10, 87, 83);
        var p2Paddle = new pongPaddle(pongCanvas.width - 20, pongCanvas.height / 2, 100, 10, 10, 38, 40);

        var value = 60;
        function ResetGame() {
            value = blinker(value);
            if (p1Score >= 10) {
                GameOver('Player 1')
            }

            if (p2Score >= 10) {
                GameOver('Player 2')
            }
        }
        
        function blinker(blinktimer) {
            pongContext.clearRect(0,0, pongCanvas.width, pongCanvas.height);
            pongContext.drawImage(pongCourt,0,0);

            p1Paddle.DrawPaddle();
            p2Paddle.DrawPaddle();

            if (!(blinktimer % 17 == 0)) {
                ball.DrawBall();
                console.log("blink");
            }
            
            if (blinktimer == 0) {
                window.cancelAnimationFrame(req);
                blinktimer = 60;
                GameLoop();
            }
            else {
                blinktimer--;
                req = window.requestAnimationFrame(ResetGame);
            }

            return blinktimer;
        }

        function NewGame() {
            window.cancelAnimationFrame(req);

            pongScore = 0;
            ball.balldx = 2;
            ball.balldy = 1.75;

            GameLoop();
        }

        function GameOver() {
            //display winner, P1 or P2
        }

        // render frame
        function RenderFrame() {
            pongContext.clearRect(0, 0, pongCanvas.width, pongCanvas.height);
            pongContext.drawImage(pongCourt,0,0);

            ball.DrawBall();
			p1Paddle.DrawPaddle();
			p2Paddle.DrawPaddle();
        }

        // calculate logic
        function LogicLoop() {
            ball.MoveBall();
            ball.BallCollision();
            
            p1Paddle.MovePaddle();
            p2Paddle.MovePaddle();

            if(ball.ballx + ball.balldx > pongCanvas.width - ball.ballRadius
		        || ball.ballx + ball.balldx < ball.ballRadius) {
                    //window.cancelAnimationFrame(GameLoop);

                    ball.ballx = pongCanvas.width / 2;
                    ball.bally = pongCanvas.height / 2;

                    pongPaddle.paddley = pongCanvas.height / 2 + p1Paddle.paddleHeight;
                    //p2Paddle.paddley = pongCanvas.height / 2 + p2Paddle.paddleHeight;

                    console.log("game over");
                    pongContext.clearRect(0,0, pongCanvas.width, pongCanvas.height);
			        ResetGame();

                    //rework this to make it so that it just waits
                    //then regenerates the ball
                    //when the score reaches 5 then we can call GameOver
		    }
            else{
                req = window.requestAnimationFrame(GameLoop);
            }
        }

        function OpponantAI() {
            // Possibly calculate where the ball will go for AI
            // Program it to just barely miss sometimes
        }

        function GameLoop() {
            RenderFrame();
            LogicLoop(); 
        }

        GameLoop();
    </script>

    <tr> 
        <td>
		    <button type = "button" class = "gameButton" onclick = "NewGame()">New Game</button>
	    </tr> 
    </td>
</table>