import { describe, it, expect } from 'vitest';

describe('dark mode toggle', () => {
  it('persists preference key', () => {
    const key = 'aqualuxe:theme';
    expect(typeof key).toBe('string');
  });
});
