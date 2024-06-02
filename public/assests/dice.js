class Dice {
    constructor(sides = 6) {
        this.sides = sides;
    }

    roll() {
        const x = Math.random();
        const result = Math.floor(x * this.sides) + 1;
        return result
    }
}


const dice = new Dice();
console.log(dice.roll()); 
