let cols, rows;
let blockSize = 30; // Size of each Tetris block
let board; // Array to hold the board state
let currentPiece; // Current Tetris piece
let gameOver = true;
let score = 0; // Initialize score
let lastFallTime = 0;
const fallInterval = 500; // Time between automatic falls (500ms)
let moveSideInterval = 100; // Time between side moves when holding key
let lastMoveSideTime = 0;
let isPaused = false;
let gameStarted = false;

function setup() {
  let canvas = createCanvas(300, 600);
  canvas.parent("game-container");
  cols = width / blockSize;
  rows = height / blockSize;
  board = Array.from({ length: rows }, () => Array(cols).fill(0));
  frameRate(60); // High frame rate for smooth rendering
  newPiece();
  hideGameOverPopup(); // Ensure popup is hidden when the game first loads
}

function draw() {
  if (!gameStarted || isPaused) return;
  
  background(0);

  if (!gameOver) {
    let currentTime = millis();

    // Only move the piece down based on the fall interval
    if (currentTime - lastFallTime >= fallInterval) {
      movePieceDown();
      lastFallTime = currentTime;
    }

    drawGrid();
    drawPiece(currentPiece);
  } else {
    noLoop(); // Stop the draw loop if the game is over
  }
}

function drawGrid() {
  for (let row = 0; row < rows; row++) {
    for (let col = 0; col < cols; col++) {
      stroke(50);
      noFill();
      rect(col * blockSize, row * blockSize, blockSize, blockSize);
      if (board[row][col]) {
        fill("blue");
        rect(col * blockSize, row * blockSize, blockSize, blockSize);
      }
    }
  }
}
// Generate pieces
let bag = [];

function shuffle(array) {
  for (let i = array.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1));
    [array[i], array[j]] = [array[j], array[i]];
  }
}

function newPiece() {
  // Define all possible Tetris pieces
  const pieces = [
    { shape: [[1, 1, 1, 1]], color: "cyan" }, // I
    { shape: [[1, 1], [1, 1]], color: "yellow" }, // O
    { shape: [[0, 1, 0], [1, 1, 1]], color: "purple" }, // T
    { shape: [[1, 1, 0], [0, 1, 1]], color: "green" }, // Z
    { shape: [[0, 1, 1], [1, 1, 0]], color: "red" }, // S
    { shape: [[1, 1, 1], [1, 0, 0]], color: "blue" }, // L
    { shape: [[1, 1, 1], [0, 0, 1]], color: "orange" }, // J
  ];
  // Randomly select a piece from the 'pieces' array
  const randomIndex = Math.floor(Math.random() * pieces.length); // Get a random index
  const randomPiece = pieces[randomIndex]; // Select the piece based on the random index

  // Set the current piece, positioning it at the top of the grid and centering it horizontally
  currentPiece = {
    ...randomPiece, // Copy the selected piece's properties (shape and color)
    x: Math.floor(cols / 2) - Math.floor(randomPiece.shape[0].length / 2), // Center horizontally
    y: 0, // Start at the top of the grid
  };
}
// Function to draw the current piece on the screen
function drawPiece(piece) {
  piece.shape.forEach((row, rowIndex) => {
    row.forEach((cell, colIndex) => {
      if (cell) {
        fill(piece.color); // Set the color for the piece
        rect(
          (piece.x + colIndex) * blockSize, // Calculate the x position for the block
          (piece.y + rowIndex) * blockSize, // Calculate the y position for the block
          blockSize, // Set the width of each block
          blockSize  // Set the height of each block
        );
      }
    });
  });
}

// Collision detection
function movePieceDown() {
  currentPiece.y++;
  if (collides()) {
    currentPiece.y--;
    merge();
    clearRows();
    newPiece();
    if (collides()) {
      gameOver = true; // Game over if a new piece collides
      showGameOverPopup(); // Show the game over popup
      noLoop(); // Stop the game loop
    }
  }
}

function movePieceLeft() {
  currentPiece.x--;
  if (collides()) currentPiece.x++;
}

function movePieceRight() {
  currentPiece.x++;
  if (collides()) currentPiece.x--;
}

// Collision detection
function collides() {
  for (let row = 0; row < currentPiece.shape.length; row++) {
    for (let col = 0; col < currentPiece.shape[row].length; col++) {
      if (
        currentPiece.shape[row][col] &&
        (currentPiece.y + row >= rows || // Bottom boundary
          currentPiece.x + col < 0 || // Left boundary
          currentPiece.x + col >= cols || // Right boundary
          board[currentPiece.y + row][currentPiece.x + col]) // Collision with other pieces
      ) {
        return true;
      }
    }
  }
  return false;
}

