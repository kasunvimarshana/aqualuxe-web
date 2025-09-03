export function formatPrice(amount, symbol = '$') {
  const num = Number(amount);
  if (Number.isNaN(num)) return `${symbol}0.00`;
  return `${symbol}${num.toFixed(2)}`;
}
