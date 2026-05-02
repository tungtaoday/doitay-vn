// Profession-specific placeholder images from design stitch (Google AI generated)
const PLACEHOLDER_IMAGES: Record<string, string> = {
  electric: 'https://lh3.googleusercontent.com/aida-public/AB6AXuCl62-YA2pH38HtKVTw-WUYSAXZILvt4MoxzPn7d6l1SY9RRYheqB7jA-EsotYPkyMqJqrKvLstq0xdL1B3TSY_SGppsN-ohf-IUMTWYYWBU-WTYFJfCc-l2TfDxlLAXShpiyCVSMJMjGcAUcLe3wcP29DoPB5Ao-XwU6g5nZqP9B5MZmkc2eNRsqair4ksUuf8dQc82AXPo1eT_jKZWQGBQOGJzhAhqguBLiPZaD0dle2E17f-_bVJfzvhCBBQvj868yNDqsBhX5yg',
  plumbing:  'https://lh3.googleusercontent.com/aida-public/AB6AXuALSW3nWbn0-0LSL5-X9NL4MH075PfMbcHu-OTj7cR0GwsZ9ZnIi9aCRaT7osxCNzqqBAPBJFS8HZsfUe0YgxhT9te4GXWp6BX89Pkj-uVnqZ64Md6yiCxF1KhFtwrcEcnUlqXAvk-BMO_QxpSLP8h8IXREK64JjYM1JZxGtro5HOcSmDSD5AiPvDa1ZYIgfFJW6qVf60oamqBWWbT_QLKiGsA6XppfNixWK1Vjjja74M97qcsmg-w9eFTNvAKf1-SzRm7pa8YpZhKx',
  cooling:   'https://lh3.googleusercontent.com/aida-public/AB6AXuCwVVPJnma4-cgqm1NYTFZiQ98oKdh7z7cHcfMY3FDbF9_w8MOA6wKB3FPmexkeBCV-Hzk6wTGkvuEEItv7lwe-cQDq1-7yeRI3kfQLKoVVdSRfcfKIxLoVlWzh_GvR3b_iX0_TA-cd9L5PT9iWX4iT7D5wTGpGtD7W1MJw9raQ9kEnKBHg-iV3lFOsNfIC1jarQjOHWIHsZjzxsjZ9etmyOgy7vV2zdYY22B_nXUn-E8sqSEJ2-P8XABc5GLRNlaZnFpd4y_Ob5Die',
  general:   'https://lh3.googleusercontent.com/aida-public/AB6AXuCTzzrdorE-Ei3PAj_fqoCbfyi17TfafvIcB0nwfMnvTW4QYLmV3OQ2gUuYVsv7rNftmYkC9Nce8PQEWsXm9cJPjCXL2bsicfiC2o8vtdU3hxGNRNnkGMuLaxxE3LO_4RC96HAzhGMgCdP01hw6o7zCXG-yJMWPs0cC4YQT6g1OYcC-Z38jnAsweT5wP0Czdf6gOvnRPCn4u0Gdf7Ljhto1rIwIXPM0SmEFHtUcguj42a2JRB7ja58CK3yVQj-4A-zh5VJgVPNIi9ez',
};

export function getPlaceholderImage(categoryName?: string | null): string {
  const name = (categoryName ?? '').toLowerCase();
  if (/nước|ống|thông|bồn/.test(name)) return PLACEHOLDER_IMAGES.plumbing;
  if (/máy lạnh|điều hòa|điều hoà|lạnh trung tâm|vrv|vrf/.test(name)) return PLACEHOLDER_IMAGES.cooling;
  if (/điện/.test(name)) return PLACEHOLDER_IMAGES.electric;
  return PLACEHOLDER_IMAGES.general;
}
