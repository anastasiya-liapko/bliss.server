module.exports = {
  "env": {
    "browser": true,
    "es6": true,
    "mocha": true
  },
  "plugins": ["mocha"],
  "extends": "eslint:recommended",
  "parserOptions": {
    "sourceType": "module"
  },
  "globals": {
    "$": true,
    "Swiper": true,
    "Cookies": true,
    "Form": true,
    "Toast": true,
    "ProgressBar": true
  },
  "rules": {
    "no-console": 0,
    "indent": [
      "error",
      2
    ],
    "quotes": [
      "error",
      "single"
    ],
    "semi": [
      "error",
      "always"
    ]
  }
};
