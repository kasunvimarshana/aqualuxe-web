export async function postJSON(url, data, nonce) {
  const res = await fetch(url, { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-WP-Nonce': nonce || '' }, body: JSON.stringify(data) });
  if (!res.ok) throw new Error(`HTTP ${res.status}`);
  return res.json();
}