function merge() {
  for (let row = 0; row < currentPiece.shape.length; row++) {
    for (let col = 0; col < currentPiece.shape[row].length; col++) {
      if (currentPiece.shape[row][col]) {
        board[currentPiece.y + row][currentPiece.x + col] = 1;
      }
    }
  }
}

// Line clearing function
function clearRows() {
  let rowsCleared = 0;
  for (let row = rows - 1; row >= 0; row--) {
    if (board[row].every((val) => val > 0)) {
      board.splice(row, 1);
      board.unshift(Array(cols).fill(0)); // Add a new empty row at the top
      rowsCleared++;
    }
  }
  score += rowsCleared * 100; // Update score based on cleared rows
  updateScore(); // Update the score display
}

function updateScore() {
  const scoreDisplay = document.getElementById("score-value");
  scoreDisplay.innerText = score; // Update the score display
}

function rotatePiece() {
  const tempShape = currentPiece.shape;
  
  // Rotate the piece clockwise by transposing the matrix and reversing the rows
  currentPiece.shape = currentPiece.shape[0]
    .map((val, index) => currentPiece.shape.map(row => row[index]))
    .map(row => row.reverse());

  // Check if rotation causes a collision; if so, revert to the previous shape
  if (collides()) {
    currentPiece.shape = tempShape; // revert rotation if it collides
  }
}

function keyPressed() {
  const currentTime = millis();

  if (keyCode === LEFT_ARROW && currentTime - lastMoveSideTime >= moveSideInterval) {
    movePieceLeft();
    lastMoveSideTime = currentTime;
  } else if (keyCode === RIGHT_ARROW && currentTime - lastMoveSideTime >= moveSideInterval) {
    movePieceRight();
    lastMoveSideTime = currentTime;
  } else if (keyCode === DOWN_ARROW) {
    movePieceDown(); // Accelerate the fall with the down arrow
  } else if (keyCode === UP_ARROW) {
    rotatePiece();
  }
}

function startGame() {
    // Reset game variables
    gameStarted = true;
    isPaused = false;
    score = 0;
    document.getElementById('score-value').textContent = '0';
    
    // Enable pause button and disable resume button when game starts
    document.getElementById('pauseButton').disabled = false;
    document.getElementById('resumeButton').disabled = true;
    document.getElementById('pauseButton').textContent = 'Pause';
    
    board = Array.from({ length: rows }, () => Array(cols).fill(0)); 
    hideGameOverPopup();
    gameOver = false;
    updateScore();
    newPiece();
    loop();
}

function showGameOverPopup() {
  const popup = document.getElementById('game-over-popup');
  const finalScore = document.getElementById('final-score');
  finalScore.innerText = score;
  popup.style.display = 'block';
  
  // Disable both pause and resume buttons when game is over
  document.getElementById('pauseButton').disabled = true;
  document.getElementById('resumeButton').disabled = true;

  const userScore = score;
  const data = { score: userScore };

  fetch('save_score.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify(data),
  })
  .then(response => response.json())
  .then(data => {
    console.log('Success:', data);
  })
  .catch((error) => {
    console.error('Error:', error);
  });
}

function hideGameOverPopup() {
  const popup = document.getElementById('game-over-popup');
  popup.style.display = 'none'; // Hide the popup
}

window.addEventListener("keydown", function (e) {
  if (["ArrowUp", "ArrowDown", "ArrowLeft", "ArrowRight"].includes(e.key)) {
    e.preventDefault(); // Prevent scrolling with arrow keys
  }
});

function togglePause() {
    if (!gameStarted) return;
    
    isPaused = !isPaused;
    const pauseButton = document.getElementById('pauseButton');
    const resumeButton = document.getElementById('resumeButton');
    
    if (isPaused) {
        pauseButton.disabled = true;  // Disable pause button
        resumeButton.disabled = false; // Enable resume button
        pauseButton.textContent = 'Pause';
        noLoop();
    } else {
        pauseButton.disabled = false;  // Enable pause button
        resumeButton.disabled = true;  // Disable resume button
        pauseButton.textContent = 'Pause';
        loop();
    }
}

function resumeGame() {
    if (isPaused) {
        togglePause();
    }
}