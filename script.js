$(document).ready(function() {
    function updateGameState() {
        $.ajax({
            url: 'server.php',
            method: 'POST',
            data: { action: 'get_game_state' },
            success: function(response) {
                const gameState = JSON.parse(response);
                renderDice(gameState.dice, gameState.keepDice, gameState.rollCount);
                renderLeaderboard(gameState.leaderboard);
                updateScoreTable(gameState.scores);
                updateRollCount(gameState.rollCount);
                checkGameFinished(gameState.scores);
            }
        });
    }

    function renderDice(dice, keepDice, rollCount) {
        $('.dice-container').empty();
        dice.forEach(function(die, index) {
            const checked = keepDice[index] ? 'checked' : '';
            const disabled = keepDice[index] || rollCount === 0 ? 'disabled' : '';
            $('.dice-container').append(`
                <div class="dice" data-index="${index}">
                    ${die}
                    <input type="checkbox" class="keep-die" data-index="${index}" ${checked} ${disabled}>
                </div>
            `);
        });
    }

    function renderLeaderboard(leaderboard) {
        $('#leaderboard').empty();
        leaderboard.forEach(function(entry, index) {
            $('#leaderboard').append('<tr><td>' + (index + 1) + '</td><td>' + entry.name + '</td><td>' + entry.score + '</td></tr>');
        });
    }

    function updateScoreTable(scores) {
        Object.keys(scores).forEach(category => {
            const scoreElement = $(`.category-score[data-category="${category}"]`);
            if (scores[category] !== null) {
                scoreElement.text(scores[category]).addClass('bold').addClass('filled');
            } else {
                scoreElement.text(0).removeClass('bold').removeClass('filled');
            }
        });

        const totalScore = Object.values(scores).reduce((acc, score) => acc + (score || 0), 0);
        $('#total-score').text(totalScore);
    }

    function updateRollCount(rollCount) {
        if (rollCount >= 3) {
            $('#roll-dice').prop('disabled', true);
            $('.score-option').prop('disabled', false);
        } else {
            $('#roll-dice').prop('disabled', false);
            $('.score-option').prop('disabled', true);
        }
    }

    function checkGameFinished(scores) {
        const allScored = Object.values(scores).every(score => score !== null);
        if (allScored) {
            $('#roll-dice').prop('disabled', true);
            $('.score-option').prop('disabled', true);
        }
    }

    function rollDiceListener() {
        const keepDice = [];
        $('.keep-die').each(function() {
            keepDice.push($(this).is(':checked'));
        });

        $.ajax({
            url: 'server.php',
            method: 'POST',
            data: { action: 'roll_dice', keepDice: JSON.stringify(keepDice) },
            success: function(response) {
                updateGameState();
            }
        });
    }

    function submitScoreListener() {
        const playerName = prompt("Enter your name:");
        if (playerName) {
            $.ajax({
                url: 'server.php',
                method: 'POST',
                data: { action: 'submit_score', name: playerName },
                success: function(response) {
                    updateGameState();
                }
            });
        }
    }

    function scoreCategoryListener(event) {
        const category = event.target.getAttribute('data-category');
        const scoreElement = $(`.category-score[data-category="${category}"]`);

        if (scoreElement.hasClass('filled')) {
            alert('This category is already filled.');
            return;
        }

        $.ajax({
            url: 'server.php',
            method: 'POST',
            data: { action: 'score_category', category: category },
            success: function(response) {
                updateGameState();
                submitScoreListener();
            }
        });
    }

    function startOverListener() {
        $.ajax({
            url: 'server.php',
            method: 'POST',
            data: { action: 'start_over' },
            success: function(response) {
                updateGameState();
            }
        });
    }

    document.getElementById('roll-dice').addEventListener('click', rollDiceListener);
    document.getElementById('start-over').addEventListener('click', startOverListener);
    
    document.querySelectorAll('.score-option').forEach(button => {
        button.addEventListener('click', scoreCategoryListener);
    });

    updateGameState();
});
