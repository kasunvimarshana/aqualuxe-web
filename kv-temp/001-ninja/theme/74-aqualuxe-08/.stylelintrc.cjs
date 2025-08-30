module.exports = {
  extends: ['stylelint-config-standard'],
  rules: {
  'at-rule-no-unknown': [true, { ignoreAtRules: ['tailwind', 'apply'] }],
  'function-no-unknown': [true, { ignoreFunctions: ['theme'] }],
  },
};
