function scoreTurn(game, scoreBox) {
    if (game.scores[scoreBox] !== undefined) {
        console.log(`Category ${scoreBox} already scored.`);
        return; 
    }

    const diceValues = game.getDiceValues();
    let score = 0;

    switch (scoreBox) {
        case 'ones':
            score = sumOfValue(diceValues, 1);
            break;
        case 'twos':
            score = sumOfValue(diceValues, 2);
            break;
        case 'threes':
            score = sumOfValue(diceValues, 3);
            break;
        case 'fours':
            score = sumOfValue(diceValues, 4);
            break;
        case 'fives':
            score = sumOfValue(diceValues, 5);
            break;
        case 'sixes':
            score = sumOfValue(diceValues, 6);
            break;
        case 'threeOfAKind':
            score = sumOfAKind(diceValues, 3);
            break;
        case 'fourOfAKind':
            score = sumOfAKind(diceValues, 4);
            break;
        case 'fullHouse':
            score = fullHouse(diceValues);
            break;
        case 'smallStraight':
            score = smallStraight(diceValues);
            break;
        case 'largeStraight':
            score = largeStraight(diceValues);
            break;
        case 'yatzy':
            score = yatzy(diceValues);
            break;
        case 'chance':
            score = diceValues.reduce((sum, val) => sum + val, 0);
            break;
    }

    game.scores[scoreBox] = score;
    updateOverallScore(game);
}

function sumOfValue(diceValues, value) {
    return diceValues.filter(dice => dice === value).reduce((sum, val) => sum + val, 0);
}

function sumOfAKind(diceValues, count) {
    const counts = {};
    for (const value of diceValues) {
        counts[value] = (counts[value] || 0) + 1;
        if (counts[value] >= count) {
            return diceValues.reduce((sum, val) => sum + val, 0);
        }
    }
    return 0;
}

function fullHouse(diceValues) {
    const counts = {};
    let hasThree = false;
    let hasTwo = false;

    for (const value of diceValues) {
        counts[value] = (counts[value] || 0) + 1;
    }

    for (const key in counts) {
        if (counts[key] === 3) hasThree = true;
        if (counts[key] === 2) hasTwo = true;
    }

    return hasThree && hasTwo ? 25 : 0;
}

function smallStraight(diceValues) {
    const smallStraightPatterns = [
        [1, 2, 3, 4],
        [2, 3, 4, 5],
        [3, 4, 5, 6]
    ];
    for (const pattern of smallStraightPatterns) {
        if (pattern.every(value => diceValues.includes(value))) {
            return 30;
        }
    }
    return 0;
}

function largeStraight(diceValues) {
    const largeStraightPatterns = [
        [1, 2, 3, 4, 5],
        [2, 3, 4, 5, 6]
    ];
    for (const pattern of largeStraightPatterns) {
        if (pattern.every(value => diceValues.includes(value))) {
            return 40;
        }
    }
    return 0;
}

function yatzy(diceValues) {
    return diceValues.every(value => value === diceValues[0]) ? 50 : 0;
}

function updateOverallScore(game) {
    const upperSectionScores = ['ones', 'twos', 'threes', 'fours', 'fives', 'sixes'];
    let upperSectionTotal = 0;

    for (const box of upperSectionScores) {
        upperSectionTotal += game.scores[box] || 0;
    }

    game.bonus = upperSectionTotal >= 63 ? 50 : 0;
    let lowerSectionTotal = 0;

    for (const key in game.scores) {
        if (!upperSectionScores.includes(key)) {
            lowerSectionTotal += game.scores[key] || 0;
        }
    }

    game.totalScore = upperSectionTotal + game.bonus + lowerSectionTotal;
}
