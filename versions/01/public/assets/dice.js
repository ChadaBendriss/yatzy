class Dice {
    constructor() {
        this.value = 1;
        this.roll();
    }

    roll() {
        this.value = Math.floor(Math.random() * 6) + 1;
    }
}

function rollDice(dice) {
    dice.roll();
    return dice.value;
}
