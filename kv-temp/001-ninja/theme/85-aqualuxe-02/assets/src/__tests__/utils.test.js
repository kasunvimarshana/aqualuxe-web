import { formatPrice } from '../utils';

test('formats price with two decimals', () => {
  expect(formatPrice(12)).toBe('$12.00');
});

test('handles NaN input', () => {
  expect(formatPrice('abc')).toBe('$0.00');
});
