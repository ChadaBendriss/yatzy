class YatzyGame {
    constructor() {
        this.resetGame();
    }

    resetGame() {
        this.turn = 0;
        this.dice = [new Dice(), new Dice(), new Dice(), new Dice(), new Dice()];
        this.keep = [false, false, false, false, false];
        this.scores = {};
        this.bonus = 0;
        this.totalScore = 0;
    }

    rollDice() {
        if (this.turn >= 3) return;
        for (let i = 0; i < this.dice.length; i++) {
            if (!this.keep[i]) {
                this.dice[i].roll();
            }
        }
        this.turn++;
    }

    keepDice(index) {
        this.keep[index] = true;
    }

    releaseDice(index) {
        this.keep[index] = false;
    }

    resetTurn() {
        this.turn = 0;
        this.keep = [false, false, false, false, false];
    }

    newTurn() {
        this.resetTurn();
        this.dice = [new Dice(), new Dice(), new Dice(), new Dice(), new Dice()];
    }

    getDiceValues() {
        return this.dice.map(d => d.value);
    }
}
