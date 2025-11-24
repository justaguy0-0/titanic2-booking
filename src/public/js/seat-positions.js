
const seatPositions = {
    "first-class": [
        { top: 27, left: 19.7 },
        { top: 27, left: 31.1 },
        { top: 27, left: 42.6 },
        { top: 27, left: 54.2 },
        { top: 27, left: 65.6 },
        { top: 27, left: 77, isTrapezoid: "left" },

        { top: 72.6, left: 19.7 },
        { top: 72.6, left: 31.1 },
        { top: 72.6, left: 42.6 },
        { top: 72.6, left: 54.2 },
        { top: 72.6, left: 65.6 },
        { top: 72.6, left: 77, isTrapezoid: "right" },
    ],

    "business-class": [
        { top: 27.8, left: 13.1 },
        { top: 27.8, left: 20.7 },
        { top: 27.8, left: 28.5 },
        { top: 27.8, left: 36.3 },
        { top: 27.8, left: 44.1 },
        { top: 27.8, left: 51.9 },
        { top: 27.8, left: 59.7 },
        { top: 27.8, left: 67.5 },
        { top: 27.8, left: 77.5, isTrapezoid: "left" },

        { top: 72.1, left: 13.1 }, // Место 10
        { top: 72.1, left: 20.7 }, // Место 11
        { top: 72.1, left: 28.5 }, // Место 12
        { top: 72.1, left: 36.3 }, // Место 13
        { top: 72.1, left: 44.1 }, // Место 14
        { top: 72.1, left: 51.9 }, // Место 15
        { top: 72.1, left: 59.7 }, // Место 16
        { top: 72.1, left: 67.5 }, // Место 17
        { top: 72.1, left: 77.5, isTrapezoid: "right" },
    ],

    "economy-class": [
        { top: 21, left: 16 },
        { top: 21, left: 21 },
        { top: 21, left: 24.4 },
        { top: 21, left: 29.4 },
        { top: 21, left: 32.7 },
        { top: 21, left: 37.8 },
        { top: 21, left: 41 },
        { top: 21, left: 46.1 },
        { top: 21, left: 49.3 },
        { top: 21, left: 54.4 },
        { top: 21, left: 57.6 },
        { top: 21, left: 62.8 },
        { top: 21, left: 65.9 },
        { top: 21, left: 71.4 },
        { top: 28, left: 79.6, isTrapezoid: "left" },

        { top: 80, left: 16 },
        { top: 80, left: 21 },
        { top: 80, left: 24.4 },
        { top: 80, left: 29.4 },
        { top: 80, left: 32.7 },
        { top: 80, left: 37.8 },
        { top: 80, left: 41 },
        { top: 80, left: 46.1 },
        { top: 80, left: 49.3 },
        { top: 80, left: 54.4 },
        { top: 80, left: 57.6 },
        { top: 80, left: 62.8 },
        { top: 80, left: 65.9 },
        { top: 80, left: 71.4 },
        { top: 72.3, left: 79.6, isTrapezoid: "right" },
    ],
};

if (typeof module !== 'undefined' && module.exports) {
    module.exports = seatPositions;
}
